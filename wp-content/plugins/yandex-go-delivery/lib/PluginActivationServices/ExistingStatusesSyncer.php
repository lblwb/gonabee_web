<?php

namespace WCYandexTaxiDeliveryPlugin\PluginActivationServices;

defined('ABSPATH') || exit;

use WCYandexTaxiDeliveryPlugin\Constants;
use WCYandexTaxiDeliveryPlugin\OrderMetaHelper;
use WCYandexTaxiDeliveryPlugin\StatusStorage;
use YandexTaxi\Delivery\Entities\Claim\Status;
use YandexTaxi\Delivery\Entities\RoutePoint\RoutePointVisitStatus;
use WC_Order;

/**
 * Class ExistingStatusesSyncer
 *
 * @package WCYandexTaxiDeliveryPlugin\PluginActivationServices
 */
class ExistingStatusesSyncer
{
    private const WERE_SYNCED_SETTING_NAME = 'were_statuses_synced';

    /** @var StatusStorage */
    private $statusStorage;

    public function __construct()
    {
        $this->statusStorage = new StatusStorage();
    }

    public function syncIfNeeded(): void
    {
        if (!$this->needSync()) {
            return;
        }

        foreach ($this->findOrdersWithClaimId() as $order) {
            $this->syncOrder($order);
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

    private function syncOrder(WC_Order $order): void
    {
        $this->storeCommonStatus($order);
        $this->storeRoutePointStatus($order);
    }

    private function storeCommonStatus(WC_Order $order): void
    {
        $rawStatus = $order->get_meta(Constants::getShippingStatusMetaParamName() );

        if (empty($rawStatus)) {
            return;
        }

        $status = Status::fromCode($rawStatus);

        if (!$status->in(...Status::values())) {
            return;
        }

        OrderMetaHelper::updateShippingStatus($order, $status);
    }

    private function storeRoutePointStatus(WC_Order $order): void
    {
        $rawVisitStatus = $order->get_meta(Constants::getShippingRoutePointMetaParamName() );

        if (empty($rawVisitStatus)) {
            return;
        }

        $visitStatus = RoutePointVisitStatus::fromCode($rawVisitStatus);

        if (!$visitStatus->in(...RoutePointVisitStatus::values())) {
            return;
        }

        OrderMetaHelper::updateRoutePointStatus($order, $visitStatus);
    }

    private function markStatusesWereSynced(): void
    {
        $settings = get_option(YGO_PLUGIN_SETTINGS);
        $settings[self::WERE_SYNCED_SETTING_NAME] = 'yes';

        update_option(YGO_PLUGIN_SETTINGS, $settings);
    }
}
