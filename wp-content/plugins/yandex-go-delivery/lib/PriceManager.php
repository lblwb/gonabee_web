<?php

namespace WCYandexTaxiDeliveryPlugin;

/**
 * Class PriceManager
 *
 * @package WCYandexTaxiDeliveryPlugin
 */
class PriceManager
{
    /** @var int */
    private $priceMarkup;

    /** @var bool */
    private $isDiscountOn;

    /** @var int */
    private $discount;

    /** @var int */
    private $discountFrom;

    public function __construct(int $priceMarkup, bool $isDiscountOn = false, int $discount = 0, int $discountFrom = 0)
    {
        $this->priceMarkup = $priceMarkup;
        $this->isDiscountOn = $isDiscountOn;
        $this->discount = $discount;
        $this->discountFrom = $discountFrom;
    }

    public function prepare(float $deliveryPrice, float $packageCost): float
    {
        $deliveryPrice = $this->addPriceMarkup($deliveryPrice);
        $deliveryPrice = $this->addDiscount($deliveryPrice, $packageCost);

        return $deliveryPrice;
    }

    private function addPriceMarkup(float $price): float
    {
        if (empty($this->priceMarkup)) {
            return $price;
        }

        $coefficient = ($this->priceMarkup / 100) + 1;

        return $price * $coefficient;
    }

    private function addDiscount(float $price, float $packageCost): float
    {
        if (!$this->isDiscountOn) {
            return $price;
        }

        if ($packageCost < $this->discountFrom) {
            return $price;
        }

        $coefficient = 1 - ($this->discount / 100);

        if ($coefficient < 0) {
            return 0;
        }

        return $price * $coefficient;
    }
}
