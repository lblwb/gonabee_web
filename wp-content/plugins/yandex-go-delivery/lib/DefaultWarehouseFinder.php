<?php

namespace WCYandexTaxiDeliveryPlugin;

defined('ABSPATH') || exit;

use WCYandexTaxiDeliveryPlugin\Entities\Warehouse;
use WCYandexTaxiDeliveryPlugin\Repositories\WarehouseRepository;

/**
 * Class DefaultWarehouseFinder
 *
 * @package WCYandexTaxiDeliveryPlugin
 */
class DefaultWarehouseFinder
{
    /** @var Warehouse|null */
    protected static $warehouse;

    public static function find(): ?Warehouse
    {
        if (!empty(self::$warehouse)) {
            return self::$warehouse;
        }

        self::$warehouse = self::findWarehouse();

        return self::$warehouse;
    }

    private static function findWarehouse(): ?Warehouse
    {
        $repository = new WarehouseRepository();
        $settings = get_option(YGO_PLUGIN_SETTINGS);

        $defaultWarehouseId = $settings['default_werehouse_id'] ?? null;

        $warehouse = null;

        if (!empty($defaultWarehouseId)) {
            $warehouse = $repository->get($defaultWarehouseId);
        }

        if (!is_null($warehouse)) {
            return $warehouse;
        }

        $warehouses = $repository->all();

        if (empty($warehouses)) {
            return new Warehouse();
        }

        return array_shift($warehouses);
    }
}
