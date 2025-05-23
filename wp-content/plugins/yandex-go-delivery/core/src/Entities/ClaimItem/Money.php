<?php

namespace YandexTaxi\Delivery\Entities\ClaimItem;

defined( 'YGO_CALLED_FROM_PLUGIN' ) || exit;

/**
 * Class Money
 *
 * @package YandexTaxi\Delivery\Entities\ClaimItem
 */
class Money {
	/** @var string */
	private $value;

	/** @var string */
	private $currency;

	public function __construct( string $value, string $currency ) {
		$this->value    = number_format( $value, 2, '.', '' );
		$this->currency = $currency;
	}

	public function getValue(): string {
		return $this->value;
	}

	public function getCurrency(): string {
		return $this->currency;
	}
}
