<?php

namespace WCYandexTaxiDeliveryPlugin;

defined('ABSPATH') || exit;

/**
 * Class Constants
 *
 * @package WCYandexTaxiDeliveryPlugin
 */
class Constants
{
    // system values
    public const CLIENT_SESSION_KEY = 'yandex-delivery_shipping_id';

    public static function getPluginName(): string
    {
        return __('Яндекс Доставка', 'yandex-go-delivery');
    }

    public static function getToPluginName(): string
    {
        return __('Яндекс Доставку', 'yandex-go-delivery');
    }

    public static function getDeliveryIdMetaParamName(): string
    {
        return __('Id заказа в Доставке Яндекс Go', 'yandex-go-delivery');
    }

    public static function getDriverMetaParamName(): string
    {
        return __('Исполнитель заказа', 'yandex-go-delivery');
    }

    public static function getDriverPhoneMetaParamName(): string
    {
        return __('Номер телефона исполнителя', 'yandex-go-delivery');
    }

    public static function getShippingSlotMetaParamName(): string
    {
        return __('Желаемое время доставки', 'yandex-go-delivery');
    }

    public static function getShippingStatusMetaParamName(): string
    {
        return __('Статус заказа в Доставке Яндекс Go', 'yandex-go-delivery');
    }

    public static function getShippingRoutePointMetaParamName(): string
    {
        return __('Статус посещения точки в Доставке Яндекс Go', 'yandex-go-delivery');
    }

    public static function getTariffMetaParamName(): string
    {
        return __('Тариф доставки в Доставке Яндекс Go', 'yandex-go-delivery');
    }

    public static function getDeliveryPriceFromCart(): string
    {
        return __('Стоимость Яндекс Доставки в корзине', 'yandex-go-delivery');
    }

    public static function getDeliveryPriceMetaParamName(): string
    {
        return __('Стоимость Яндекс Доставки', 'yandex-go-delivery');
    }
}
