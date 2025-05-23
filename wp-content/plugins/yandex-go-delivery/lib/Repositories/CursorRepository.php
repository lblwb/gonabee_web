<?php

namespace WCYandexTaxiDeliveryPlugin\Repositories;

defined('ABSPATH') || exit;

use YandexTaxi\Delivery\Entities\Journal\Cursor;
use YandexTaxi\Delivery\YandexApi\Repositories\CursorRepository as BaseRepository;
use DateTime;
use WCYandexTaxiDeliveryPlugin\Constants;
use wpdb;

/**
 * Class CursorRepository
 *
 * @package WCYandexTaxiDeliveryPlugin\Repositories
 */
class CursorRepository implements BaseRepository
{
    /** @var wpdb */
    private $wpdb;

    public function __construct()
    {
        global $wpdb;

        $this->wpdb = $wpdb;
        $this->createTableIfNotExists();
    }

    public function getLatest(): ?Cursor
    {
        $row = $this->wpdb->get_row("SELECT * FROM `{$this->getTableName()}` ORDER BY datetime DESC");

        if (empty($row)) {
            return null;
        }

        return new Cursor($row->value);
    }

    public function store(Cursor $cursor): void
    {
        $this->wpdb->query(
            $this->wpdb->prepare(
                "INSERT INTO `{$this->getTableName()}` (`value`, `datetime`) VALUES (%s, NOW())",
                $cursor->getValue()
            )
        );
    }

    public function delete(Cursor $cursor): void
    {
        $this->wpdb->query(
            $this->wpdb->prepare(
                "DELETE FROM `{$this->getTableName()}` WHERE value = %s",
                $cursor->getValue()
            )
        );
    }

    public function deleteOlderThanYesterday(): void
    {
        $dateTime = new DateTime();
        $dateTime->modify('-1 day');
        $dateTime->setTime(23, 59, 59);

        $this->wpdb->query(
            $this->wpdb->prepare(
                "DELETE FROM `{$this->getTableName()}` WHERE datetime <= %s",
                $dateTime->format('Y-m-d H:i:s')
            )
        );

    }

    private function createTableIfNotExists()
    {
        $this->wpdb->query(
            "
                CREATE TABLE IF NOT EXISTS `{$this->getTableName()}` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `value` VARCHAR(255) NOT NULL,
                    `datetime` DATETIME NOT NULL,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
            "
        );
    }

    private function getTableName(): string
    {
        return YGO_PLUGIN_ID . '_journal_cursors';
    }
}
