<?php

defined( 'ABSPATH' ) || exit;

use WCYandexTaxiDeliveryPlugin\Constants;
use WCYandexTaxiDeliveryPlugin\View\View;
use WCYandexTaxiDeliveryPlugin\Json\JsonResponse;
use YandexTaxi\Delivery\YandexApi\Exceptions\NotAuthorizedException;

/**
 * Class WC_Yandex_Taxi_Delivery_Base_Controller
 */
abstract class WC_Yandex_Taxi_Delivery_Base_Controller {
	protected static function renderException( Exception $exception ) {
		if ( $exception instanceof NotAuthorizedException ) {
			$message = __( 'Ваш Токен API Яндекс Доставки не работает. Возможно, у вас на счете недостаточно средств или не подключена доставка Яндекс Go.', 'yandex-go-delivery' );
		} else {
			$message = $exception->getMessage();
		}

		return self::renderJsonError( $message );
	}

	protected static function renderView( string $view, array $params ) {
		echo self::getView( $view, $params );
	}

	protected static function renderJsonError( string $message ) {
		self::renderJson( [ 'error' => $message ] );

		return null;
	}

	protected static function renderJson( array $data ) {
		echo ( new JsonResponse() )->getString( $data );

		return null;
	}

	protected static function getView( string $view, array $params = [] ): string {
		return ( new View() )->buildHtml( self::getViewPath( $view ), $params );
	}

	private static function getViewPath( string $view ): string {
		return __DIR__ . '/views/' . $view . '.php';
	}

	/**
	 * @param string $current
	 *
	 * @return array
	 */
	public static function admin_tabs( $current = YGO_PLUGIN_ID . '_settings' ) {
		$tabs = [
			'tabs'    => [
				YGO_PLUGIN_ID . '_settings'   => __( 'Настройки', 'yandex-go-delivery' ),
				YGO_PLUGIN_ID . '_warehouses' => __( 'Склады', 'yandex-go-delivery' ),
			],
			'current' => $current,
		];

		return $tabs;

	}
}
