<?php

namespace WCYandexTaxiDeliveryPlugin\Helpers;

defined( 'ABSPATH' ) || exit;

use WCYandexTaxiDeliveryPlugin\Constants;

/**
 * Class AdminUrlHelper
 *
 * @package WCYandexTaxiDeliveryPlugin\Helpers
 */
class AdminUrlHelper {
	public static function getCreateClaimUrl( array $ids ): string {
		return self::createUrl( 'create_claim', [ 'order_ids' => $ids ] );
	}

	public static function getCreateBulkClaimUrl( array $ids ): string {
		return self::createUrl( 'create_claim', [
			'bulk'      => 1,
			'order_ids' => $ids,
		] );
	}

	public static function getBaseAskClaimCreationUrl(): string {
		return self::createUrl( 'ask_claim_creation' );
	}

	public static function getAskClaimCreationUrl( array $ids ): string {
		return self::createUrl( 'ask_claim_creation', [ 'order_ids' => $ids ] );
	}

	public static function getWarehouseEditUrl( ?int $id ): string {
		return self::createUrl( 'warehouses_edit', is_null( $id ) ? [] : [ 'id' => $id ] );
	}

	public static function getWarehouseIndexUrl(): string {
		return self::createUrl( 'warehouses' );
	}

	public static function getOrdersPageUrl(): string {
		return admin_url( 'edit.php?post_type=shop_order ' );
	}

	public static function getCreateCabinetUrl(): string {
		return self::createUrl( 'cabinet_modal' );
	}

	public static function getSettingsUrl(): string {
		return admin_url( 'admin.php?page=' . YGO_PLUGIN_ID . '_settings' );
	}

	private static function createUrl( string $action, array $query = [] ): string {
		$base = 'admin.php?page=' . YGO_PLUGIN_ID;

		$path = $base . '_' . $action;

		if ( ! empty( $query ) ) {
			$path .= '&' . http_build_query( $query );
		}

		return admin_url( $path );
	}
}
