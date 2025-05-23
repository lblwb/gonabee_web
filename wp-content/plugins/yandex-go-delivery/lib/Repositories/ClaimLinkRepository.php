<?php

namespace WCYandexTaxiDeliveryPlugin\Repositories;

defined('ABSPATH') || exit;

use wpdb;
use WCYandexTaxiDeliveryPlugin\Constants;
use YandexTaxi\Delivery\ClaimLink\ClaimLink;
use YandexTaxi\Delivery\ClaimLink\ClaimLinkRepository as BaseRepository;

/**
 * Class ClaimLinkRepository
 *
 * @package Delivery\ClaimLink
 */
class ClaimLinkRepository implements BaseRepository
{
    /** @var wpdb */
    private $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;

        $this->createTableIfNotExists();
    }

    /**
     * @param string $id
     *
     * @return ClaimLink|null
     */
    public function get(string $id): ?ClaimLink
    {
        $row = $this->wpdb->get_row(
	        $this->wpdb->prepare(
				"SELECT * FROM `{$this->getTableName()}` WHERE id = %s",$id
	        ));

        if (empty($row)) {
            return null;
        }

        return new ClaimLink(
            $row->id,
            $row->meta_hash,
            $row->address,
            $row->lat,
            $row->lon,
            $row->claim_id,
            $row->version
        );
    }

    public function store(ClaimLink $link): void
    {
        $storedLink = $this->get($link->getId());

        if (is_null($storedLink)) {
            $this->create($link);
            return;
        }

        $this->update($link);
    }

    public function delete(string $id): void
    {
        $this->wpdb->delete($this->getTableName(), ['id' => $id]);
    }

    private function update(ClaimLink $link): void
    {
        $this->wpdb->query(
            $this->wpdb->prepare(
                "UPDATE `{$this->getTableName()}` 
                        SET `meta_hash` = %s, `address`= %s, `lat`= %s, `lon`= %s, `claim_id` = %s, `version` = %s
                        WHERE `id` = %s",
                $link->getMetaHash(),
                $link->getAddress(),
                $link->getLat(),
                $link->getLon(),
                $link->getClaimId(),
                $link->getVersion(),
                $link->getId()
            )
        );
    }

    private function create(ClaimLink $link): void
    {
        $this->wpdb->query(
            $this->wpdb->prepare(
                "INSERT INTO `{$this->getTableName()}` 
                (`id`, `meta_hash`, `address`, `lat`, `lon`, `claim_id`, `version`) 
                VALUES (%s, %s, %s, %s, %s, %s, %s)",
                $link->getId(), $link->getMetaHash(), $link->getAddress(),
                $link->getLat(), $link->getLon(), $link->getClaimId(), $link->getVersion()
            )
        );
    }

    private function createTableIfNotExists()
    {
        $this->wpdb->query(
            "
                CREATE TABLE IF NOT EXISTS `{$this->getTableName()}` (
                    `id` VARCHAR(255) NOT NULL,
                    `meta_hash` VARCHAR(255) NOT NULL,
                    `address` VARCHAR(255) NOT NULL,
                    `lat` DOUBLE,
                    `lon` DOUBLE,
                    `claim_id` VARCHAR(255) DEFAULT NULL,
                    `version` INT(11) NOT NULL,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
            "
        );
    }

    private function getTableName(): string
    {
        return YGO_PLUGIN_ID . '_claim_links';
    }
}
