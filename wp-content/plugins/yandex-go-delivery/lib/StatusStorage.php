<?php

namespace WCYandexTaxiDeliveryPlugin;

defined('ABSPATH') || exit;

use wpdb;

/**
 * Class StatusStorage
 *
 * @package WCYandexTaxiDeliveryPlugin
 */
class StatusStorage
{
    /** @var wpdb */
    private $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;

        $this->createTablesIfNotExists();
    }

    public function storeShipmentStatus(string $claimId, string $status): void
    {
        $currentStatus = $this->getShipmentStatus($claimId);

        if (is_null($currentStatus)) {
            $this->wpdb->query($this->wpdb->prepare(
                "INSERT INTO `{$this->getShipmentTableName()}` (`claim_id`, `status`) VALUES (%s, %s)",
                $claimId, $status
            ));
            return;
        }

        $this->wpdb->query($this->wpdb->prepare(
            "UPDATE `{$this->getShipmentTableName()}` SET `status` = %s WHERE `claim_id` = %s",
            $status,
            $claimId
        ));
    }

    public function getShipmentStatus(string $claimId): ?string
    {
        $row = $this->wpdb->get_row(
	        $this->wpdb->prepare(
				"SELECT * FROM `{$this->getShipmentTableName()}` WHERE `claim_id`= %s",
				$claimId
	        ));

        if (empty($row)) {
            return null;
        }

        return $row->status;
    }

    public function storeOrderStatus(int $orderId, string $claimId, string $visitStatus): void
    {
        $currentStatus = $this->getOrderStatus($orderId, $claimId);

        if (is_null($currentStatus)) {
            $this->wpdb->query($this->wpdb->prepare(
                "INSERT INTO `{$this->getShipmentOrderTableName()}` (`claim_id`, `order_id`, `visit_status`) VALUES (%s, %s, %s)",
                $claimId, $orderId, $visitStatus
            ));

            return;
        }
        $this->wpdb->query($this->wpdb->prepare(
            "UPDATE `{$this->getShipmentOrderTableName()}` SET `visit_status` = %s 
                WHERE `claim_id` = %s AND `order_id` = %s",
            $visitStatus,
            $orderId,
            $claimId
        ));
    }

    public function getOrderStatus(int $orderId, string $claimId): ?string
    {
        $row = $this->wpdb->get_row(
	        $this->wpdb->prepare(
				"SELECT * FROM `{$this->getShipmentOrderTableName()}` WHERE `claim_id` = %s AND `order_id` = %d",
				$claimId, $orderId
	        ));
        if (empty($row)) {
            return null;
        }

        return $row->visit_status;
    }

    private function createTablesIfNotExists(): void
    {
        $this->wpdb->query(
            "
                CREATE TABLE IF NOT EXISTS `{$this->getShipmentTableName()}` (
                    `claim_id` VARCHAR(255) NOT NULL UNIQUE,
                    `status` VARCHAR(255) DEFAULT NULL,
                    PRIMARY KEY (`claim_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
            "
        );

        $this->wpdb->query(
            "
                CREATE TABLE IF NOT EXISTS `{$this->getShipmentOrderTableName()}` (
                    `claim_id` VARCHAR(255) NOT NULL,
                    `order_id` INT(11) NOT NULL,
                    `visit_status` VARCHAR(255) DEFAULT NULL,
                    PRIMARY KEY (`claim_id`, `order_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
            "
        );
    }

    private function getShipmentTableName(): string
    {
        return YGO_PLUGIN_ID . '_shipment';
    }

    private function getShipmentOrderTableName(): string
    {
        return YGO_PLUGIN_ID . '_shipment_order';
    }
}
