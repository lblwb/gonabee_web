<?php

namespace WCYandexTaxiDeliveryPlugin\Repositories;

defined( 'ABSPATH' ) || exit;

use wpdb;
use WCYandexTaxiDeliveryPlugin\Constants;

/**
 * Class ClaimMetaRepository
 *
 * @package WCYandexTaxiDeliveryPlugin\Repositories
 */
class ClaimMetaRepository {
	/** @var wpdb */
	private $wpdb;

	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;

		$this->createTablesIfNotExists();
	}

	public function storeClaimForOrder( string $claimId, array $orderIds ): void {
		$orderIds = array_unique( $orderIds );

		// insert if not exist
		$row = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM `{$this->getMetaTableName()}` WHERE `claim_id`= (%s)", $claimId ) );

		if ( empty( $row ) ) {
			$this->wpdb->query( $this->wpdb->prepare( "INSERT INTO `{$this->getMetaTableName()}` (`claim_id`) VALUES (%s)", $claimId ) );
		}

		foreach ( $orderIds as $orderId ) {
			// unlink existing claim ids
			$this->wpdb->query( $this->wpdb->prepare( "DELETE FROM `{$this->getRelTableName()}` WHERE `order_id` = (%s)", $orderId ) );

			// link new
			$this->wpdb->query( $this->wpdb->prepare( "INSERT INTO `{$this->getRelTableName()}` (`claim_id`, `order_id`) VALUES (%s, %s)", $claimId, $orderId ) );
		}
	}

	public function getOrdersByClaimId( string $claimId ): array {
		$rows = $this->wpdb->get_results( $this->wpdb->prepare( "SELECT * FROM `{$this->getRelTableName()}` 
                WHERE `claim_id`= (%s)", $claimId ) );

		return array_map( function ( $row ) {
			return $row->order_id;
		}, $rows );
	}

	public function getClaimIdByOrder( string $orderId ): ?string {
		$row = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM `{$this->getRelTableName()}` 
                WHERE `order_id`= (%s)", $orderId ) );

		if ( empty( $row ) ) {
			return null;
		}

		return $row->claim_id;
	}

	public function getMetaForOrder( string $orderId ): ?array {
		$row = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM `{$this->getRelTableName()}` 
                JOIN `{$this->getMetaTableName()}` 
                ON `{$this->getRelTableName()}`.claim_id = `{$this->getMetaTableName()}`.claim_id
                WHERE `order_id`= (%s)", $orderId ) );

		if ( empty( $row ) ) {
			return [];
		}

		return [
			'claimId'      => $row->claim_id,
			'driver'       => $row->driver,
			'driverPhone'  => $row->driver_phone,
			'tariff'       => $row->tariff,
			'actUrl'       => $row->act_url,
			'shippingSlot' => $row->shipping_slot,
			'price'        => $row->price,
		];
	}

	public function updateDriver( string $claimId, ?string $driver ): void {
		$this->wpdb->query( $this->wpdb->prepare( "UPDATE `{$this->getMetaTableName()}` SET `driver` = %s WHERE `claim_id` = %s", $driver, $claimId ) );
	}

	public function updateDriverPhone( string $claimId, ?string $driverPhone ): void {
		$this->wpdb->query( $this->wpdb->prepare( "UPDATE `{$this->getMetaTableName()}` SET `driver_phone` = %s WHERE `claim_id` = %s", $driverPhone, $claimId ) );
	}

	public function updateTariff( string $claimId, ?string $tariff ): void {
		$this->wpdb->query( $this->wpdb->prepare( "UPDATE `{$this->getMetaTableName()}` SET tariff = %s WHERE `claim_id` = %s", $tariff, $claimId ) );
	}

	public function updatePrice( string $claimId, int $price ): void {
		$this->wpdb->query( $this->wpdb->prepare( "UPDATE `{$this->getMetaTableName()}` SET price = %s WHERE `claim_id` = %s", $price, $claimId ) );
	}

	public function updateActUrl( string $claimId, ?string $actUrl ): void {
		$this->wpdb->query( $this->wpdb->prepare( "UPDATE `{$this->getMetaTableName()}` SET `act_url` = %s WHERE `claim_id` = %s", $actUrl, $claimId ) );
	}

	public function updateShippingSlot( string $claimId, ?string $slot ): void {
		$this->wpdb->query( $this->wpdb->prepare( "UPDATE `{$this->getMetaTableName()}` SET `shipping_slot` = %s WHERE `claim_id` = %s", $slot, $claimId ) );
	}

	private function createTablesIfNotExists(): void {
		$this->wpdb->query( "
                CREATE TABLE IF NOT EXISTS `{$this->getMetaTableName()}` (
                    `claim_id` VARCHAR(255) NOT null UNIQUE,
                    `driver` VARCHAR(255) DEFAULT null,
                    `driver_phone` VARCHAR(255) DEFAULT null,
                    `act_url` VARCHAR(255) DEFAULT null,
                    `tariff` VARCHAR(255) DEFAULT null,
                    `shipping_slot` VARCHAR(255) DEFAULT null,
                    `price` INTEGER DEFAULT null,
                    PRIMARY KEY(`claim_id`)
                ) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
            " );

		$priceExists = $this->wpdb->query( "SHOW COLUMNS FROM `{$this->getMetaTableName()}` LIKE 'price';" );

		if ( empty( $priceExists ) ) {
			$this->wpdb->query( "ALTER TABLE `{$this->getMetaTableName()}` ADD COLUMN `price` INTEGER DEFAULT null;" );
		}

		$this->wpdb->query( "
                CREATE TABLE IF NOT EXISTS `{$this->getRelTableName()}` (
                    `claim_id` VARCHAR(255) NOT null,
                    `order_id` INT(11) NOT null,
                    PRIMARY KEY(`claim_id`, `order_id`)
                ) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci;
            " );
	}

	private function getMetaTableName(): string {
		return YGO_PLUGIN_ID . '_claim_meta';
	}

	private function getRelTableName(): string {
		return YGO_PLUGIN_ID . '_order_claim_rel';
	}

	public function removeMetaForOrder( string $orderId ): bool {
		$delete = $this->wpdb->delete( $this->getMetaTableName(), [
			'claim_id' => $this->getClaimIdByOrder( $orderId ),
		] );

		if ( ! $delete ) {
			return false;
		}

		return true;
	}
}
