<?php

namespace YandexTaxi\Delivery\GeoCoding\Dadata;

defined( 'YGO_CALLED_FROM_PLUGIN' ) || exit;

use YandexTaxi\Delivery\GeoCoding\Exceptions\GeoCodingException;
use YandexTaxi\Delivery\GeoCoding\GeoCoderInterface;
use YandexTaxi\Delivery\GeoCoding\Point;
use YandexTaxi\Delivery\Http\Client as HttpClient;

/**
 * Class DadataGeoCoder
 *
 * @package YandexTaxi\Delivery\GeoCoding\Dadata
 */
final class DadataGeoCoder implements GeoCoderInterface {
	private const BASE_URL = 'https://cleaner.dadata.ru/api/v1/clean/address';

	/** @var string */
	private $token;

	/** @var string */
	private $secret;

	/** @var HttpClient */
	private $httpClient;

	public function __construct( HttpClient $httpClient, string $token ) {
		$token = explode('::', $token);
		$this->token      = $token[0] ?? '';
		$this->secret     = $token[1] ?? '';
		$this->httpClient = $httpClient;
	}

	/**
	 * @param string $address
	 *
	 * @return Point
	 * @throws GeoCodingException
	 */
	public function decode( string $address ): Point {
		$result = $this->call( [ $address ] );

		if ( ! isset( $result[0] ) ) {
			throw new GeoCodingException( 'Не удалось расшифровать адрес' );
		}

		$lat = $result[0]['geo_lat'] ?? '';
		$lng = $result[0]['geo_lon'] ?? '';

		return new Point( $lat, $lng );
	}

	/**
	 * @param array $options
	 *
	 * @return mixed
	 * @throws GeoCodingException
	 */
	public function call( array $options ) {
		$response = $this->httpClient->sendPost( self::BASE_URL, $options, [
			'Content-Type'  => 'application/json',
			'Authorization' => "Token {$this->token}",
			'X-Secret'      => $this->secret,
		] );

		if ( $response->getCode() !== 200 ) {
			throw new GeoCodingException( $response->getContent() );
		}

		return json_decode( $response->getContent(), true );
	}
}
