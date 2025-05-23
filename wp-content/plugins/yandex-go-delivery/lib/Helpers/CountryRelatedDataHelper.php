<?php

namespace WCYandexTaxiDeliveryPlugin\Helpers;

use WC_Yandex_Taxi_Delivery_Plugin_Version;

/**
 * Class CountryRelatedDataHelper
 *
 * @package WCYandexTaxiDeliveryPlugin\Helpers
 */
class CountryRelatedDataHelper {
	private const RUSSIA = 'Russia';
	private const ISRAEL = 'Israel';
	private const BELARUS = 'Belarus';
	private const KAZAKHSTAN = 'Kazakhstan';
	private const UZBEKISTAN = 'Uzbekistan';
	private const ARMENIA = 'Армения';
	private const CHILE = 'Chile';
	private const SOUTHAFRICA = 'SouthAfrica';
	private const ZAMBIA = 'Zambia';
	private const COTEDIVOIRE = 'CoteDivoire';

	public static function getGeocoderInstructionUrlSource( $geocoder ): array {
		$return = [
			self::ISRAEL      => 'https://developers.google.com/maps/documentation/geocoding/get-api-key',
			self::SOUTHAFRICA => 'https://developers.google.com/maps/documentation/geocoding/get-api-key',
			self::ZAMBIA      => 'https://developers.google.com/maps/documentation/geocoding/get-api-key',
			self::COTEDIVOIRE => 'https://developers.google.com/maps/documentation/geocoding/get-api-key',
			'default'         => 'https://developers.google.com/maps/documentation/geocoding/get-api-key?hl=ru',
		];

		return $return;
	}

	public static function getGeocoderCabinetUrlSource( $geocoder ): array {
		switch ( $geocoder ) {
			case 'google':
				$return = [
					'default' => 'https://cloud.google.com/maps-platform/',
				];
				break;
			case 'dadata':
				$return = [
					'default' => 'https://dadata.ru/profile/',
				];
				break;
			case 'yandex':
			default:
				$return = [
					self::ISRAEL      => 'https://developer.tech.yandex.com/services/',
					self::SOUTHAFRICA => 'https://developer.tech.yandex.com/services/',
					self::ZAMBIA      => 'https://developer.tech.yandex.com/services/',
					self::COTEDIVOIRE => 'https://developer.tech.yandex.com/services/',
					self::BELARUS     => 'https://developer.tech.yandex.com/services/',
					self::KAZAKHSTAN  => 'https://developer.tech.yandex.com/services/',
					self::UZBEKISTAN  => 'https://developer.tech.yandex.com/services/',
					self::ARMENIA     => 'https://developer.tech.yandex.com/services/',
					'default'         => 'https://developer.tech.yandex.ru/services/',
				];
				break;
		}

		return $return;
	}

	public static function getCabinetUrlSource(): array {
		return [
			self::ISRAEL      => 'https://yango.delivery/account2',
			self::ZAMBIA      => 'https://yango.delivery/account2',
			self::COTEDIVOIRE => 'https://yango.delivery/account2',
			self::SOUTHAFRICA => 'https://yango.delivery/account2',
			self::BELARUS     => 'https://business.taxi.yandex.by/profile/settings/',
			self::KAZAKHSTAN  => 'https://delivery.yandex.kz/account2',
			self::UZBEKISTAN  => 'https://delivery.yandex.uz/account2',
			self::ARMENIA     => 'https://delivery.yandex.com/am-hy/',
			self::CHILE       => '',
			'default'         => 'https://dostavka.yandex.ru/account/cargo',
		];
	}

	public static function getConnectUrlSource(): array {
		return [
			self::ISRAEL      => 'https://yango.delivery/il-he/?ya_medium=module&ya_campaign=woocommerce&utm_source=woocommerce&utm_medium=backend#form-group',
			self::COTEDIVOIRE => 'https://yango.delivery/ci-fr/?ya_medium=module&ya_campaign=woocommerce&utm_source=woocommerce&utm_medium=backend#form-group',
			self::ZAMBIA      => 'https://yango.delivery/zm-en/?ya_medium=module&ya_campaign=woocommerce&utm_source=woocommerce&utm_medium=backend#form-group',
			self::BELARUS     => 'https://delivery.yandex.by/?ya_medium=module&ya_campaign=WooCommerce#form',
			self::KAZAKHSTAN  => 'https://delivery.yandex.kz/?ya_medium=module&ya_campaign=WooCommerce#form',
			self::UZBEKISTAN  => 'https://delivery.yandex.uz/?ya_medium=module&ya_campaign=WooCommerce#form',
			self::ARMENIA     => 'https://delivery.yandex.com/am-hy/?ya_medium=module&ya_campaign=WooCommerce#form',
			self::CHILE       => '',
			self::SOUTHAFRICA => '',
			'default'         => 'https://dostavka.yandex.ru/express-delivery?ya_source=businessdelivery&ya_medium=module&ya_campaign=WooCommerce#form',
		];
	}

	public static function getUpperPhoneCountry(): string {
		$country = self::getCountry();

		switch ( $country ) {
			case self::ISRAEL:
				return 'IL';
			case self::BELARUS:
				return 'BLR';
			case self::KAZAKHSTAN:
				return 'KZ';
			case self::ARMENIA:
				return 'AM';
			case self::UZBEKISTAN:
				return 'UZ';
			case self::CHILE:
				return 'CL';
			case self::SOUTHAFRICA:
				return 'ZA';
			case self::ZAMBIA:
				return 'ZM';
			case self::COTEDIVOIRE:
				return 'CI';
			default:
				return 'RU';
		}
	}

	public static function getPhoneCountry(): string {
		$country = self::getCountry();

		switch ( $country ) {
			case self::ISRAEL:
				return 'il';
			case self::BELARUS:
				return 'blr';
			case self::KAZAKHSTAN:
				return 'kz';
			case self::ARMENIA:
				return 'am';
			case self::UZBEKISTAN:
				return 'uz';
			case self::CHILE:
				return 'cl';
			case self::SOUTHAFRICA:
				return 'za';
			case self::ZAMBIA:
				return 'zm';
			case self::COTEDIVOIRE:
				return 'ci';
			default:
				return 'ru';
		}
	}

	public static function getDefaultTariffsPoint(): array {
		$country = self::getCountry();

		switch ( $country ) {
			case self::ISRAEL:
				return [ 32.085443, 34.782175 ];
			case self::BELARUS:
				return [ 53.893386, 27.556720 ];
			case self::KAZAKHSTAN:
				return [ 51.120020, 71.439383 ];
			case self::UZBEKISTAN:
				return [ 41.312143, 69.253261 ];
			case self::ARMENIA:
				return [ 40.185004, 44.514843 ];
			case self::CHILE:
				return [ - 33.451882, - 70.647616 ];
			case self::ZAMBIA:
				return [ - 15.419916, 28.314981 ];
			case self::COTEDIVOIRE:
				return [ 5.338653, - 4.018761 ];
			default:
				return [ 55.734148, 37.5865588 ]; // Yandex Russian Office
		}
	}

	public static function getCurrency(): string {
		$country = self::getCountry();

		switch ( $country ) {
			case self::ISRAEL:
				return 'ILS';
			case self::BELARUS:
				return 'BYN';
			case self::KAZAKHSTAN:
				return 'KZT';
			case self::UZBEKISTAN:
				return 'UZS';
			case self::ARMENIA:
				return 'AMD';
			case self::CHILE:
				return 'CLP';
			case self::SOUTHAFRICA:
				return 'ZAR';
			case self::ZAMBIA:
				return 'ZMW';
			case self::COTEDIVOIRE:
				return 'CFA';
			default:
				return 'RUB';
		}
	}

	public static function getCountry(): string {
		$settings = get_option( YGO_PLUGIN_SETTINGS );

		return $settings['country'] ?? self::RUSSIA;
	}

	public static function getRefferalSource() {
		$plugin_version = WC_Yandex_Taxi_Delivery_Plugin_Version::get();
		$source         = "CMS_WordPress_CreativeMotion_";

		switch ( self::getCountry() ) {
			case self::RUSSIA:
				$source .= "RU";
			case self::BELARUS:
				$source .= "BY";
			case self::KAZAKHSTAN:
				$source .= "KZ";
			case self::UZBEKISTAN:
				$source .= "UZ";
			case self::ARMENIA:
				$source .= "AM";
			case self::ZAMBIA:
				$source .= "ZMB";
			case self::COTEDIVOIRE:
				$source .= "CIV";
			default:
				$source = "Wordpress plugin: " . YGO_PLUGIN_BRAND . " | WooCommerce - {$plugin_version}";
		}


		return $source;
	}
}
