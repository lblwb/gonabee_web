<?php
/**
 * Plugin Name: CDEKDelivery
 * Plugin URI: https://www.cdek.ru/ru/integration/modules/33
 * Description: CDEK delivery integration for WooCommerce
 * Version: 4.1.12
 * Requires at least: 6.0
 * Text Domain: cdekdelivery
 * Domain Path: /lang
 * Requires PHP: 7.4
 * Requires Plugins: woocommerce
 * Author: CDEKIT
 * Author URI: https://cdek.ru
 * WC requires at least: 6.9
 * WC tested up to: 9.7
 * License: GPLv3
 */

defined('ABSPATH') or exit;

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
}

if (!class_exists(\Cdek\Loader::class)) {
    trigger_error('CDEKDelivery not fully installed! Please install with Composer or download full release archive.',
                  E_USER_ERROR);
}

\Cdek\Loader::new()(__FILE__);
