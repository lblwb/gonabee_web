<?php
namespace WCYandexTaxiDeliveryPlugin\Datetime;

defined('ABSPATH') || exit;

use DateTime;
use WCYandexTaxiDeliveryPlugin\Constants;
use WCYandexTaxiDeliveryPlugin\DefaultWarehouseFinder;
use WCYandexTaxiDeliveryPlugin\Entities\Warehouse;
use RuntimeException;

/**
 * Class SlotsFinder
 *
 * @package WCYandexTaxiDeliveryPlugin\Datetime
 */
class SlotsFinder
{
    /** @var int */
    private $delay;

    /** @var Warehouse */
    private $warehouse;

    public function __construct()
    {
        $settings = get_option(Constants::SETTINGS_GROUP);
        $this->delay = intval($settings['assembly_delay_minutes']);
        $warehouse = DefaultWarehouseFinder::find();

        if (empty($warehouse)) {
            throw new RuntimeException('Default warehouse is not found');
        }

        $this->warehouse = $warehouse;
    }

    /**
     * @return int
     */
    public function getDelay(): int
    {
        return $this->delay;
    }

    public function findCurrent(): array
    {
        return array_merge($this->getDefaultSlots(), $this->getSlots(new DateTime('now', wp_timezone())));
    }

    public function findAll(): array
    {
        return array_merge($this->getDefaultSlots(), $this->getSlots());
    }

    private function getDefaultSlots(): array
    {
        return [
            'default' => 'Ближайшее время',
        ];
    }

    private function getSlots(?Datetime $from = null): array
    {
        $period = (new WarehouseWorkPeriodFinder())->find($this->warehouse);

        if (empty($period)) {
            return [];
        }

        $slots = [];

        if (!is_null($from) && $from > $period->getStart()) {
            $cursorTime = clone $from;
        } else {
            $cursorTime = clone $period->getStart();
        }

        $cursorTime->modify("+{$this->delay} minute");
        $cursorTime = $this->roundToNextHour($cursorTime);

        while ($cursorTime < $period->getEnd()) {
            $line = $cursorTime->format('H:00');

            $startHour = $cursorTime->format('H:i');
            $cursorTime->modify('+1 hour');
            $endHour = $cursorTime->format('H:i');

            $slots[$line] = "{$startHour} - {$endHour}";
        }

        return $slots;
    }

    private function roundToNextHour(Datetime $date): Datetime
    {
        $minutes = $date->format('i');

        if ($minutes > 0) {
            $date->modify("+1 hour");
            $date->modify('-' . $minutes . ' minutes');
        }

        return $date;
    }
}
