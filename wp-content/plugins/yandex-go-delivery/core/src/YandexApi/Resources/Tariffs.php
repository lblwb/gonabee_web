<?php

namespace YandexTaxi\Delivery\YandexApi\Resources;

defined( 'YGO_CALLED_FROM_PLUGIN' ) || exit;

use YandexTaxi\Delivery\Entities\Claim\Tariff;
use YandexTaxi\Delivery\Entities\Claim\TariffRequirementOption;
use YandexTaxi\Delivery\Entities\Claim\TariffRequirement;
use YandexTaxi\Delivery\YandexApi\Client;
use YandexTaxi\Delivery\YandexApi\Exceptions\YandexApiException;

/**
 * Class Tariffs
 *
 * @package YandexTaxi\Delivery\YandexApi\Resources
 */
class Tariffs extends Resource {
	protected function getBasePath(): string {
		return 'cargo/integration';
	}

	/**
	 * @param float $lat
	 * @param float $lon
	 *
	 * @return Tariff[]
	 * @throws YandexApiException
	 */
	public function getAllForPoint( float $lat, float $lon ): array {
		$raw = $this->call( 'tariffs', Client::API_V2, [
			'json' => [
				'start_point' => [ $lon, $lat ],
			],
		] );

		return array_map( function ( array $tariff ) {
			return $this->prepareTariff( $tariff );
		}, $raw['available_tariffs'] );
	}

	private function prepareTariff( array $raw ): Tariff {
		$requirements = [];
		$settings     = ygo_get_settings();
		foreach ( $raw['supported_requirements'] as $requirement ) {
			$options = [];
			foreach ( $requirement['options'] as $option ) {
				$options[] = new TariffRequirementOption( $option['title'], $option['text'], $option['value'] );
			}

			$requirements[] = new TariffRequirement( $requirement['name'], $requirement['title'], $requirement['text'], $requirement['type'], (bool) $requirement['required'], $options );
		}
		if ( isset( $settings['country'] ) && 'Belarus' === $settings['country'] ) {
			$title = __( 'Срочная доставка', 'yandex-go-delivery' );
		} else {
			$title = $raw['title'];
		}

		return new Tariff( $raw['name'], $title, $raw['text'], $requirements );
	}
}
