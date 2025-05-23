<?php

namespace WCYandexTaxiDeliveryPlugin\Http;

use WC_Yandex_Taxi_Delivery_Plugin_Version;
use YandexTaxi\Delivery\Http\Client;
use YandexTaxi\Delivery\Http\Response;

/**
 * Class WpHttpClient
 *
 * @package WCYandexTaxiDeliveryPlugin\Http
 */
class WpHttpClient implements Client {
	public function sendPost( string $url, array $body, array $headers ): Response {
		$result = wp_remote_post( $url, [
			'body'    => ! empty( $body ) ? json_encode( $body ) : '',
			'headers' => $this->getHeaders( $headers ),
		] );

		if ( is_wp_error( $result ) ) {
			return new Response( $result->get_error_code(), $result->get_error_message() );
		}

		return new Response( $result['response']['code'], $result['body'] );
	}

	public function sendGet( string $url, array $headers ): Response {
		$result = wp_remote_get( $url, [
			'headers' => $this->getHeaders( $headers ),
		] );

		if ( is_wp_error( $result ) ) {
			return new Response( $result->get_error_code(), $result->get_error_message() );
		}

		return new Response( $result['response']['code'], $result['body'] );
	}

	protected function getHeaders( $additional_headers ) {
		$plugin_version = WC_Yandex_Taxi_Delivery_Plugin_Version::get();

		return array_merge( [
			'User-Agent' => "Wordpress plugin: " . YGO_PLUGIN_BRAND . ", Verison: " . YGO_PLUGIN_VERSION . " | WooCommerce Version: {$plugin_version}"
		], $additional_headers );
	}
}
