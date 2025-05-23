<?php

namespace WCYandexTaxiDeliveryPlugin\PluginActivationServices;

defined('ABSPATH') || exit;

use libphonenumber\NumberParseException;
use WCYandexTaxiDeliveryPlugin\Constants;
use WCYandexTaxiDeliveryPlugin\Repositories\WarehouseRepository;
use WCYandexTaxiDeliveryPlugin\Entities\Warehouse;

/**
 * Class ExistingWarehouseSyncer
 *
 * @package WCYandexTaxiDeliveryPlugin\PluginActivationServices
 */
class ExistingWarehouseSyncer
{
    private const WERE_SYNCED_SETTING_NAME = 'was_warehouse_synced';

    public function syncIfNeeded(): void
    {
        if (!$this->needSync()) {
            return;
        }

        $this->doSync();

        $this->markWasSynced();
    }

    private function doSync(): void
    {
        $repository = new WarehouseRepository();

        if ($repository->count() > 0) {
            return; // warehouses exist, skip syncing
        }

        $settings = get_option(YGO_PLUGIN_SETTINGS);

        $warehouse = new Warehouse();
        $warehouse->setAddress($settings['warehouse_address']);
        list ($lat, $lon) = explode(',', $settings['warehouse_coordinate']);
        $warehouse->setLat($lat);
        $warehouse->setLon($lon);

        $warehouse->setContactName($settings['warehouse_contact_name']);
        try {
            $warehouse->setContactPhone($settings['warehouse_contact_phone']);
        } catch (NumberParseException $exception) {
            // do nothing, create
        }
        $warehouse->setContactEmail($settings['warehouse_email']);
        $warehouse->setStartTime($settings['warehouse_start_time']);
        $warehouse->setEndTime($settings['warehouse_end_time']);

        $repository->store($warehouse);

        // set as default
        $settings['default_werehouse_id'] = $warehouse->getId();
        update_option(YGO_PLUGIN_SETTINGS, $settings);
    }

    private function needSync(): bool
    {
        $settings = get_option(YGO_PLUGIN_SETTINGS);

        if (isset($settings[self::WERE_SYNCED_SETTING_NAME]) && $settings[self::WERE_SYNCED_SETTING_NAME] === 'yes') {
            return false;
        }

        // settings are empty, nothing to sync
        if (!isset($settings['warehouse_address']) || !isset($settings['warehouse_coordinate']) ||
            empty($settings['warehouse_address']) || empty($settings['warehouse_coordinate'])) {
            return false;
        }

        return true;
    }

    private function markWasSynced(): void
    {
        $settings = get_option(YGO_PLUGIN_SETTINGS);
        $settings[self::WERE_SYNCED_SETTING_NAME] = 'yes';
        update_option(YGO_PLUGIN_SETTINGS, $settings);
    }
}
