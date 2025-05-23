<?php

namespace WCYandexTaxiDeliveryPlugin\Wrappers;

defined('ABSPATH') || exit;

use WC_Product;
use WCYandexTaxiDeliveryPlugin\Constants;
use WCYandexTaxiDeliveryPlugin\Helpers\CountryRelatedDataHelper;

/**
 * Class ProductWrapper
 *
 * @package WCYandexTaxiDeliveryPlugin\Wrappers
 */
class ProductWrapper
{
    /** @var WC_Product */
    private $entity;

    /** @var array */
    private $settings;

    public function __construct(WC_Product $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->entity->get_id();
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->entity->get_title();
    }

    /**
     * @return float now kg is hardcoded
     */
    public function getWeight(): float
    {
        $prop = (float) $this->entity->get_weight();

        if (!empty($prop)) {
            return $this->normalizeWeight($prop);
        }

        return $this->normalizeWeight($this->getSettings()['default_weight']);
    }

    public function getWidth(): float
    {
        $prop = (float) $this->entity->get_width();

        if (!empty($prop)) {
            return $this->normalizeDimension($prop);
        }

        return $this->normalizeDimension($this->getSettings()['default_width']);
    }

    public function getLength(): float
    {
        $prop = (float) $this->entity->get_length();

        if (!empty($prop)) {
            return $this->normalizeDimension($prop);
        }

        return $this->normalizeDimension($this->getSettings()['default_length']);
    }

    public function getHeight(): float
    {
        $prop = (float) $this->entity->get_height();

        if (!empty($prop)) {
            return $this->normalizeDimension($prop);
        }

        return $this->normalizeDimension($this->getSettings()['default_height']);
    }

    /**
     * @return string
     */
    public function getPrice(): string
    {
        return $this->entity->get_price();
    }

    public function getCurrency(): string
    {
        return CountryRelatedDataHelper::getCurrency();
    }

    public function getSku(): string
    {
	    return $this->entity->get_sku();
    }

    private function getSettings(): array
    {
        if (!empty($this->settings)) {
            return $this->settings;
        }

        $this->settings =  get_option(YGO_PLUGIN_SETTINGS);

        return $this->settings;
    }

    private function normalizeDimension(float $dimension)
    {
        return wc_get_dimension($dimension, 'm'); // yandex expects meters
    }

    private function normalizeWeight(float $weight): float
    {
        return wc_get_weight($weight, 'kg'); // yandex expects kgs
    }
}
