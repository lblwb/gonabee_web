<?php

defined( 'ABSPATH' ) || exit;

use WCYandexTaxiDeliveryPlugin\Constants;
use Automattic\WooCommerce\Admin\Overrides\Order;
use WCYandexTaxiDeliveryPlugin\AdminPackageService;
use WCYandexTaxiDeliveryPlugin\Wrappers\OrderWrapper;
use WCYandexTaxiDeliveryPlugin\OrderDeliveryStatus;
use WCYandexTaxiDeliveryPlugin\Helpers\AdminUrlHelper;
use YandexTaxi\Delivery\Entities\RoutePoint\RoutePoint;
use WCYandexTaxiDeliveryPlugin\Repositories\WarehouseRepository;
use WCYandexTaxiDeliveryPlugin\DefaultWarehouseFinder;
use WCYandexTaxiDeliveryPlugin\Helpers\CountryRelatedDataHelper;

/**
 * Class WC_Yandex_Taxi_Delivery_Claim_Controller
 */
final class WC_Yandex_Taxi_Delivery_Claim_Controller extends WC_Yandex_Taxi_Delivery_Base_Controller {
	private const ORDER_PAGE_URL = '/wp-admin/edit.php?post_type=shop_order';

	public static function ask_creation() {
		$ids             = array_map( 'intval', $_REQUEST['order_ids'] ?? [] );
		$activeOrders    = [];
		$activeOrdersIds = [];
		$allOrders       = [];

		foreach ( $ids as $id ) {
			$order = wc_get_order( $id );

			if ( empty( $order ) ) {
				continue;
			}

			$wrappedOrder = new OrderWrapper( $order );
			$allOrders[]  = $wrappedOrder;

			if ( OrderDeliveryStatus::isActive( $order ) ) {
				$activeOrders[]    = $wrappedOrder;
				$activeOrdersIds[] = $order->get_id();
			}
		}

		if ( empty( $activeOrdersIds ) ) {
			$settings = get_option( YGO_PLUGIN_SETTINGS );
			if ( 'yes' === $settings['bulk_send_to_delivery'] ) {
				$url = AdminUrlHelper::getCreateBulkClaimUrl( $ids );
			} else {
				$url = AdminUrlHelper::getCreateClaimUrl( $ids );
			}

			if ( wp_safe_redirect( $url ) ) {
				exit;
			}
		}

		$notActiveIds = array_diff( $ids, $activeOrdersIds );

		self::renderView( 'claims/ask-creation', [
			'allOrders'                   => $allOrders,
			'activeOrders'                => $activeOrders,
			'backToOrdersPageUrl'         => self::ORDER_PAGE_URL,
			'createClaimUrlWithAll'       => AdminUrlHelper::getCreateClaimUrl( $ids ),
			'createClaimUrlWithNotActive' => AdminUrlHelper::getCreateClaimUrl( $notActiveIds ),
		] );
	}

	public static function create() {
		$settings = get_option( YGO_PLUGIN_SETTINGS );
		$is_bulk  = isset( $_REQUEST['bulk'] );

		if ( empty( $settings['token'] ) || ( 'Chile' !== $settings['country'] && empty( $settings['geocode_token'] ) ) ) {
			$url = admin_url( 'admin.php?page=' . YGO_PLUGIN_ID . '_settings' );

			self::renderView( 'message', [
				'message' => __( 'Настройки плагина не заполнены.', 'yandex-go-delivery' ) . ' <a href="' . $url . '">' . __( 'Перейти к странице настроек', 'yandex-go-delivery' ) . '</a>',
			] );

			return;
		}

		$ids    = array_map( 'intval', $_REQUEST['order_ids'] ?? [] );
		$orders = [];

		$ordersHaveAllowedPaymentMethods = true;

		foreach ( $ids as $id ) {
			$order = wc_get_order( $id );
			if ( ! empty( $order ) ) {
				$isPaymentMethodAllowed          = in_array( $order->get_payment_method(), $settings['payment_methods'] );
				$ordersHaveAllowedPaymentMethods = $ordersHaveAllowedPaymentMethods && $isPaymentMethodAllowed;
				$orders[]                        = new OrderWrapper( $order );
			}
		}

		if ( ! $orders ) {
			_e( 'Заказы не найдены', 'yandex-go-delivery' );

			return;
		}

		$settings = get_option( YGO_PLUGIN_SETTINGS );

		if ( 'Chile' === $settings['country'] ) {
			$chile_coordinate = '-33.45008256893874,-70.64508599999998';
			$warehouse        = new \WCYandexTaxiDeliveryPlugin\Entities\Warehouse( 4, 'Чили, Сантьяго, проспект Санта Исабель, 727', '-33.450082568939', '-70.645086', 'aaa@chile.cl', 'Хосе', '+56221234567', '00:00', '00:00', '', 123, '', 5 );
		} else {
			$warehouse = DefaultWarehouseFinder::find();
		}

		if ( $is_bulk ) {
			self::renderView( 'claims/create_bulk', [
				'isCountryCurrency'               => get_woocommerce_currency() === CountryRelatedDataHelper::getCurrency(),
				'ordersHaveAllowedPaymentMethods' => $ordersHaveAllowedPaymentMethods,
				'orders'                          => $orders,
				'geocodeToken'                    => $settings['geocode_token'],
				'chile_coordinate'                => $chile_coordinate ?? '',
				'warehouse'                       => $warehouse,
				'warehousesList'                  => ( new WarehouseRepository() )->all(),
				'warehouseCommentPlaceholder'     => __( 'Домофон: ', 'yandex-go-delivery' ),
			] );
		} else {
			self::renderView( 'claims/create', [
				'isCountryCurrency'               => get_woocommerce_currency() === CountryRelatedDataHelper::getCurrency(),
				'ordersHaveAllowedPaymentMethods' => $ordersHaveAllowedPaymentMethods,
				'orders'                          => $orders,
				'geocodeToken'                    => $settings['geocode_token'],
				'chile_coordinate'                => $chile_coordinate ?? '',
				'warehouse'                       => $warehouse,
				'warehousesList'                  => ( new WarehouseRepository() )->all(),
				'warehouseCommentPlaceholder'     => __( 'Домофон: ', 'yandex-go-delivery' ),
			] );

		}
	}

	public static function create_claim() {
		if ( isset( $_REQUEST['is_bulk_claim'] ) && $_REQUEST['is_bulk_claim'] ) {
			$request = $_REQUEST;
			foreach ( $request['warehouse'] as $wk => $warehouse ) {
				$key = self::get_key();
				$due = self::prepare_due( $warehouse['due'] ?? [] );

				try {
					$_REQUEST['warehouse'] = $warehouse;
					$_REQUEST['customer']  = [ $wk => $request['customer'][ $wk ] ];

					$service = new AdminPackageService();
					$service->createClaim( $key, $due );

					$data[] = [
						'created' => true,
						'key'     => $key,
					];
				} catch ( Exception $exception ) {
					self::renderException( $exception );
				}
			}

			self::renderJson( $data ?? [] );
		} else {
			$key = self::get_key();

			$due = self::prepare_due( $_REQUEST['warehouse']['due'] ?? [] );

			try {
				$service = new AdminPackageService();
				$claim   = $service->createClaim( $key, $due );

				self::renderJson( [
					'created' => true,
					'key'     => $key,
				] );
			} catch ( Exception $exception ) {
				self::renderException( $exception );
			}
		}

	}

	public static function get_claim() {
		if ( isset( $_REQUEST['is_bulk_claim'] ) && $_REQUEST['is_bulk_claim'] ) {
			$request = $_REQUEST;
			if ( is_array( $request['key'] ) ) {
				$keys = $request['key'];
			} else {
				$keys = explode( ',', $request['key'] );
				if ( ! is_array( $keys ) ) {
					$keys = $request['key'];
				}
			}
			$price_sum = 0;
			$params    = [];
			$currency  = '';
			$claim     = null;

			foreach ( $keys as $key ) {
				try {
					$_REQUEST['key'] = $key;

					$service = new AdminPackageService();
					$claim   = $service->getClaimByKey( $key );
				} catch ( Exception $exception ) {
					return self::renderException( $exception );
				}

				if ( is_null( $claim->getPrice() ) ) {
					$params[] = [
						'calculated' => false,
					];

					continue;
				}

				$price        = floatval( $claim->getPrice()->getValue() );
				$price_string = wc_price( $price, [
					'currency' => $claim->getPrice()->getCurrency(),
				] );

				$due      = $claim->getDue();
				$currency = $claim->getPrice()->getCurrency();

				if ( is_null( $due ) ) {
					$dueString = __( 'Ближайшее', 'yandex-go-delivery' );
				} else {
					$due->setTimezone( wp_timezone() );
					$dueString = $due->format( 'Y-m-d H:i' );
				}

				$tariff = empty( $claim->getTariffName() ) ? __( 'Не определен', 'yandex-go-delivery' ) : $claim->getTariffName();
				$param  = [
					'calculated'   => true,
					'price_number' => $price,
					'price'        => sprintf( esc_html__( 'Стоимость доставки: %1$s', 'yandex-go-delivery' ), $price_string ),
					'due'          => sprintf( esc_html__( 'Время подачи машины: %1$s', 'yandex-go-delivery' ), $dueString ),
					'tariff'       => __( 'Тариф', 'yandex-go-delivery' ) . ': ' . $tariff,
				];

				if ( ! empty( $claim->getWarnings() ) ) {
					$param['warning'] = implode( PHP_EOL, $claim->getWarnings() );
				}

				$params[] = $param;

				$price_sum = $price_sum + $price;
			}

			$data = [
				'claims'     => $params,
				'sum'        => $price_sum,
				'sum_string' => wc_price( $price_sum, [
					'currency' => $currency,
				] ),
			];

			self::renderJson( $data );
		} else {
			$key = self::get_key();

			try {
				$service = new AdminPackageService();
				$claim   = $service->getClaimByKey( $key );
			} catch ( Exception $exception ) {
				return self::renderException( $exception );
			}

			if ( is_null( $claim->getPrice() ) ) {
				return self::renderJson( [
					'calculated' => false,
				] );
			}

			$price = wc_price( $claim->getPrice()->getValue(), [
				'currency' => $claim->getPrice()->getCurrency(),
			] );

			$due = $claim->getDue();

			if ( is_null( $due ) ) {
				$dueString = __( 'Ближайшее', 'yandex-go-delivery' );
			} else {
				$due->setTimezone( wp_timezone() );
				$dueString = $due->format( 'Y-m-d H:i' );
			}

			$tariff = empty( $claim->getTariffName() ) ? __( 'Не определен', 'yandex-go-delivery' ) : $claim->getTariffName();
			$params = [
				'calculated'   => true,
				'price_number' => $claim->getPrice()->getValue(),
				'price'        => sprintf( esc_html__( 'Стоимость доставки: %1$s', 'yandex-go-delivery' ), $price ),
				'due'          => sprintf( esc_html__( 'Время подачи машины: %1$s', 'yandex-go-delivery' ), $dueString ),
				'tariff'       => __( 'Тариф', 'yandex-go-delivery' ) . ': ' . $tariff,
			];

			if ( ! empty( $claim->getWarnings() ) ) {
				$params['warning'] = implode( PHP_EOL, $claim->getWarnings() );
			}

			self::renderJson( $params );
		}
	}

	public static function get_tariffs() {
		if ( isset( $_REQUEST['is_bulk_claim'] ) && $_REQUEST['is_bulk_claim'] ) {
			$request = $_REQUEST;
			try {
				foreach ( $_REQUEST['coordinates'] as $item ) {
					$coordinate = explode( ',', $item['value'] );
					[ $lat, $lon ] = $coordinate;

					$service = new AdminPackageService();
					$tariffs = $service->getTariffs( floatval( $lat ), floatval( $lon ) );

					if ( ! empty( $tariffs ) ) {
						$html = self::getView( 'claims/_tariffs', [ 'tariffs' => $tariffs ] );
					} else {
						$html = self::getView( 'partial/_error', [ 'error' => self::getView( 'partial/_no_tariffs', [ 'tariffs' => false ] ) ] );
					}

					$labels = [];

					foreach ( $tariffs as $tariff ) {
						$labels[ $tariff->getName() ] = $tariff->getTitle();
					}
				}

				return self::renderJson( [
					'html'   => $html,
					'labels' => $labels,
				] );
			} catch ( Exception $exception ) {
				return self::renderException( $exception );
			}
		} else {
			try {
				[ $lat, $lon ] = explode( ',', $_REQUEST['coordinate'] );

				$service = new AdminPackageService();
				$tariffs = $service->getTariffs( $lat, $lon );

				if ( ! empty( $tariffs ) ) {
					$html = self::getView( 'claims/_tariffs', [ 'tariffs' => $tariffs ] );
				} else {
					$html = self::getView( 'partial/_error', [ 'error' => self::getView( 'partial/_no_tariffs' ) ] );
				}

				$labels = [];

				foreach ( $tariffs as $tariff ) {
					$labels[ $tariff->getName() ] = $tariff->getTitle();
				}

				return self::renderJson( [
					'html'   => $html,
					'labels' => $labels,
				] );
			} catch ( Exception $exception ) {
				return self::renderException( $exception );
			}
		}
	}

	public static function confirm() {
		if ( empty( $_REQUEST['key'] ) ) {
			return self::renderJsonError( 'Order Key not found' );
		}

		if ( isset( $_REQUEST['is_bulk_claim'] ) && $_REQUEST['is_bulk_claim'] ) {
			$keys = explode( ',', $_REQUEST['key'] );

			$i = 0;

			$order_ids = array_keys( $_REQUEST['customer'] );
			foreach ( $keys as $key ) {
				$_REQUEST['key'] = $key;

				$ids = array_map( 'intval', [ $order_ids[ $i ++ ] ] );

				$service = new AdminPackageService();
				$service->confirm( $key, $ids );
			}
		} else {
			$key = sanitize_key( $_REQUEST['key'] );
			$ids = array_map( 'intval', array_keys( $_REQUEST['customer'] ) );

			$service = new AdminPackageService();
			$service->confirm( $key, $ids );
		}

		if ( count( $ids ) === 1 ) {
			$orderId = array_shift( $ids );
			if ( wp_safe_redirect( "/wp-admin/post.php?post={$orderId}}&action=edit" ) ) {
				exit;
			}
		}

		if ( wp_safe_redirect( self::ORDER_PAGE_URL ) ) {
			exit;
		}
	}

	public static function get_cancel_info() {
		/** @var Order|false $order */
		$order = wc_get_order( (int) ( $_REQUEST['order_id'] ) );

		try {
			$service = new AdminPackageService();
			$claim   = $service->getClaim( $order );
		} catch ( Exception $exception ) {
			return self::renderException( $exception );
		}

		if ( is_null( $claim->getAvailableCancelStatus() ) ) {
			return self::renderJson( [
				'is_confirm' => false,
				'message'    => __( 'Данный заказ уже невозможно отменить', 'yandex-go-delivery' ),
			] );
		}

		if ( $claim->isMulti() ) {
			$ids = array_map( function ( RoutePoint $point ) {
				return $point->getOrderId();
			}, $claim->getDestinations() );

			$ids = preg_filter( '/^/', '№', $ids );

			$message = __( 'Отмена доставки данного заказа, приведет к отмене заказов: ', 'yandex-go-delivery' ) . implode( ', ', $ids ) . '.' . PHP_EOL;
		} else {
			$message = sprintf( esc_html__( 'Для заказа №%1$s доступна: ', 'yandex-go-delivery' ), $order->get_id() );
		}

		$message .= __( 'cancel_status_' . $claim->getAvailableCancelStatus()->getValue(), 'yandex-go-delivery' ) . '. ' . __( 'Подтвердить отмену?', 'yandex-go-delivery' );

		self::renderJson( [
			'is_confirm'    => true,
			'id'            => $order->get_id(),
			'version'       => $claim->getVersion(),
			'cancel_status' => $claim->getAvailableCancelStatus()->getValue(),
			'message'       => $message,
		] );
	}

	public static function cancel() {
		/** @var Order|false $order */
		$order = wc_get_order( $_REQUEST['order_id'] );

		try {
			$service = new AdminPackageService();
			$service->cancel( $order, (int) ( $_REQUEST['version'] ), $_REQUEST['cancel_status'] );

			return self::renderJson( [
				'message' => __( 'Заказ был отменен', 'yandex-go-delivery' ),
			] );
		} catch ( Exception $exception ) {
			return self::renderException( $exception );
		}
	}

	public static function get_order_route_point() {
		$id         = ( $_REQUEST['order_id'] !== 'fake' ) ? (int) ( $_REQUEST['order_id'] ) : 'fake';
		$pointCount = (int) ( $_REQUEST['point_count'] );

		if ( $id === 'fake' ) {
			return self::renderJson( [
				'html' => self::getView( 'claims/_route_point', [
					'id'                 => 'fake_' . uniqid(),
					'address'            => '',
					'fullName'           => '',
					'phone'              => '',
					'editUrl'            => '',
					'isFake'             => true,
					'commentPlaceHolder' => __( 'Домофон: ', 'yandex-go-delivery' ),
					'pointNumber'        => ( $pointCount + 2 ),
				] ),
			] );
		}

		$order = wc_get_order( $id );

		if ( empty( $order ) ) {
			return self::renderJsonError( __( 'Заказ не найден', 'yandex-go-delivery' ) );
		}

		if ( OrderDeliveryStatus::isActive( $order ) ) {
			return self::renderJsonError( sprintf( esc_html__( 'Заказ №%1$s уже отправлен в доставку', 'yandex-go-delivery' ), $id ) );
		}

		$wrappedOrder = new OrderWrapper( $order );

		return self::renderJson( [
			'html' => self::getView( 'claims/_route_point', [
				'id'                 => $wrappedOrder->getId(),
				'address'            => $wrappedOrder->getAddress(),
				'fullName'           => $wrappedOrder->getFullName(),
				'phone'              => $wrappedOrder->getPhone(),
				'editUrl'            => $wrappedOrder->getEditUrl(),
				'isFake'             => false,
				'commentPlaceHolder' => $wrappedOrder->getCommentPlaceHolder(),
				'pointNumber'        => ( $pointCount + 2 ),
			] ),
		] );
	}

	private static function prepare_due( array $raw ): ?DateTime {
		if ( empty( $raw ) ) {
			return null;
		}

		if ( ! isset( $raw['is_on'] ) || $raw['is_on'] !== 'on' ) {
			return null;
		}

		$datetime = new DateTime( $raw['date'], wp_timezone() );
		$datetime->setTime( (int) ( $raw['hour'] ), (int) ( $raw['minute'] ) );

		if ( $datetime < current_datetime() ) {
			return null;
		}

		return $datetime;
	}

	private static function get_key(): string {
		return ! empty( $_REQUEST['key'] ) ? sanitize_key( $_REQUEST['key'] ) : uniqid();
	}
}
