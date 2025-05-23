<?php

namespace WCYandexTaxiDeliveryPlugin\Repositories;

defined('ABSPATH') || exit;

use WCYandexTaxiDeliveryPlugin\Constants;
use WCYandexTaxiDeliveryPlugin\Entities\Warehouse;
use wpdb;

/**
 * Class WarehouseRepository
 *
 * @package WCYandexTaxiDeliveryPlugin\Repositories
 */
class WarehouseRepository
{
    /** @var wpdb */
    private $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;

        $this->createTableIfNotExists();
    }

    public function get(int $id): ?Warehouse
    {
        $row = $this->wpdb->get_row(
	        $this->wpdb->prepare(
				"SELECT * FROM `{$this->getTableName()}` WHERE id = %d",
				$id
	    ));

        if (empty($row)) {
            return null;
        }

        return $this->mapWarehouse($row);
    }

    /**
     * @return Warehouse[]
     */
    public function all(): array
    {
        $rows = $this->wpdb->get_results("SELECT * FROM `{$this->getTableName()}` ORDER BY id");

        if (is_null($rows)) {
            return [];
        }

        return array_map(function ($row) {
            return $this->mapWarehouse($row);
        }, $rows);
    }

    public function count(): int
    {
        return $this->wpdb->get_var("SELECT COUNT(*) FROM `{$this->getTableName()}`");
    }

    public function store(Warehouse $warehouse): void
    {
        if (is_null($warehouse->getId())) {
            $this->create($warehouse);
            return;
        }
        $this->update($warehouse);
    }

    public function delete(string $id): void
    {
        $this->wpdb->delete($this->getTableName(), ['id' => $id]);
    }

    private function update(Warehouse $warehouse): void
    {
        $this->wpdb->query(
            $this->wpdb->prepare(
                "UPDATE `{$this->getTableName()}` 
                        SET `address` = %s, `lat` = %s, `lon` = %s, `contact_email` = %s, `contact_name` = %s, 
                            `contact_phone` = %s, `startTime` = %s, `endTime` = %s, `comment` = %s, `flat` = %s, 
                            `porch` = %s, `floor` = %s WHERE `id` = %s",
                $warehouse->getAddress(),
                $warehouse->getLat(),
                $warehouse->getLon(),
                $warehouse->getContactEmail(),
                $warehouse->getContactName(),
                $warehouse->getContactPhone(),
                $warehouse->getStartTime(),
                $warehouse->getEndTime(),
                $warehouse->getComment(),
                $warehouse->getFlat(),
                $warehouse->getPorch(),
                $warehouse->getFloor(),
                $warehouse->getId()
            )
        );
    }

    private function create(Warehouse $warehouse): void
    {
        $this->wpdb->query(
            $this->wpdb->prepare(
                "INSERT INTO `{$this->getTableName()}` 
             (`address`, `lat`, `lon`, `contact_email`, `contact_name`, `contact_phone`, `startTime`, `endTime`, 
              `comment`, `flat`, `porch`, `floor`) 
                VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                $warehouse->getAddress(),
                $warehouse->getLat(),
                $warehouse->getLon(),
                $warehouse->getContactEmail(),
                $warehouse->getContactName(),
                $warehouse->getContactPhone(),
                $warehouse->getStartTime(),
                $warehouse->getEndTime(),
                $warehouse->getComment(),
                $warehouse->getFlat(),
                $warehouse->getPorch(),
                $warehouse->getFloor()
            )
        );

        $warehouse->setId($this->wpdb->insert_id);
    }

    private function mapWarehouse($raw): Warehouse
    {
        return new Warehouse(
            $raw->id,
            $raw->address,
            $raw->lat,
            $raw->lon,
            $raw->contact_email,
            $raw->contact_name,
            $raw->contact_phone,
            $raw->startTime,
            $raw->endTime,
            $raw->comment,
            $raw->flat,
            $raw->porch,
            $raw->floor
        );
    }

    private function createTableIfNotExists()
    {
        $this->wpdb->query(
            "
                CREATE TABLE IF NOT EXISTS `{$this->getTableName()}` (
                    `id` INT NOT NULL AUTO_INCREMENT,
                    `address` VARCHAR(255) NOT NULL,
                    `lat` DOUBLE,
                    `lon` DOUBLE,
                    `contact_email` VARCHAR(255) NOT NULL,
                    `contact_name` VARCHAR(255) NOT NULL,
                    `contact_phone` VARCHAR(255) NOT NULL,
                    `startTime` VARCHAR(255) NOT NULL,
                    `endTime` VARCHAR(255) NOT NULL,
                    `comment` VARCHAR(255) NOT NULL,
                    `flat` VARCHAR(255) NOT NULL,
                    `porch` VARCHAR(255) NOT NULL,
                    `floor` VARCHAR(255) NOT NULL,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
            "
        );
    }

    private function getTableName(): string
    {
        return YGO_PLUGIN_ID . '_warehouses';
    }
}
