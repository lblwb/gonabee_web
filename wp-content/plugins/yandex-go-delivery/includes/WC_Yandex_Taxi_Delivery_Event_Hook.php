<?php

defined('ABSPATH') || exit;

use YandexTaxi\Delivery\YandexApi\Client;
use YandexTaxi\Delivery\YandexApi\Resources\Journal;
use WCYandexTaxiDeliveryPlugin\Repositories\CursorRepository;
use YandexTaxi\Delivery\Services\EventService;
use YandexTaxi\Delivery\YandexApi\Resources\Claims;
use WCYandexTaxiDeliveryPlugin\OrderEventsHandler;
use YandexTaxi\Delivery\YandexApi\Resources\DriverPhones;
use WCYandexTaxiDeliveryPlugin\Container;

/**
 * Class WC_Yandex_Taxi_Delivery_Event_Hook
 */
final class WC_Yandex_Taxi_Delivery_Event_Hook
{
    public static function handle()
    {
        $api = Container::get(Client::class);

        $eventService = new EventService(new Journal($api), new CursorRepository());
        $claims = new Claims($api);
        $driverPhone = new DriverPhones($api);

        (new OrderEventsHandler($eventService, $claims, $driverPhone))->applyNewChanges();
    }
}
