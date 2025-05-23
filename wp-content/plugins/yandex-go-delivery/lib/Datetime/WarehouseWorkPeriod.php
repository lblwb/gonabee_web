<?php

namespace WCYandexTaxiDeliveryPlugin\Datetime;

defined('ABSPATH') || exit;

use Datetime;
use DateTimeZone;

/**
 * Class WarehouseWorkPeriod
 *
 * @package WCYandexTaxiDeliveryPlugin\Datetime
 */
class WarehouseWorkPeriod
{
    /** @var Datetime */
    private $start;

    /** @var Datetime */
    private $end;

    /** @var DateTimeZone */
    private $timezone;

    public function __construct(string $start, string $end)
    {
        $this->timezone = wp_timezone();
        $startTime = new DateTime($start, $this->timezone);
        $endTime = new DateTime($end, $this->timezone);

        if ($endTime <= $startTime) {
            $endTime->modify('+1 day'); // if end time is smaller (or equals) then start, it mead warehouse closes next day
        }

        $this->start = $startTime;
        $this->end = $endTime;
    }

    public function getStart(): Datetime
    {
        return $this->start;
    }

    public function getEnd(): Datetime
    {
        return $this->end;
    }

    public function isOpen(): bool
    {
        $currentTime = new DateTime( 'now', $this->timezone);

        return $currentTime >= $this->start && $currentTime <= $this->end;
    }
}
