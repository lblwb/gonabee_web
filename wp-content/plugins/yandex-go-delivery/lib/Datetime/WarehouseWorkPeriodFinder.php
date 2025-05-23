<?php

namespace WCYandexTaxiDeliveryPlugin\Datetime;

defined('ABSPATH') || exit;

use WCYandexTaxiDeliveryPlugin\Entities\Warehouse;

/**
 * Class WarehouseWorkPeriodFinder
 *
 * @package WCYandexTaxiDeliveryPlugin\Datetime
 */
class WarehouseWorkPeriodFinder
{
    public function find(Warehouse $warehouse): ?WarehouseWorkPeriod
    {
        $start = $warehouse->getStartTime();
        $end = $warehouse->getEndTime();

        if (empty($start || $end)) {
            return null;
        }

        return new WarehouseWorkPeriod($start, $end);
    }

    public function isOpen(Warehouse $warehouse): bool
    {
        $period = $this->find($warehouse);

        return is_null($period) ? false : $period->isOpen();
    }
}
