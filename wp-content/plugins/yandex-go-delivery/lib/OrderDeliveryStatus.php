<?php

namespace WCYandexTaxiDeliveryPlugin;

defined('ABSPATH') || exit;

use WC_Order;
use WCYandexTaxiDeliveryPlugin\Repositories\ClaimMetaRepository;
use YandexTaxi\Delivery\Entities\Claim\Status;

/**
 * Class ShowButton
 *
 * @package WCYandexTaxiDeliveryPlugin
 */
class OrderDeliveryStatus
{
    public static function isNotActive(WC_Order $order): bool
    {
        return !self::isActive($order);
    }

    public static function isActive(WC_Order $order): bool
    {
        $metaRepository = new ClaimMetaRepository();
        $meta = $metaRepository->getMetaForOrder($order->get_id());

        if (empty($meta)) {
            return false; // no delivery id - not active
        }

        $status = OrderMetaHelper::getShippingStatus($order);

        if (is_null($status)) {
            return true; // has delivery id, no status - active
        }

        if (!$status->in(...self::inactiveStatuses())) {
            return true; // status active - active
        }

        return false; // rest is - not active
    }

    /**
     * @return Status[]
     */
    private static function inactiveStatuses(): array
    {
        return [
            Status::fromCode('performer_not_found'),
            Status::fromCode('cancelled'),
            Status::fromCode('cancelled_with_payment'),
            Status::fromCode('cancelled_by_taxi'),
            Status::fromCode('cancelled_with_items_on_hands'),
        ];
    }
}
