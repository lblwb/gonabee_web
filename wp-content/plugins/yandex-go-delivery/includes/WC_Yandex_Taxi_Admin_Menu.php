<?php

defined('ABSPATH') || exit;

use WCYandexTaxiDeliveryPlugin\Constants;

/**
 * Class WC_Yandex_Taxi_Admin_Menu
 */
class WC_Yandex_Taxi_Admin_Menu
{
    private const CAPABILITY = 'manage_woocommerce';

    public function init()
    {
        add_action('admin_menu', [$this, 'register_pages']);
    }

    public function register_pages()
    {
        $this->register_menu();
        $this->register_hidden_pages();
    }

    private function register_menu()
    {
        /*add_menu_page(
            Constants::getPluginName(),
            Constants::getPluginName(),
            self::CAPABILITY,
            YGO_PLUGIN_ID,
            [WC_Yandex_Taxi_Delivery_Setting_Controller::class, 'index'],
            $this->getIconFilePath(),
            '30' // position in menu
        );*/
        add_submenu_page(
            'woocommerce',
            __('Настройки', 'yandex-go-delivery'),
	        __('Яндекс Доставка', 'yandex-go-delivery'),
            static::CAPABILITY,
            YGO_PLUGIN_ID . '_settings',
            [WC_Yandex_Taxi_Delivery_Setting_Controller::class, 'index']
        );
        add_submenu_page(
	        YGO_PLUGIN_ID . '_hidden',
            __('Склады', 'yandex-go-delivery'),
	        __('Склады', 'yandex-go-delivery'),
            static::CAPABILITY,
            YGO_PLUGIN_ID . '_warehouses',
            [WC_Yandex_Taxi_Delivery_Warehouse_Controller::class, 'index']
        );
    }

    private function register_hidden_pages()
    {
        add_submenu_page(
            YGO_PLUGIN_ID . '_hidden',
            __('Отправка в ', 'yandex-go-delivery') . Constants::getToPluginName(),
            __('Отправка в ', 'yandex-go-delivery') . Constants::getToPluginName(),
            self::CAPABILITY,
            YGO_PLUGIN_ID . '_create_claim',
            [WC_Yandex_Taxi_Delivery_Claim_Controller::class, 'create']
        );

        add_submenu_page(
            YGO_PLUGIN_ID . '_hidden',
            __('Подтвердить отправку в ', 'yandex-go-delivery') . Constants::getToPluginName(),
            __('Подтвердить отправку в ', 'yandex-go-delivery') . Constants::getToPluginName(),
            self::CAPABILITY,
            YGO_PLUGIN_ID . '_ask_claim_creation',
            [WC_Yandex_Taxi_Delivery_Claim_Controller::class, 'ask_creation']
        );

        add_submenu_page(
            YGO_PLUGIN_ID . '_hidden',
            __('Отмена заказа ', 'yandex-go-delivery') . Constants::getPluginName(),
            __('Отмена заказа ', 'yandex-go-delivery') . Constants::getToPluginName(),
            self::CAPABILITY,
            YGO_PLUGIN_ID . '_cancel',
            [WC_Yandex_Taxi_Delivery_Claim_Controller::class, 'cancel']
        );

        add_submenu_page(
            YGO_PLUGIN_ID . '_hidden',
            'Редактирование склада',
            'Редактирование склада',
            static::CAPABILITY,
            YGO_PLUGIN_ID . '_warehouses_edit',
            [WC_Yandex_Taxi_Delivery_Warehouse_Controller::class, 'edit']
        );

        add_submenu_page(
            YGO_PLUGIN_ID . '_hidden',
            'Создание кабинета',
            'Создание кабинета',
            static::CAPABILITY,
            YGO_PLUGIN_ID . '_cabinet_modal'
        );
    }

    public static function getIconFilePath(): string
    {
        //$locale = is_admin() ? get_user_locale() : get_locale();
        //$filename = ($locale === 'he_IL') ? 'favicon_he_IL.png' : 'yandex-logo.svg';
	    $filename = 'yandex-logo.svg';

        return plugins_url("/../assets/{$filename}", __FILE__);
    }
}
