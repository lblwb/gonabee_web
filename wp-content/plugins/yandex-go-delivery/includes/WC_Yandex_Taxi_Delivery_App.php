<?php

defined( 'ABSPATH' ) || exit;

use WCYandexTaxiDeliveryPlugin\Constants;
use WCYandexTaxiDeliveryPlugin\Helpers\CountryRelatedDataHelper;
use YandexTaxi\Delivery\Services\ClaimService;
use WCYandexTaxiDeliveryPlugin\Container;
use YandexTaxi\Delivery\YandexApi\Client;
use YandexTaxi\Delivery\YandexApi\Resources\Claims;
use YandexTaxi\Delivery\GeoCoding\YandexMaps\YandexGeoCoder;
use YandexTaxi\Delivery\GeoCoding\GoogleMaps\GoogleGeoCoder;
use YandexTaxi\Delivery\GeoCoding\Dadata\DadataGeoCoder;
use WCYandexTaxiDeliveryPlugin\Repositories\ClaimLinkRepository;
use YandexTaxi\Delivery\YandexApi\Resources\Tariffs;
use YandexTaxi\Delivery\YandexApi\Resources\PriceChecker;
use WCYandexTaxiDeliveryPlugin\Http\WpHttpClient;
use YandexTaxi\Delivery\Http\Client as HttpClient;

/**
 * Class WC_Yandex_Taxi_Delivery_App
 */
final class WC_Yandex_Taxi_Delivery_App {
	private const CRON_PERIOD_LABEL    = 'Once every 2 minutes';
	private const CRON_PERIOD_INTERVAL = 2 * 60;
	private const CRON_PERIOD_NAME     = '2min';

	/**
	 * @var array
	 */
	public $settings;

	public function init() {
		// PSR-4 autoloader
		spl_autoload_register( function ( $className ) {
			$className       = str_replace( 'YandexTaxi\\Delivery\\', '', $className );
			$destination     = str_replace( '\\', DIRECTORY_SEPARATOR, $className ) . '.php';
			$fileDestination = __DIR__ . '/../core/src/' . $destination;
			if ( is_file( $fileDestination ) ) {
				require $fileDestination;
			}
		} );

		// PSR-4 autoloader
		spl_autoload_register( function ( $className ) {
			$className       = str_replace( 'WCYandexTaxiDeliveryPlugin\\', '', $className );
			$destination     = str_replace( '\\', DIRECTORY_SEPARATOR, $className ) . '.php';
			$fileDestination = __DIR__ . '/../lib/' . $destination;
			if ( is_file( $fileDestination ) ) {
				require $fileDestination;
			}
		} );

		require_once __DIR__ . '/../core/vendor/autoload.php';

		require_once __DIR__ . '/functions.php';

		require_once __DIR__ . '/WC_Yandex_Taxi_Delivery_Event_Hook.php';
		require_once __DIR__ . '/WC_Yandex_Taxi_Delivery_Router.php';
		require_once __DIR__ . '/WC_Yandex_Taxi_Delivery_Base_Controller.php';
		require_once __DIR__ . '/WC_Yandex_Taxi_Delivery_Claim_Controller.php';
		require_once __DIR__ . '/WC_Yandex_Taxi_Delivery_Setting_Controller.php';
		require_once __DIR__ . '/WC_Yandex_Taxi_Delivery_Warehouse_Controller.php';
		require_once __DIR__ . '/WC_Yandex_Taxi_Delivery_Plugin_Version.php';
		require_once __DIR__ . '/WC_Yandex_Taxi_Delivery_Action_Buttons.php';
		require_once __DIR__ . '/WC_Yandex_Taxi_Delivery_Admin_Error.php';
		require_once __DIR__ . '/WC_Yandex_Taxi_Delivery_Order_Meta_Box.php';
		require_once __DIR__ . '/WC_Yandex_Taxi_Admin_Menu.php';

		require_once __DIR__ . '/WC_Yandex_Taxi_Delivery_Payment_Method.php';
		require_once __DIR__ . '/WC_Yandex_Taxi_Delivery_Payment_Method_Card.php';
		require_once __DIR__ . '/WC_Yandex_Taxi_Delivery_Payment_Method_Cash.php';

		$this->settings = ygo_get_settings();

		( new WC_Yandex_Taxi_Admin_Menu() )->init();
		( new WC_Yandex_Taxi_Delivery_Router() )->init();
		$this->register_services();

		add_action( 'woocommerce_shipping_init', [ $this, 'init_shipping_method' ] );
		add_filter( 'woocommerce_shipping_methods', [ $this, 'add_shipping_method' ] );

		if ( 'Russia' === $this->settings['country'] ) {
			add_filter( 'woocommerce_payment_gateways', [
				WC_Yandex_Taxi_Delivery_Payment_Method_Card::class,
				'add_gateway_class',
			] );
			add_filter( 'woocommerce_payment_gateways', [
				WC_Yandex_Taxi_Delivery_Payment_Method_Cash::class,
				'add_gateway_class',
			] );
		}

		add_filter( 'woocommerce_order_data_store_cpt_get_orders_query', [
			$this,
			'handle_delivery_id_query_var',
		], 10, 2 );
		add_action( 'get_new_events_hook', [ WC_Yandex_Taxi_Delivery_Event_Hook::class, 'handle' ] );

		add_filter( 'woocommerce_admin_order_actions', [
			WC_Yandex_Taxi_Delivery_Action_Buttons::class,
			'add_order_actions_button',
		], 100, 2 );

		// load styles
		add_action( 'admin_head', [ $this, 'register_assets' ] );

		// add cron task
		add_filter( 'cron_schedules', [ $this, 'delivery_schedules' ] );

		// add order metabox
		add_action( 'add_meta_boxes', [ $this, 'add_custom_box' ] );
	}

	public function add_custom_box() {
		add_meta_box( YGO_PLUGIN_ID . 'meta_box', Constants::getPluginName(), [
			WC_Yandex_Taxi_Delivery_Order_Meta_Box::class,
			'render',
		], [ 'shop_order' ] );
	}

	public static function delivery_schedules( $schedules ) {
		if ( ! isset( $schedules[ self::CRON_PERIOD_NAME ] ) ) {
			$schedules[ self::CRON_PERIOD_NAME ] = [
				'interval' => self::CRON_PERIOD_INTERVAL,
				'display'  => __( self::CRON_PERIOD_LABEL ),
			];
		}

		return $schedules;
	}

	public static function register_assets() {
		wp_enqueue_style( YGO_PLUGIN_ID . '-admin-css', plugins_url( '/../assets/css/admin-panel.css', __FILE__ ), [], WC_Yandex_Taxi_Delivery_Plugin_Version::get() );
		wp_enqueue_script( YGO_PLUGIN_ID . '-admin-js', plugins_url( '/../assets/js/admin-panel.js', __FILE__ ), [], WC_Yandex_Taxi_Delivery_Plugin_Version::get(), true );

		wp_add_inline_script( YGO_PLUGIN_ID . '-admin-js', '
            yandexTaxiDeliverySendOrdersBaseUrl = "' . \WCYandexTaxiDeliveryPlugin\Helpers\AdminUrlHelper::getBaseAskClaimCreationUrl() . '";
        ' );
	}

	/**
	 * Handle a custom 'delivery_id' query var to get orders with the 'delivery_id' meta.
	 *
	 * @param array $query - Args for WP_Query.
	 * @param array $query_vars - Query vars from WC_Order_Query.
	 *
	 * @return array modified $query
	 */
	public function handle_delivery_id_query_var( $query, $query_vars ) {
		if ( ! empty( $query_vars[ Constants::getDeliveryIdMetaParamName() ] ) ) {
			$query['meta_query'][] = [
				'key'   => Constants::getDeliveryIdMetaParamName(),
				'value' => esc_attr( $query_vars[ Constants::getDeliveryIdMetaParamName() ] ),
			];
		}

		return $query;
	}

	public function add_shipping_method( $methods ) {
		$methods[ YGO_PLUGIN_ID ] = WC_Yandex_Taxi_Delivery_Shipping_Method::class;

		return $methods;
	}

	public function init_shipping_method() {
		require_once __DIR__ . '/WC_Yandex_Taxi_Delivery_Shipping_Method.php';
		require_once __DIR__ . '/WC_Yandex_Taxi_Delivery_Shipping_Controller.php';
		( new WC_Yandex_Taxi_Delivery_Shipping_Controller() )->init();
	}

	public static function register_services(): void {
		$settings = get_option( YGO_PLUGIN_SETTINGS );
		$geocoder = ygo_get_geocoder_classname();

		if ( empty( $settings['token'] ) || ( 'Chile' !== $settings['country'] && empty( $settings['geocode_token'] ) ) ) {
			return;
		}

		Container::set( HttpClient::class, function () {
			return new WpHttpClient();
		} );

		Container::set( Client::class, function () use ( $settings ) {
			$refferal_source = CountryRelatedDataHelper::getRefferalSource();

			return new Client( Container::get( HttpClient::class ), $settings['token'], ! YGO_USE_TEST_ENV, $refferal_source, get_locale() );
		} );

		Container::set( Claims::class, function () {
			return new Claims( Container::get( Client::class ) );
		} );

		Container::set( Tariffs::class, function () {
			return new Tariffs( Container::get( Client::class ) );
		} );

		Container::set( PriceChecker::class, function () {
			return new PriceChecker( Container::get( Client::class ) );
		} );

		Container::set( $geocoder, function () use ( $settings, $geocoder ) {
			return new $geocoder( Container::get( HttpClient::class ), $settings['geocode_token'] );
		} );

		Container::set( ClaimService::class, function () use ( $geocoder ) {
			return new ClaimService( new ClaimLinkRepository(), Container::get( Claims::class ), Container::get( $geocoder ) );
		} );
	}
}
