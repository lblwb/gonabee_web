<?php

namespace WCYandexTaxiDeliveryPlugin;

defined( 'ABSPATH' ) || exit;

use DateTime;
use Exception;
use RuntimeException;
use WC_Order;
use WC_Product;
use WCYandexTaxiDeliveryPlugin\Helpers\CountryRelatedDataHelper;
use WCYandexTaxiDeliveryPlugin\Repositories\ClaimMetaRepository;
use YandexTaxi\Delivery\Entities\Claim\AvailableCancelStatus;
use YandexTaxi\Delivery\Entities\Claim\Claim;
use YandexTaxi\Delivery\Entities\Claim\Tariff;
use YandexTaxi\Delivery\Entities\ClaimItem\ClaimItem;
use YandexTaxi\Delivery\Entities\Order\Order;
use YandexTaxi\Delivery\Entities\RoutePoint\Address;
use YandexTaxi\Delivery\Entities\RoutePoint\Contact;
use YandexTaxi\Delivery\Entities\RoutePoint\RoutePoint;
use YandexTaxi\Delivery\Exceptions\ClaimNotFoundException;
use YandexTaxi\Delivery\Services\ClaimService;
use YandexTaxi\Delivery\Services\TariffTextFinder;
use YandexTaxi\Delivery\YandexApi\Exceptions\YandexApiException;
use YandexTaxi\Delivery\YandexApi\Resources\Tariffs;

/**
 * Class AdminPackageService
 *
 * @package WCYandexTaxiDeliveryPlugin
 */
class AdminPackageService extends BasePackageService {
	/** @var Tariffs */
	private $tariffs;

	/** @var ClaimService */
	protected $claims;

	public function __construct() {
		$this->claims  = Container::get( ClaimService::class );
		$this->tariffs = Container::get( Tariffs::class );
	}

	/**
	 * @param string $key
	 * @param DateTime|null $due
	 *
	 * @return Claim
	 * @throws Exception
	 */
	public function createClaim( string $key, ?DateTime $due ): ?Claim {
		$orders = $this->prepareOrders();

		$source = $this->prepareRoutePoint( $_REQUEST['warehouse'], null ); // no order id for source

		$requirements = $this->getClientRequirements();

		return $this->claims->calculateShippingPrice( $key, $source, $orders, $this->getTariff(), $requirements, $due, false );
	}

	public function getClaimByKey( string $key ): Claim {
		return $this->claims->getByKey( $key );
	}

	/**
	 * @return Order[]
	 * @throws Exception
	 */
	private function prepareOrders(): array {
		$ids = array_unique( array_keys( $_REQUEST['customer'] ) );

		$orders = [];

		foreach ( $ids as $id ) {
			$order = $this->createOrder( $id );

			if ( ! is_null( $order ) ) {
				$orders[] = $order;
			}
		}

		if ( empty( $orders ) ) {
			throw new Exception( 'Orders not found' );
		}

		return $orders;
	}

	private function getTariff(): ?string {
		if ( ! isset( $_REQUEST['tariff'] ) ) {
			return null;
		}

		if ( $_REQUEST['tariff'] === 'default' ) {
			return null;
		}

		return sanitize_text_field( $_REQUEST['tariff'] );
	}

	private function getClientRequirements(): array {
		if ( ! isset( $_REQUEST['tariff_requirements'] ) ) {
			return [];
		}

		$tariff = $this->getTariff();

		if ( empty( $tariff ) ) {
			return [];
		}

		$preparedRequirements = $_REQUEST['tariff_requirements'][ $tariff ] ?? [];

		// delete not selected params
		foreach ( $preparedRequirements as $key => $value ) {
			if ( $value === 'false' ) {
				unset( $preparedRequirements[ $key ] );
			}

			if ( is_numeric( $value ) ) {
				$preparedRequirements[ $key ] = (int) $value;
			}
		}

		return $preparedRequirements;
	}

	private function createOrder( string $id ): ?Order {
		if ( $this->isFakeOrderId( $id ) ) {
			return Order::createFake( $this->getDestinationFromRequest( $id ) );
		}

		$wcOrder = wc_get_order( $id );
		if ( empty( $wcOrder ) ) {
			return null;
		}

		return Order::createReal( $this->getDestinationFromRequest( $id ), $this->prepareItems( $wcOrder->get_items(), $wcOrder->get_id() ) );
	}

	private function isFakeOrderId( string $id ): bool {
		return preg_match( "#^fake_(.*)$#i", $id );
	}

	private function getDestinationFromRequest( string $orderId ): RoutePoint {
		if ( ! isset( $_REQUEST['customer'][ $orderId ] ) ) {

			throw new RuntimeException( sprintf( esc_html__( 'Данные по заказу %1$s не найдены', 'yandex-go-delivery' ), $orderId ) );
		}

		return $this->prepareRoutePoint( $_REQUEST['customer'][ $orderId ], $this->isFakeOrderId( $orderId ) ? null : $orderId );
	}

	/**
	 * @param string $key
	 * @param int[] $orderIds
	 *
	 * @throws ClaimNotFoundException
	 * @throws YandexApiException
	 */
	public function confirm( string $key, array $orderIds ) {
		$deliveryId = $this->claims->confirm( $key );

		$orderIds = array_filter( $orderIds, function ( $id ) {
			return ! $this->isFakeOrderId( $id );
		} );

		$claim       = $this->claims->get( $deliveryId );
		$tariffLabel = $this->getTariffLabel( $claim );

		foreach ( $orderIds as $id ) {
			$this->saveOrderInfo( (int) $id, $claim, $tariffLabel );
		}
	}

	/**
	 * @param WC_Order $order
	 *
	 * @return Claim
	 * @throws YandexApiException
	 */
	public function getClaim( WC_Order $order ): Claim {
		return $this->claims->get( $this->getClaimIdFromOrder( $order ) );
	}

	/**
	 * @param WC_Order $order
	 * @param int $version
	 * @param string $status
	 *
	 * @throws YandexApiException
	 */
	public function cancel( WC_Order $order, int $version, string $status ): void {
		$claimId = $this->getClaimIdFromOrder( $order );
		$this->claims->cancel( $claimId, $version, AvailableCancelStatus::fromCode( $status ) );

		$metaRepository = new ClaimMetaRepository();
		$metaRepository->removeMetaForOrder( $order->get_id() );

		$claim = $this->claims->get( $claimId );

		foreach ( $claim->getDestinations() as $destination ) {
			$order = wc_get_order( $destination->getOrderId() );

			if ( empty( $order ) ) {
				continue;
			}

			OrderMetaHelper::updateShippingStatus( $order, $claim->getStatus() );
		}
	}

	/**
	 * @param float $lat
	 * @param float $lon
	 *
	 * @return Tariff[]
	 * @throws YandexApiException
	 */
	public function getTariffs( float $lat, float $lon ): array {
		return $this->tariffs->getAllForPoint( $lat, $lon );
	}

	private function getClaimIdFromOrder( WC_Order $order ): string {
		$metaRepository = new ClaimMetaRepository();
		$claimId        = $metaRepository->getClaimIdByOrder( $order->get_id() );

		if ( is_null( $claimId ) ) {
			throw new Exception( "Delivery id not found in order: {$order->get_id()}" );
		}

		return $claimId;
	}

	private function prepareRoutePoint( array $raw, ?string $orderId ): RoutePoint {
		[ $lat, $lon ] = explode( ',', sanitize_text_field( $raw['coordinate'] ) );

		$address = new Address( sanitize_text_field( $raw['address'] ), $lat, $lon, sanitize_textarea_field( $raw['comment'] ?? '' ) );

		$flat = (int) ( $raw['flat'] ?? null );
		$flat = empty( $flat ) ? null : $flat;

		$floor = (int) ( $raw['floor'] ?? null );
		$floor = empty( $floor ) ? null : $floor;

		$address->setFlat( $flat );
		$address->setPorch( $raw['porch'] ?? null );
		$address->setFloor( $floor );

		return new RoutePoint( new Contact( sanitize_text_field( $raw['name'] ), sanitize_text_field( $raw['phone'] ), sanitize_email( $raw['email'] ?? '' ), CountryRelatedDataHelper::getUpperPhoneCountry() ), $address, isset( $raw['sms_on'] ) && $raw['sms_on'] === 'on', $orderId );
	}

	/**
	 * @param array $products
	 * @param string $orderId
	 *
	 * @return ClaimItem[]
	 */
	private function prepareItems( array $products, string $orderId ): array {
		$items = [];

		foreach ( $products as $item ) {
			/** @var WC_Product $product */
			$product = wc_get_product( $item->get_data()['product_id'] );

			$items[] = $this->prepareItem( $product, $orderId, $item->get_quantity() );
		}

		return $items;
	}

	private function getTariffLabel( Claim $claim ): string {
		return ( new TariffTextFinder( $this->tariffs ) )->find( $claim );
	}
}
