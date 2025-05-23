<?php

defined('ABSPATH') || exit;

use WCYandexTaxiDeliveryPlugin\Constants;

/**
 * Class WC_Yandex_Taxi_Delivery_Shipping_Controller
 */
final class WC_Yandex_Taxi_Delivery_Shipping_Controller
{
    public function init()
    {
        // disable cod for ya delivery
        add_filter('woocommerce_available_payment_gateways', [$this, 'disable_cod_gateway_for_ya_delivery']);

        add_action('woocommerce_after_shipping_rate', [$this, 'display_after_rate']);
    }

    public function disable_cod_gateway_for_ya_delivery($available_gateways)
    {
        if (!isset(WC()->session)) {
            return $available_gateways;
        }

        $chosen_methods = WC()->session->get('chosen_shipping_methods');

        if (!isset($chosen_methods[0])) {
            return $available_gateways;
        }

        $chosen_shipping = $chosen_methods[0];
        if (strpos($chosen_shipping, YGO_PLUGIN_ID) !== 0) {
            return $available_gateways;
        }

        $settings = get_option(YGO_PLUGIN_SETTINGS);

        foreach ($available_gateways as $key => $gateway) {
            if (!in_array($key, $settings['payment_methods'])) {
                unset($available_gateways[$key]);
            }
        }

        return $available_gateways;
    }

    public function display_after_rate($shippingMethod)
    {
        if ($shippingMethod->get_id() !== YGO_PLUGIN_ID) {
            return;
        }

        $meta = $shippingMethod->get_meta_data();

        if (!isset($meta['is_free_allowed'])) {
            return;
        }

        if (!$meta['is_free_allowed']) {
            return;
        }

        if (isset($meta['is_free']) && $meta['is_free']) {
            return;
        }

        $sum = wc_price($meta['left_for_free']);

        return sprintf(esc_html__('Закажите еще на %1$s для бесплатной доставки', 'yandex-go-delivery'), $sum);
    }
}
