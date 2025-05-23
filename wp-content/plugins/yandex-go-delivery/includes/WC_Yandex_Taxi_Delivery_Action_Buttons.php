<?php

defined( 'ABSPATH' ) || exit;

use WCYandexTaxiDeliveryPlugin\OrderDeliveryStatus;
use WCYandexTaxiDeliveryPlugin\Constants;
use WCYandexTaxiDeliveryPlugin\Helpers\AdminUrlHelper;

/**
 * Class WC_Yandex_Taxi_Delivery_Action_Buttons
 */
final class WC_Yandex_Taxi_Delivery_Action_Buttons {
	public static function add_order_actions_button( $actions, $order ) {
		/** WC_Order */ //global $the_order;

		$actions = self::add_send_to_delivery_button( $order, $actions );
		$actions = self::add_cancel_button( $order, $actions );

		return $actions;
	}

	private static function add_send_to_delivery_button( WC_Order $order, $actions ) {
		if ( ! OrderDeliveryStatus::isNotActive( $order ) ) {
			return $actions;
		}

		$action_slug = 'send_to_' . YGO_PLUGIN_ID;
		$order_id    = $order->get_id();

		$actions[ $action_slug ] = [
			'url'    => wp_nonce_url( AdminUrlHelper::getCreateClaimUrl( [ $order_id ] ), YGO_PLUGIN_ID . '_create_claim' ),
			'name'   => __( 'Отправка заказа в ', 'yandex-go-delivery' ) . Constants::getToPluginName(),
			'action' => $action_slug,
		];

		return $actions;
	}

	private static function add_cancel_button( WC_Order $order, $actions ) {
		if ( ! OrderDeliveryStatus::isActive( $order ) ) {
			return $actions;
		}

		$action_slug = 'cancel_' . YGO_PLUGIN_ID;
		$order_id    = $order->get_id();

		$actions[ $action_slug ] = [
			'url'    => wp_nonce_url( '/wp-admin/admin-post.php?action=' . YGO_PLUGIN_ID . '/get-cancel-info&order_id=' . $order_id, YGO_PLUGIN_ID . '_cancel' ),
			'name'   => __( 'Отмена заказа ', 'yandex-go-delivery' ) . Constants::getPluginName(),
			'action' => $action_slug,
		];

		return $actions;
	}
}
