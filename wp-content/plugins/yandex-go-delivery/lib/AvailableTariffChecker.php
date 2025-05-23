<?php

namespace WCYandexTaxiDeliveryPlugin;

use WCYandexTaxiDeliveryPlugin\Helpers\CountryRelatedDataHelper;
use YandexTaxi\Delivery\YandexApi\Exceptions\YandexApiException;

/**
 * Class AvailableTariffChecker
 *
 * @package WCYandexTaxiDeliveryPlugin
 */
class AvailableTariffChecker {
	/**
	 * @param float|null $lat
	 * @param float|null $lon
	 *
	 * @return bool
	 * @throws YandexApiException
	 */
	public static function isAvailable( float $lat = null, float $lon = null ): bool {
		$service = new AdminPackageService();

		if ( is_null( $lat ) || is_null( $lon ) ) {
			[ $lat, $lon ] = CountryRelatedDataHelper::getDefaultTariffsPoint();
		}

		$tariffs = $service->getTariffs( $lat, $lon );

		return ! empty( $tariffs );
	}
}
