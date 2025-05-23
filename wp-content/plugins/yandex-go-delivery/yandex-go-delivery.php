<?php
/**
 * Plugin Name: Yandex Delivery
 * Plugin URI: https://taxi.yandex.ru/action/business/delivery/
 * Description: Модуль Яндекс Доставки позволяет заказать доставку прямо из списка заказов в WooCommerce
 * Version: 1.13
 * Author: Яндекс Доставка
 * Text Domain: yandex-go-delivery
 * Domain Path: /i18n/languages/
 * WC requires at least: 6.0
 * WC tested up to: 8.8
 **/

//if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
	// load translations
	$locale = is_admin() ? get_user_locale() : get_locale();
	$locale = apply_filters( 'plugin_locale', $locale, 'yandex-go-delivery' );

	unload_textdomain( 'yandex-go-delivery' );
	load_textdomain( 'yandex-go-delivery', __DIR__ . '/i18n/languages/yandex-go-delivery-' . $locale . '.mo' );


	define( 'YGO_PLUGIN_ID', 'yandex-go-delivery' );
	define( 'YGO_PLUGIN_BRAND', 'yandex' );
	define( 'YGO_PLUGIN_VERSION', '1.13' );
	define( 'YGO_PLUGIN_SETTINGS', 'woocommerce_' . YGO_PLUGIN_ID . '_settings' );

	define( 'YGO_PLUGIN_DIR', dirname( __FILE__ ) );
	define( 'YGO_PLUGIN_URL', plugins_url( '', __FILE__ ) );
	define( 'YGO_PLUGIN_VIEWS_DIR', dirname( __FILE__ ) . '/includes/views' );

	// configure
	$config = require YGO_PLUGIN_DIR . '/config/config.php';

	define( 'YGO_CALLED_FROM_PLUGIN', 1 ); // define constant that prevents core direct call
	define( 'YGO_USE_TEST_ENV', $config['use_test_env'] ); // define constant to use test env or not

	// Declare the compatibility with WooCommerce plugin HPOS
	add_action('before_woocommerce_init', function(){
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );

		}
	});

	require_once YGO_PLUGIN_DIR . '/includes/WC_Yandex_Taxi_Delivery_App.php';
	require_once YGO_PLUGIN_DIR . '/includes/WC_Yandex_Taxi_Delivery_Activation.php';
	( new WC_Yandex_Taxi_Delivery_App() )->init();

	register_activation_hook( __FILE__, [ WC_Yandex_Taxi_Delivery_Activation::class, 'handleActivation' ] );
	register_deactivation_hook( __FILE__, [ WC_Yandex_Taxi_Delivery_Activation::class, 'handleDeactivation' ] );
} else {
	$ygo_plugin_error_func = function () {
		$message = __( "<b>Yandex Go Delivery</b>: Плагин остановлен, так как не установлен или не активирован плагин Woocommerce", 'yandex-go-delivery' );
		echo '<div class="notice notice-error"><p>' . $message . '</p></div>';
	};

	add_action( 'admin_notices', $ygo_plugin_error_func );
	add_action( 'network_admin_notices', $ygo_plugin_error_func );
}

