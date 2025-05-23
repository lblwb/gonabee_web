<?php

defined('ABSPATH') || exit;

use WCYandexTaxiDeliveryPlugin\Constants;
use YandexTaxi\Delivery\Exceptions\ValidationError;
use YandexTaxi\Delivery\YandexApi\Exceptions\YandexApiException;
use WCYandexTaxiDeliveryPlugin\ClientPackageService;
use WCYandexTaxiDeliveryPlugin\DefaultWarehouseFinder;
use WCYandexTaxiDeliveryPlugin\PriceManager;

/**
 * Class WC_Yandex_Taxi_Delivery_Shipping_Method
 */
final class WC_Yandex_Taxi_Delivery_Shipping_Method extends WC_Shipping_Method
{
    /** @var ClientPackageService */
    private $packageService;

    public function __construct($instance_id = 0)
    {
        parent::__construct($instance_id);

        $this->id = YGO_PLUGIN_ID;
        $this->method_title = Constants::getPluginName();

        $this->init_settings();

        $this->enabled = $this->is_enabled() ? 'yes' : 'no';
        $this->title = Constants::getPluginName();

        //$this->supports = []; // no settings page
        $this->supports = [
            'shipping-zones',
            //'settings',
            //'instance-settings',
        ];

        $this->init();
    }

    public function init()
    {
        if ($this->enabled === 'yes') {
            $service = new ClientPackageService();
            if (!empty($service)) {
                $this->packageService = $service;
            }
        }

        add_filter("woocommerce_cart_shipping_method_full_label", [$this, 'shipping_method_icon'], 10, 2);
    }

    /**
     * @param string $label
     * @param WC_Shipping_Rate $method Shipping method rate data.
     * @return string
     */
    public function shipping_method_icon($label, $method)
    {
        if (false === strpos($label, 'yango-logo') && $this->id === $method->get_method_id()) {
            $label = '<img src="' . YGO_PLUGIN_URL . '/assets/yango-logo.jpg"> ' . $label;
        }
        return $label;
    }

    public function calculate_shipping($package = [])
    {
	    wc_get_logger()->debug( 'test', array( 'source' => 'Yandex GO' ) );

        if ($this->isFreeDelivery($package['contents_cost'])) {
            $this->addRate(0, $package['contents_cost']);

            return;
        }

        if ($this->isFixedPriceOn()) {
            $this->addRate($this->settings['fixed_price'], $package['contents_cost']);

            return;
        }

        try {
            $sum = $this->packageService->calculateSum($package);
            $priceManager = new PriceManager(!empty($this->settings['price_markup']) ? (int)$this->settings['price_markup'] : 0, 'yes' === $this->settings['discount_is_on'], !empty($this->settings['discount_size']) ? (int)$this->settings['discount_size'] : 0, !empty($this->settings['discount_from_price']) ? (int)$this->settings['discount_from_price'] : 0);
            $sum = $priceManager->prepare($sum, (float)$package['contents_cost']);

	        wc_get_logger()->debug( var_export($package, true), array( 'source' => 'Yandex GO' ) );
	        wc_get_logger()->debug( $sum, array( 'source' => 'Yandex GO' ) );

        } catch (ValidationError $exception) {
            //$this->addError('Произошла ошибка во время рассчета доставки: ' . $exception->getMessage());
            return;
        } catch (YandexApiException $exception) {
            //$this->addError('Произошла ошибка во время рассчета доставки: ' . $exception->getMessage());
            return;
        } catch (Exception $exception) {
            //$this->addError($exception->getMessage());
            return;
        }

        $this->addRate($sum, $package['contents_cost']);
    }

    public function is_enabled(): bool
    {
        if (!isset($this->settings['enabled']) || !isset($this->settings['token']) || empty($this->settings['token']) || ('Chile' !== $this->settings['country'] && empty($this->settings['geocode_token']))) {
            return false;
        }

        if ('yes' !== $this->settings['enabled']) {
            return false;
        }

        $warehouse = (new DefaultWarehouseFinder())->find();

        if (empty($warehouse)) {
            return false;
        }

        return true;
    }

    private function addRate(float $sum, float $packageCost): void
    {
        $isFreeDeliveryAllowed = $this->isFreeDeliveryAllowed();
        $isFree = ($sum == 0);

        $metaData = [
            'is_free' => $isFree,
            'is_free_allowed' => $isFreeDeliveryAllowed,
        ];

        if ($isFreeDeliveryAllowed && !$isFree) {
            $metaData['left_for_free'] = ($this->getOrderCostForFree() * 100 - $packageCost * 100) / 100;
        }

        $this->add_rate([
            'id' => $this->id,
            'label' => $this->getLabel(),
            'cost' => $sum,
            'meta_data' => $metaData,
        ]);
    }

    private function isFreeDelivery(int $cost): bool
    {
        if (!$this->isFreeDeliveryAllowed()) {
            return false;
        }

        return $cost >= $this->getOrderCostForFree();
    }

    private function isFreeDeliveryAllowed(): bool
    {
        return $this->settings['use_order_price_for_free'] === 'yes';
    }

    private function getOrderCostForFree(): int
    {
        return (int)$this->settings['order_price_for_free'];
    }

    private function isFixedPriceOn(): bool
    {
        return $this->settings['fixed_price_is_on'] === 'yes';
    }

    private function addError(string $message)
    {
        $messageType = 'error';

        if (!wc_has_notice($message, $messageType)) {
            wc_add_notice($message, $messageType);
        }
    }

    private function getLabel(): string
    {
        $key = $this->settings['payment_method_label'];

        $labels = [
            'delivery' => __('Яндекс Доставка', 'yandex-go-delivery'),
            'express_delivery' => __('Экспресс Яндекс Доставка', 'yandex-go-delivery'),
        ];

        return $labels[$key] ?? Constants::getPluginName();
    }
}
