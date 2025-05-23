<?php

namespace WCYandexTaxiDeliveryPlugin;

defined('ABSPATH') || exit;

use WCYandexTaxiDeliveryPlugin\Repositories\ClaimMetaRepository;
use YandexTaxi\Delivery\Entities\Claim\Status;
use YandexTaxi\Delivery\Entities\DriverPhone\DriverPhone;
use YandexTaxi\Delivery\Entities\RoutePoint\RoutePointVisitStatus;
use YandexTaxi\Delivery\Entities\Claim\Driver;
use WC_Order;

/**
 * Class ClaimMetaHelper
 *
 * @package WCYandexTaxiDeliveryPlugin
 */
class ClaimMetaHelper
{
    /** @var ClaimMetaRepository */
    private static $repository;

    public static function updateDriver(string $claimId, ?Driver $driver): void
    {
        $driverText = null;
        if (!empty($driver)) {
            $driverText = implode(' ', array_filter([
                $driver->getName(),
                $driver->getCarModel(),
                $driver->getCarNumber(),
            ]));
        }

        self::getRepository()->updateDriver($claimId, $driverText);
    }

    public static function updateDriverPhone(string $claimId, ?DriverPhone $driverPhone): void
    {
        $text = null;

        if (!empty($driverPhone)) {
            $text= sprintf(
                esc_html__('%1$s, добавочный %2$s', 'yandex-go-delivery'),
                $driverPhone->getPhone(),
                $driverPhone->getExt()
            );
        }

        self::getRepository()->updateDriverPhone($claimId, $text);
    }

    private static function getRepository(): ClaimMetaRepository
    {
        if (is_null(self::$repository)) {
            self::$repository = new ClaimMetaRepository();
        }

        return self::$repository;
    }
}
