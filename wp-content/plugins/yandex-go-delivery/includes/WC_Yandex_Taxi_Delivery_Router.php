<?php

defined('ABSPATH') || exit;

use WCYandexTaxiDeliveryPlugin\Constants;

/**
 * Class WC_Yandex_Taxi_Delivery_Router
 */
final class WC_Yandex_Taxi_Delivery_Router
{
    public function init(): void
    {
        add_action('admin_post_' . YGO_PLUGIN_ID . '/create-claim', [WC_Yandex_Taxi_Delivery_Claim_Controller::class, 'create_claim']);
        add_action('admin_post_' . YGO_PLUGIN_ID . '/get-claim', [WC_Yandex_Taxi_Delivery_Claim_Controller::class, 'get_claim']);
        add_action('admin_post_' . YGO_PLUGIN_ID . '/confirm', [WC_Yandex_Taxi_Delivery_Claim_Controller::class, 'confirm']);
        add_action('admin_post_' . YGO_PLUGIN_ID . '/get-cancel-info', [WC_Yandex_Taxi_Delivery_Claim_Controller::class, 'get_cancel_info']);
        add_action('admin_post_' . YGO_PLUGIN_ID . '/cancel', [WC_Yandex_Taxi_Delivery_Claim_Controller::class, 'cancel']);
        add_action('admin_post_' . YGO_PLUGIN_ID . '/get-order-route-point', [WC_Yandex_Taxi_Delivery_Claim_Controller::class, 'get_order_route_point']);
        add_action('admin_post_' . YGO_PLUGIN_ID . '/get-tariffs', [WC_Yandex_Taxi_Delivery_Claim_Controller::class, 'get_tariffs']);
        add_action('admin_post_' . YGO_PLUGIN_ID . '/warehouses/delete', [WC_Yandex_Taxi_Delivery_Warehouse_Controller::class, 'delete']);
        add_action('admin_post_' . YGO_PLUGIN_ID . '/get-translations', [WC_Yandex_Taxi_Delivery_Setting_Controller::class, 'get_translations']);
    }
}
