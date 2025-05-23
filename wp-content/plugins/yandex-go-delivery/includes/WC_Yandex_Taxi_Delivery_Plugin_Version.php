<?php

defined('ABSPATH') || exit;

/**
 * Class WC_Yandex_Taxi_Delivery_Plugin_Version
 */
final class WC_Yandex_Taxi_Delivery_Plugin_Version
{
    /** @var string|null */
    private static $version = null;

    public static function get(): string
    {
        if (static::$version === null) {
            $data = get_file_data(__DIR__ . '/../yandex-go-delivery.php', ['Version' => 'Version']);
            static::$version = $data['Version'];
        }

        return static::$version;
    }
}
