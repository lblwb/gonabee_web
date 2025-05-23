<?php

namespace WCYandexTaxiDeliveryPlugin;

defined('ABSPATH') || exit;

use YandexTaxi\Delivery\Entities\RoutePoint\RoutePointVisitStatus;
use WC_Order;

/**
 * Class OrderAutoStatusChanger
 *
 * @package WCYandexTaxiDeliveryPlugin
 */
class OrderAutoStatusChanger
{
    private const SETTING_NAME = 'auto_change_status';

    private const STATUS_CODE = 'completed';

    public function changeIfNeeded(WC_Order $order, ?RoutePointVisitStatus $oldStatus, RoutePointVisitStatus $newStatus): void
    {
        if (!$this->isNeeded($oldStatus, $newStatus)) {
            return;
        }

        $order->update_status(self::STATUS_CODE);
    }

    private function isNeeded(?RoutePointVisitStatus $oldStatus, RoutePointVisitStatus $newStatus): bool
    {
        if (!$this->isTurnedOn()) {
            return false;
        }

        if (!is_null($oldStatus) && $oldStatus->equals(RoutePointVisitStatus::visited())) {
            return false;
        }

        return $newStatus->equals(RoutePointVisitStatus::visited());
    }

    private function isTurnedOn(): bool
    {
        $settings = get_option(YGO_PLUGIN_SETTINGS);
        return $settings[self::SETTING_NAME] === 'yes';
    }
}
