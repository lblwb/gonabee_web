<?php

defined('ABSPATH') || exit;

use WCYandexTaxiDeliveryPlugin\Repositories\WarehouseRepository;
use WCYandexTaxiDeliveryPlugin\Entities\Warehouse;
use WCYandexTaxiDeliveryPlugin\Constants;
use WCYandexTaxiDeliveryPlugin\Helpers\AdminUrlHelper;
use WCYandexTaxiDeliveryPlugin\AvailableTariffChecker;
use YandexTaxi\Delivery\YandexApi\Exceptions\YandexApiException;
use YandexTaxi\Delivery\YandexApi\Exceptions\NotAuthorizedException;

/**
 * Class WC_Yandex_Taxi_Delivery_Warehouse_Controller
 */
class WC_Yandex_Taxi_Delivery_Warehouse_Controller extends WC_Yandex_Taxi_Delivery_Base_Controller
{
    private const DEFAULT_WAREHOUSE_ID_SETTING_NAME = 'default_werehouse_id';

    public static function index()
    {
        self::renderView('warehouses/index', [
            'warehouses' => (new WarehouseRepository())->all(),
            'defaultWarehouseId' => self::get_default_warehouse_id(),
        ]);
    }

    public static function edit()
    {
        $repository = new WarehouseRepository();

        $settings = get_option(YGO_PLUGIN_SETTINGS);

        $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : null;

        if (is_null($id)) {
            $warehouse = new Warehouse();
        } else {
            $warehouse = $repository->get((int)$id);

            if (empty($warehouse)) {
                echo 'Warehouse not found';

                return;
            }
        }

        $hours = self::get_hours_range();
        $message = null;

        if (isset($_REQUEST['submit'])) {
            $warehouse->setAddress(sanitize_text_field($_REQUEST['address']));
            [$lat, $lon] = explode(',', sanitize_text_field($_REQUEST['coordinate']));
            $warehouse->setLat($lat);
            $warehouse->setLon($lon);

            $warehouse->setContactName(sanitize_text_field($_REQUEST['name']));
            $warehouse->setContactPhone(sanitize_text_field($_REQUEST['phone']));
            $warehouse->setContactEmail(sanitize_email($_REQUEST['email']));

            $warehouse->setComment(sanitize_textarea_field($_REQUEST['comment']));
            $warehouse->setFlat((int)$_REQUEST['flat']);
            $warehouse->setPorch(sanitize_text_field($_REQUEST['porch']));
            $warehouse->setFloor((int)$_REQUEST['floor']);

            $startTime = sanitize_text_field($_REQUEST['start_time']);
            $endTime = sanitize_text_field($_REQUEST['end_time']);

            if (!isset($hours[$startTime])) {
                $startTime = array_shift($hours);
            }

            if (!isset($hours[$endTime])) {
                $endTime = array_shift($hours);
            }

            $warehouse->setStartTime($startTime);
            $warehouse->setEndTime($endTime);

            $repository->store($warehouse);

            if (isset($_REQUEST['is_default']) && '1' === $_REQUEST['is_default']) {
                self::mark_warehouse_default($warehouse);
            }

            self::mark_warehouse_default_if_it_only($warehouse);

            $message = self::get_tariffs_message($lat, $lon);

            if (is_null($message)) {
                if (wp_safe_redirect(AdminUrlHelper::getWarehouseIndexUrl())) {
                    exit;
                }
            }
        }

        self::renderView('warehouses/edit', [
            'warehouse' => $warehouse,
            'geocodeToken' => $settings['geocode_token'],
            'hours' => $hours,
            'isDefault' => self::get_default_warehouse_id() === $warehouse->getId(),
            'message' => $message,
        ]);
    }

    public static function delete()
    {
        $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : null;

        if (empty($id)) {
            return;
        }

        $id = (int)$id;

        $repository = new WarehouseRepository();
        $repository->delete($id);

        // mark default - last warehouse
        $count = (new WarehouseRepository())->count();

        if (1 === $count) {
            self::mark_warehouse_default($repository->all()[0]);
        }

        if ((self::get_default_warehouse_id() === $id) && ($count > 0)) {
            self::mark_warehouse_default($repository->all()[0]);
        }
    }

    private static function get_tariffs_message(float $lat, float $lon): ?string
    {
        if (!self::has_token()) {
            return null;
        }

        try {
            if (AvailableTariffChecker::isAvailable($lat, $lon)) {
                return null;
            }

            return self::getView('partial/_error', ['error' => self::getView('partial/_no_tariffs')]);
        } catch (NotAuthorizedException $exception) {
            return self::getView('partial/_error', ['error' => self::getView('partial/_bad_token')]);
        } catch (YandexApiException $exception) {
            return self::getView('partial/_error', ['error' => $exception->getMessage()]);
        }
    }

    private static function mark_warehouse_default_if_it_only(Warehouse $warehouse): void
    {
        $count = (new WarehouseRepository())->count();
        if ($count === 1) {
            self::mark_warehouse_default($warehouse);
        }
    }

    private static function mark_warehouse_default(Warehouse $warehouse): void
    {
        $settings = self::get_settings();
        $settings['default_werehouse_id'] = $warehouse->getId();
        update_option(YGO_PLUGIN_SETTINGS, $settings);
    }

    private static function get_hours_range($start = 0, $end = 86400, $step = 3600)
    {
        $times = [];
        foreach (range($start, $end, $step) as $timestamp) {
            $hourMins = gmdate('H:i', $timestamp);
            $times[$hourMins] = gmdate('H:i', $timestamp);
        }

        return $times;
    }

    private static function get_default_warehouse_id(): ?int
    {
        $settings = self::get_settings();
        $id = $settings[self::DEFAULT_WAREHOUSE_ID_SETTING_NAME] ?? null;

        if (is_null($id)) {
            return $id;
        }

        return (int)$id;
    }

    private static function has_token(): bool
    {
        $settings = self::get_settings();

        return !empty($settings['token']);
    }

    private static function get_settings(): array
    {
        return WC_Yandex_Taxi_Delivery_Setting_Controller::get_settings();
    }
}
