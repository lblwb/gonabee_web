<?php

defined('ABSPATH') || exit;

use WCYandexTaxiDeliveryPlugin\PluginActivationServices\ExistingStatusesSyncer;
use WCYandexTaxiDeliveryPlugin\PluginActivationServices\ExistingWarehouseSyncer;
use WCYandexTaxiDeliveryPlugin\PluginActivationServices\ClaimSyncer;

/**
 * Class WC_Yandex_Taxi_Delivery_Activation
 */
class WC_Yandex_Taxi_Delivery_Activation
{
    private const TASK_NAME = 'get_new_events_hook';
    private const PERIOD_NAME = '2min';

    public static function handleActivation()
    {
        ( new WC_Yandex_Taxi_Delivery_App() )->init();

        (new ExistingStatusesSyncer())->syncIfNeeded();
        (new ExistingWarehouseSyncer())->syncIfNeeded();
        (new ClaimSyncer())->syncIfNeeded();

        self::register_cron();
    }

    public static function handleDeactivation()
    {
        self::unregister_cron();
    }

    private static function register_cron()
    {
        if (!wp_next_scheduled(self::TASK_NAME)) {
            wp_schedule_event(time(), self::PERIOD_NAME, self::TASK_NAME);
        }
    }

    private static function unregister_cron()
    {
        if (wp_next_scheduled(self::TASK_NAME)) {
            wp_clear_scheduled_hook(self::TASK_NAME);
        }
    }
}
