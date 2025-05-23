<?php

use WCYandexTaxiDeliveryPlugin\ClientPackageService;
use WCYandexTaxiDeliveryPlugin\Constants;

/**
 * Get plugin settings array
 *
 * @return array
 */
function ygo_get_settings(): array {
	return WC_Yandex_Taxi_Delivery_Setting_Controller::get_settings();
}

/**
 * Get GeoCoder class name
 *
 * @return string
 */
function ygo_get_geocoder_classname(): string {
	$settings = ygo_get_settings();
	switch ( $settings['geocoder'] ?? '' ) {
		case 'google':
			$return = 'YandexTaxi\Delivery\GeoCoding\GoogleMaps\GoogleGeoCoder';
			break;
		case 'dadata':
			$return = 'YandexTaxi\Delivery\GeoCoding\Dadata\DadataGeoCoder';
			break;
		case 'yandex':
		default:
			$return = 'YandexTaxi\Delivery\GeoCoding\YandexMaps\YandexGeoCoder';
			break;
	}

	return $return;
}

/**
 * @return string
 */
function ygo_get_delivery_price( $product_id = false ): string {
	$user    = wp_get_current_user();
	$meta    = get_user_meta( $user->ID );
	$product = wc_get_product( $product_id );

	if ( $product ) {
		$package = [
			'contents'      => [
				[
					'data'     => $product,
					'quantity' => 1,
				],
			],
			'contents_cost' => 1,
			'user'          => [
				'ID' => get_current_user_id(),
			],
			'destination'   => [
				'country'   => $meta['shipping_country'][0],
				'state'     => $meta['shipping_state'][0],
				'postcode'  => $meta['shipping_postcode'][0],
				'city'      => $meta['shipping_city'][0],
				'address'   => $meta['shipping_address_1'][0],
				'address_1' => $meta['shipping_address_1'][0],
				// Provide both address and address_1 for backwards compatibility.
				'address_2' => $meta['shipping_address_2'][0],
			],
			'cart_subtotal' => $product->get_price(),
		];

		$client = new ClientPackageService();

		return $client->calculateSum( $package );
	} else {
		return '';
	}
}

function get_custom_attribute_html( $data ) {
	$custom_attributes = [];

	if ( ! empty( $data['custom_attributes'] ) && is_array( $data['custom_attributes'] ) ) {
		foreach ( $data['custom_attributes'] as $attribute => $attribute_value ) {
			$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
		}
	}

	return implode( ' ', $custom_attributes );
}
