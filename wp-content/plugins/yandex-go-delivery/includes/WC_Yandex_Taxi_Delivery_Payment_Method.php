<?php

use WCYandexTaxiDeliveryPlugin\Constants;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Yandex Go Delivery Gateway for payment.
 *
 * @class       WC_Yandex_Taxi_Delivery_Payment_Method
 * @extends     WC_Gateway_COD
 * @version     1.0.0
 */
abstract class WC_Yandex_Taxi_Delivery_Payment_Method extends WC_Gateway_COD {

	/**
	 * Constructor for the gateway.
	 */
	public function __construct() {
		parent::__construct();

		$this->enable_for_methods = [ YGO_PLUGIN_ID, 'cod' ];
	}

	/**
	 * Setup general properties for the gateway.
	 */
	protected function setup_properties() {
		add_filter( 'yandex_go/post_payment_methods', [ $this, 'post_payment_method' ] );
	}

	/**
	 * Initialise Gateway Settings Form Fields.
	 */
	public function init_form_fields() {
		parent::init_form_fields();

		$this->form_fields['title']['default']       = $this->method_title;
		$this->form_fields['description']['default'] = $this->method_description;
		unset( $this->form_fields['enable_for_methods'] );
	}

	/**
	 * yandex_go/post_payment_methods filter handler
	 *
	 * @param $methods
	 *
	 * @return array
	 */
	public function post_payment_method( $methods ): array {
		$methods[] = $this->id;

		return $methods;
	}
}
