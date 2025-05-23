<?php

namespace WCYandexTaxiDeliveryPlugin;

defined( 'ABSPATH' ) || exit;

use WC_Countries;
use YandexTaxi\Delivery\Entities\ClaimItem\ClaimItem;
use YandexTaxi\Delivery\Entities\RoutePoint\Address;
use YandexTaxi\Delivery\Exceptions\ValidationError;
use YandexTaxi\Delivery\GeoCoding\Point;
use YandexTaxi\Delivery\GeoCoding\YandexMaps\YandexGeoCoder;
use YandexTaxi\Delivery\GeoCoding\GoogleMaps\GoogleGeoCoder;
use YandexTaxi\Delivery\GeoCoding\Dadata\DadataGeoCoder;
use YandexTaxi\Delivery\YandexApi\Exceptions\YandexApiException;
use YandexTaxi\Delivery\YandexApi\Resources\PriceChecker;

/**
 * Class ClientPackageService
 *
 * @package WCYandexTaxiDeliveryPlugin
 */
class ClientPackageService extends BasePackageService {
	/** @var PriceChecker */
	private $priceChecker;

	/** @var YandexGeoCoder|GoogleGeoCoder|DadataGeoCoder */
	private $geoCoder;

	public function __construct() {
		$this->priceChecker = Container::get( PriceChecker::class );
		$this->geoCoder     = Container::get( ygo_get_geocoder_classname() );
	}

	/**
	 * @param array $package
	 *
	 * @return float|null
	 * @throws ValidationError
	 * @throws YandexApiException
	 */
	public function calculateSum( array $package ): ?float {
		$items  = $this->prepareItems( $package['contents'] );
		$source = $this->getSource();

		$destination = $this->prepareDestination( $package );

		$checkPriceResult = $this->priceChecker->calculate( $items, [ $source, $destination ] );

		return $checkPriceResult->getPrice()->getValue();
	}

	/**
	 * @param array $products
	 *
	 * @return ClaimItem[]
	 */
	private function prepareItems( array $products ): array {
		$items = [];

		foreach ( $products as $item ) {
			$items[] = $this->prepareItem( $item['data'], null, $item['quantity'] );
		}

		return $items;
	}

	private function getSource(): Address {
		$settings = get_option( YGO_PLUGIN_SETTINGS );
		if ( 'Chile' === $settings['country'] ) {
			//$warehouse = unserialize( 'O:45:"WCYandexTaxiDeliveryPlugin\Entities\Warehouse":13:{s:49:" WCYandexTaxiDeliveryPlugin\Entities\Warehouse id";i:4;s:54:" WCYandexTaxiDeliveryPlugin\Entities\Warehouse address";s:75:"Чили, Сантьяго, проспект Санта Исабель, 727";s:50:" WCYandexTaxiDeliveryPlugin\Entities\Warehouse lat";d:-33.450082568939;s:50:" WCYandexTaxiDeliveryPlugin\Entities\Warehouse lon";d:-70.645086;s:58:" WCYandexTaxiDeliveryPlugin\Entities\Warehouse contactName";s:8:"Хосе";s:59:" WCYandexTaxiDeliveryPlugin\Entities\Warehouse contactEmail";s:12:"aaa@chile.cl";s:59:" WCYandexTaxiDeliveryPlugin\Entities\Warehouse contactPhone";s:12:"+56221234567";s:56:" WCYandexTaxiDeliveryPlugin\Entities\Warehouse startTime";s:5:"00:00";s:54:" WCYandexTaxiDeliveryPlugin\Entities\Warehouse endTime";s:5:"00:00";s:54:" WCYandexTaxiDeliveryPlugin\Entities\Warehouse comment";s:0:"";s:51:" WCYandexTaxiDeliveryPlugin\Entities\Warehouse flat";s:3:"123";s:52:" WCYandexTaxiDeliveryPlugin\Entities\Warehouse porch";s:0:"";s:52:" WCYandexTaxiDeliveryPlugin\Entities\Warehouse floor";s:1:"5";}' );
			$warehouse = new \WCYandexTaxiDeliveryPlugin\Entities\Warehouse( 4, 'Чили, Сантьяго, проспект Санта Исабель, 727', '-33.450082568939', '-70.645086', 'aaa@chile.cl', 'Хосе', '+56221234567', '00:00', '00:00', '', 123, '', 5 );

		} else {
			$warehouse = DefaultWarehouseFinder::find();
		}

		if ( empty( $warehouse ) ) {
			throw new ValidationError( 'Default warehouse is not found' );
		}

		return new Address( $warehouse->getAddress(), $warehouse->getLat(), $warehouse->getLon() );
	}

	private function prepareDestination( array $package ): Address {
		$addressLine = $this->getAddressLineFromPackage( $package );
		$addressLine = trim( $addressLine );

		if ( empty( $addressLine ) ) {
			throw new ValidationError( 'Address is not filled' );
		}

		if ( isset( WC()->session ) ) {
			$stored = WC()->session->get( 'last_address' );

			if ( ! is_null( $stored ) && isset( $stored['address'] ) && $stored['address'] === $addressLine ) {
				return new Address( $addressLine, $stored['lat'], $stored['lon'] );
			}
		}

		$settings = get_option( YGO_PLUGIN_SETTINGS );
		if ( 'Chile' === $settings['country'] ) {
			$point = new Point( '-33.45008256893874', '-70.64508599999998' );
		} else {
			$point = $this->geoCoder->decode( $addressLine );
		}

		$this->saveAddressDataInSession( $addressLine, $point );

		return new Address( $addressLine, $point->getLat(), $point->getLon() );
	}

	private function saveAddressDataInSession( string $address, Point $point ): void {
		if ( ! isset( WC()->session ) ) {
			return;
		}

		WC()->session->set( 'last_address', [
			'address' => $address,
			'lat'     => $point->getLat(),
			'lon'     => $point->getLon(),
		] );
	}

	private function getAddressLineFromPackage( array $package ): string {
		$parts = $package['destination'];
		$store = new WC_Countries();
		if ( empty( $parts['city'] ) ) {
			$parts['city'] = $store->get_base_city();
		}
		if ( empty( $parts['country'] ) ) {
			$parts['country'] = $store->get_base_country();
		}

		$country = WC()->countries->countries[ sanitize_text_field( $parts['country'] ) ];

		return implode( ', ', array_filter( [
			$country,
			sanitize_text_field( $parts['city'] ),
			sanitize_text_field( $parts['address'] ),
		] ) );
	}
}
