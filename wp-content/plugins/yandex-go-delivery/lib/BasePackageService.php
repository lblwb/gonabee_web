<?php

namespace WCYandexTaxiDeliveryPlugin;

defined('ABSPATH') || exit;

use WCYandexTaxiDeliveryPlugin\Helpers\CountryRelatedDataHelper;
use WCYandexTaxiDeliveryPlugin\Repositories\ClaimMetaRepository;
use WCYandexTaxiDeliveryPlugin\Wrappers\ProductWrapper;
use YandexTaxi\Delivery\Entities\Claim\Claim;
use YandexTaxi\Delivery\Entities\ClaimItem\ClaimItem;
use YandexTaxi\Delivery\Entities\ClaimItem\Money;
use YandexTaxi\Delivery\Entities\ClaimItem\Size;
use WC_Product;

/**
 * Class BasePackageService
 *
 * @package WCYandexTaxiDeliveryPlugin
 */
class BasePackageService
{
    protected function saveOrderInfo(int $orderId, Claim $claim, string $tariffLabel)
    {
        $order = wc_get_order($orderId);

        if (empty($order)) {
            return;
        }

        $metaRepository = new ClaimMetaRepository();
        $metaRepository->storeClaimForOrder($claim->getId(), [$orderId]);
        $metaRepository->updateTariff($claim->getId(), $tariffLabel);
        $metaRepository->updatePrice($claim->getId(), $claim->getPrice()->getValue() * 100);

        $statusStorage = new StatusStorage();
        $statusStorage->storeShipmentStatus($claim->getId(), $claim->getStatus());
    }

    protected function prepareItem(WC_Product $product, ?string $orderId, int $quantity): ClaimItem
    {
        $wrappedProduct = new ProductWrapper($product);

        $price = (get_woocommerce_currency() === CountryRelatedDataHelper::getCurrency()) ? $wrappedProduct->getPrice() : 0;
        $sku   = $wrappedProduct->getSku() ? $wrappedProduct->getSku() : "Product-{$wrappedProduct->getId()}";

        return new ClaimItem(
            $wrappedProduct->getId(),
            $sku,
            $orderId,
            $wrappedProduct->getTitle(),
            new Size($wrappedProduct->getWidth(), $wrappedProduct->getLength(), $wrappedProduct->getHeight()),
            new Money($price, $wrappedProduct->getCurrency()),
            $wrappedProduct->getWeight(),
            $quantity
        );
    }
}
