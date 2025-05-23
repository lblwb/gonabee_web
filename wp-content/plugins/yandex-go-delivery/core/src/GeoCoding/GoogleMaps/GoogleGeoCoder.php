<?php

namespace YandexTaxi\Delivery\GeoCoding\GoogleMaps;

defined( 'YGO_CALLED_FROM_PLUGIN' ) || exit;

use YandexTaxi\Delivery\GeoCoding\Exceptions\GeoCodingException;
use YandexTaxi\Delivery\GeoCoding\GeoCoderInterface;
use YandexTaxi\Delivery\GeoCoding\Point;
use YandexTaxi\Delivery\Http\Client as HttpClient;

/**
 * Class GoogleGeoCoder
 *
 * @package YandexTaxi\Delivery\GeoCoding\GoogleMaps
 */
final class GoogleGeoCoder implements GeoCoderInterface {
	private const BASE_URL = 'https://maps.googleapis.com/maps/api/geocode';

	/** @var string */
	private $token;

	/** @var HttpClient */
	private $httpClient;

	public function __construct( HttpClient $httpClient, string $token ) {
		$this->token      = $token;
		$this->httpClient = $httpClient;
	}

	/**
	 * @param string $address
	 *
	 * @return Point
	 * @throws GeoCodingException
	 */
	public function decode( string $address ): Point {
		$result = $this->call( [ 'address' => $address ] );

		if ( ! isset( $result['results'][0]['geometry']['location'] ) ) {
			throw new GeoCodingException( 'Не удалось расшифровать адрес' );
		}

		$lat = $result['results'][0]['geometry']['location']['lat'] ?? '';
		$lng = $result['results'][0]['geometry']['location']['lng'] ?? '';

		return new Point( $lat, $lng );
	}

	/**
	 * @param array $options
	 *
	 * @return mixed
	 * @throws GeoCodingException
	 */
	public function call( array $options ) {
		$response = $this->httpClient->sendGet( $this->buildUrl( $options ), [] );

		if ( $response->getCode() !== 200 ) {
			throw new GeoCodingException( $response->getContent() );
		}

		return json_decode( $response->getContent(), true );
	}

	private function buildUrl( array $query = [] ): string {
		$query = http_build_query( array_merge( $query, [
			'key' => $this->token,
		] ) );

		return self::BASE_URL . "/json?{$query}";
	}
}
