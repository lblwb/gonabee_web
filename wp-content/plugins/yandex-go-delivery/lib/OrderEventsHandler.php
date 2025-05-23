<?php

namespace WCYandexTaxiDeliveryPlugin;

defined('ABSPATH') || exit;

use WCYandexTaxiDeliveryPlugin\Repositories\ClaimMetaRepository;
use YandexTaxi\Delivery\Entities\Claim\Claim;
use YandexTaxi\Delivery\Entities\Journal\Event;
use YandexTaxi\Delivery\Services\EventService;
use YandexTaxi\Delivery\YandexApi\Exceptions\YandexApiException;
use YandexTaxi\Delivery\YandexApi\Resources\Claims;
use YandexTaxi\Delivery\Entities\Claim\Status;
use YandexTaxi\Delivery\YandexApi\Resources\DriverPhones;
use RuntimeException;
use Exception;
use WC_Order;

/**
 * Class OrderEventsHandler
 *
 * @package WCYandexTaxiDeliveryPlugin
 */
class OrderEventsHandler
{
    /** @var EventService */
    private $events;

    /** @var Claims */
    private $claims;

    /** @var DriverPhones */
    private $driverPhones;

    public function __construct(EventService $events, Claims $claims, DriverPhones $driverPhone)
    {
        $this->events = $events;
        $this->claims = $claims;
        $this->driverPhones = $driverPhone;
    }

    public function applyNewChanges(): void
    {
        $events = $this->events->findNew();

        foreach ($this->prepareIdStatusMap($events) as $id => $status) {
            $this->updateOrderStatus($id, $status);
        }

        $needDriverAddClaimIds = $this->getClaimIdsWereInStatus($events, Status::performerFound());
        $needDriverRemoveClaimIds = $this->getClaimIdsWereInStatus($events, Status::deliveredFinish());
        $needRoutePointStatusUpdateIds = $this->getRoutePointStatusUpdateIds($events);

        $claims = $this->getClaims(array_unique(array_merge($needDriverAddClaimIds, $needRoutePointStatusUpdateIds)));

        $this->addDriverInfo($needDriverAddClaimIds, $claims);
        $this->removeDriverPhoneInfo($needDriverRemoveClaimIds);
        $this->updateRoutePointVisitStatus($needRoutePointStatusUpdateIds, $claims);
    }

    /**
     * @param Event[] $events
     * @param Status  $status
     *
     * @return string[]
     */
    private function getClaimIdsWereInStatus(array $events, Status $status): array
    {
        $ids = [];

        foreach ($events as $event) {
            if ($event->statusWasChanged() && $event->getNewStatus()->equals($status)) {
                $ids[] = $event->getClaimId();
            }
        }

        return array_unique($ids);
    }

    /**
     * @param Event[] $events
     *
     * @return string[]
     */
    private function getRoutePointStatusUpdateIds(array $events): array
    {
        $ids = [];

        foreach ($events as $event) {
            if ($event->statusWasChanged()) {
                $status = $event->getNewStatus();
                if ($status->in(Status::delivered(), Status::readyForDeliveryConfirmation(), Status::deliveryArrived())) {
                    $ids[] = $event->getClaimId();
                }
            }
        }

        return array_unique($ids);
    }

    /**
     * @param string[] $ids
     *
     * @return Claim[]
     */
    private function getClaims(array $ids): array
    {
        try {
            $claims = [];
            foreach ($this->claims->getBulk($ids) as $claim) {
                $claims[$claim->getId()] = $claim;
            }
            return $claims;
        } catch (Exception $exception) {
            return [];
        }
    }

    private function updateRoutePointVisitStatus(array $deliveryIds, array $claims): void
    {
        foreach ($deliveryIds as $deliveryId) {
            if (!isset($claims[$deliveryId])) {
                continue;
            }

            /** @var Claim $claim */
            $claim = $claims[$deliveryId];

            foreach ($this->getOrders($deliveryId) as $order) {
                try {
                    OrderMetaHelper::updateRoutePointStatus($order, $claim->getRoutePointStatus($order->get_id()));
                } catch (RuntimeException $exception) {
                    // skip to process all orders
                }
            }
        }
    }

    private function updateOrderStatus(string $deliveryId, Status $status): void
    {
        foreach ($this->getOrders($deliveryId) as $order) {
            OrderMetaHelper::updateShippingStatus($order, $status);
        }
    }

    /**
     * @param string[] $ids
     * @param Claim[]  $claims
     */
    private function addDriverInfo(array $ids, array $claims): void
    {
        foreach ($ids as $id) {
            if (!isset($claims[$id])) {
                continue;
            }

            /** @var Claim $claim */
            $claim = $claims[$id];


            try {
                $driverPhone = $this->driverPhones->get($id);
            } catch (YandexApiException $exception) {
                $driverPhone = null;
            }

            ClaimMetaHelper::updateDriver($id, $claim->getDriver());
            ClaimMetaHelper::updateDriverPhone($id, $driverPhone);
        }
    }

    /**
     * @param string[] $ids
     */
    private function removeDriverPhoneInfo(array $ids): void
    {
        foreach ($ids as $id) {
            ClaimMetaHelper::updateDriverPhone($id, null);
        }
    }

    /**
     * @param Event[] $events
     *
     * @return array
     */
    private function prepareIdStatusMap(array $events): array
    {
        $map = [];

        foreach ($events as $event) {
            if ($event->statusWasChanged()) {
                $map[$event->getClaimId()] = $event->getNewStatus();
            }
        }

        return $map;
    }

    /**
     * @param string $deliveryId
     *
     * @return WC_Order[]
     */
    private function getOrders(string $deliveryId): array
    {
        $metaRepository = new ClaimMetaRepository();
        $orderIds = $metaRepository->getOrdersByClaimId($deliveryId);

        if (empty($orderIds)) {
            return [];
        }

        $orders = [];

        foreach ($orderIds as $orderId) {

            $order = wc_get_order($orderId);

            if (empty($order)) {
                continue;
            }

            $orders[] = $order;
        }

        return $orders;
    }
}
