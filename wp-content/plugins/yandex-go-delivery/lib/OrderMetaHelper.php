<?php

namespace WCYandexTaxiDeliveryPlugin;

defined('ABSPATH') || exit;

use WCYandexTaxiDeliveryPlugin\Repositories\ClaimMetaRepository;
use YandexTaxi\Delivery\Entities\Claim\Status;
use YandexTaxi\Delivery\Entities\RoutePoint\RoutePointVisitStatus;
use WC_Order;

/**
 * Class OrderMetaHelper
 *
 * @package WCYandexTaxiDeliveryPlugin
 */
class OrderMetaHelper
{
    /** @var StatusStorage */
    private static $storage;

    /** @var ClaimMetaRepository */
    private static $metaRepository;

    public static function updateShippingStatus(WC_Order $order, Status $status): void
    {
        $claimId = self::getClaimId($order);

        if (empty($claimId)) {
            return;
        }

        self::getStorage()->storeShipmentStatus($claimId, $status);
    }

    public static function getShippingStatus(WC_Order $order): ?Status
    {
        $claimId = self::getClaimId($order);

        if (empty($claimId)) {
            return null;
        }

        $rawStatus = self::getStorage()->getShipmentStatus($claimId);

        if (empty($rawStatus)) {
            return null;
        }

        return Status::fromCode($rawStatus);
    }

    public static function updateRoutePointStatus(WC_Order $order, RoutePointVisitStatus $status): void
    {
        $claimId = self::getClaimId($order);

        if (empty($claimId)) {
            return;
        }

        $oldStatusCode = self::getStorage()->getOrderStatus($order->get_id(), $claimId);

        self::getStorage()->storeOrderStatus($order->get_id(), $claimId, $status);

        (new OrderAutoStatusChanger())->changeIfNeeded(
            $order,
            is_null($oldStatusCode) ? null : RoutePointVisitStatus::fromCode($oldStatusCode),
            $status
        );
    }

    private static function getClaimId(WC_Order $order): ?string
    {
        return self::getClaimMetaRepository()->getClaimIdByOrder($order->get_id());
    }

    private static function getStorage(): StatusStorage
    {
        if (is_null(self::$storage)) {
            self::$storage = new StatusStorage();
        }

        return self::$storage;
    }

    private static function getClaimMetaRepository(): ClaimMetaRepository
    {
        if (is_null(self::$metaRepository)) {
            self::$metaRepository = new ClaimMetaRepository();
        }

        return self::$metaRepository;
    }
}
