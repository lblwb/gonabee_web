<?php

namespace WCYandexTaxiDeliveryPlugin\PluginActivationServices;

defined('ABSPATH') || exit;

use WCYandexTaxiDeliveryPlugin\Constants;
use WCYandexTaxiDeliveryPlugin\Repositories\ClaimMetaRepository;
use WC_Order;

/**
 * Class ClaimSyncer
 *
 * @package WCYandexTaxiDeliveryPlugin\PluginActivationServices
 */
class ClaimSyncer
{
    private const WERE_SYNCED_SETTING_NAME = 'was_claim_meta_synced';

    /** @var ClaimMetaRepository */
    private $repository;

    public function __construct()
    {
        $this->repository = new ClaimMetaRepository();
    }

    public function syncIfNeeded(): void
    {
        if (!$this->needSync()) {
            return;
        }

        $claimIds = [];
        
        foreach ($this->findOrdersWithClaimId() as $order) {
            $claimIds[] = $order->get_meta(Constants::getDeliveryIdMetaParamName());
        }

        $claimIds = array_unique($claimIds);

        foreach ($claimIds as $claimId) {
            $this->syncClaim($claimId);
        }

        $this->markStatusesWereSynced();
    }

    private function needSync(): bool
    {
        $settings = get_option(YGO_PLUGIN_SETTINGS);

        if (isset($settings[self::WERE_SYNCED_SETTING_NAME]) && $settings[self::WERE_SYNCED_SETTING_NAME] === 'yes') {
            return false;
        }

        return true;
    }

    /**
     * @return WC_Order[]
     */
    private function findOrdersWithClaimId(): array
    {
        return wc_get_orders(array(
            'limit' => -1, // Query all orders
            'orderby' => 'date',
            'order' => 'DESC',
            'meta_key' => Constants::getDeliveryIdMetaParamName(),
            'meta_compare' => 'EXISTS',
        ));
    }

    private function syncClaim(string $claimId): void
    {
        $orders = wc_get_orders([Constants::getDeliveryIdMetaParamName() => $claimId]);

        if (empty($orders)) {
            return;
        }

        /** @var WC_Order $order */
        $order = $orders[0];

        $orderIds = array_map(function (WC_Order $order) {
            return $order->get_id();
        }, $orders);

        $this->repository->storeClaimForOrder($claimId, $orderIds);
        $this->repository->updateDriver($claimId, $order->get_meta(Constants::getDriverMetaParamName()));
        $this->repository->updateShippingSlot($claimId, $order->get_meta(Constants::getShippingSlotMetaParamName()));

        foreach ($orders as $order) {
            $this->clearOrderMeta($order);
        }
    }

    private function clearOrderMeta(WC_Order $order): void
    {
        $order->delete_meta_data(Constants::getDeliveryIdMetaParamName());
        $order->delete_meta_data(Constants::getTariffMetaParamName());
        $order->delete_meta_data(Constants::getShippingSlotMetaParamName());
        $order->delete_meta_data(Constants::getDeliveryIdMetaParamName());
        $order->delete_meta_data(Constants::getShippingStatusMetaParamName());
        $order->delete_meta_data(Constants::getShippingRoutePointMetaParamName());

        $order->save_meta_data();
    }

    private function markStatusesWereSynced(): void
    {
        $settings = get_option(YGO_PLUGIN_SETTINGS);
        $settings[self::WERE_SYNCED_SETTING_NAME] = 'yes';

        update_option(YGO_PLUGIN_SETTINGS, $settings);
    }
}
