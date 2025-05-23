<?php

use WCYandexTaxiDeliveryPlugin\Constants;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Yandex Go Delivery Gateway for cash payment.
 *
 * @class       WC_Yandex_Taxi_Delivery_Payment_Method_Card
 * @extends     WC_Gateway_COD
 * @version     1.0.0
 */
class WC_Yandex_Taxi_Delivery_Payment_Method_Cash extends WC_Yandex_Taxi_Delivery_Payment_Method {

	/**
	 * Setup general properties for the gateway.
	 */
	protected function setup_properties() {
		$this->id                 = 'ygo_cash';
		$this->icon               = '';
		$this->method_title       = __( 'Оплата наличными при получении (Яндекс Доставка)', 'yandex-go-delivery' );
		$this->method_description = __( 'Оплата наличными при получении (Яндекс Доставка)', 'yandex-go-delivery' );
		$this->instructions       = $this->method_description;
		$this->has_fields         = false;

		parent::setup_properties();
	}

	/**
	 * woocommerce_payment_gateways hook handler
	 *
	 * @param $methods
	 *
	 * @return array
	 */
	public static function add_gateway_class( $methods ): array {
		$methods[] = self::class;

		return $methods;
	}
}
