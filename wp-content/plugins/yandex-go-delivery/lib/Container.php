<?php

namespace WCYandexTaxiDeliveryPlugin;

defined( 'ABSPATH' ) || exit;

use Exception;

/**
 * Class Container
 *
 * @package WCYandexTaxiDeliveryPlugin
 */
final class Container {
	protected static $instances = [];

	public static function set( string $className, callable $function ) {
		if ( ! is_callable( $function ) ) {
			return;
		}

		self::$instances[ $className ] = $function;
	}

	/**
	 * @param string $className
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public static function get( string $className ) {
		if ( ! self::has( $className ) ) {
			throw new Exception( "{$className} is not registered" );
		}

		$dependency = self::$instances[ $className ];

		if ( is_callable( $dependency ) ) {
			$dependency = call_user_func( $dependency );
		}

		if ( ! $dependency instanceof $className ) {
			throw new Exception( "Dependency has bad class, should be {$className}" );
		}

		self::$instances[ $className ] = $dependency;

		return self::$instances[ $className ];
	}

	public static function has( string $className ) {
		return isset( self::$instances[ $className ] );
	}
}
