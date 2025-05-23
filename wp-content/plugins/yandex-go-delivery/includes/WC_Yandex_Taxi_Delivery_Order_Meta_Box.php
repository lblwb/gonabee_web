<?php

defined('ABSPATH') || exit;

use WCYandexTaxiDeliveryPlugin\Constants;
use YandexTaxi\Delivery\Entities\Claim\Status;
use WCYandexTaxiDeliveryPlugin\Repositories\ClaimMetaRepository;
use WCYandexTaxiDeliveryPlugin\StatusStorage;

/**
 * Class WC_Yandex_Taxi_Delivery_Order_Meta_Box
 */
class WC_Yandex_Taxi_Delivery_Order_Meta_Box
{
    public static function render($post)
    {
        if ($post->post_type !== 'shop_order') {
            return;
        }

        $repository = new ClaimMetaRepository();
        $statusStorage = new StatusStorage();

        $result = $repository->getMetaForOrder($post->ID);

        if (empty($result)) {
            _e('Заказ не был отправлен в ', 'yandex-go-delivery') . Constants::getToPluginName();
            return;
        }

        $claimId = $result['claimId'];

        $status = $statusStorage->getShipmentStatus($claimId);
        $routePointStatus = $statusStorage->getOrderStatus($post->ID, $claimId);

        $phone = $result['driverPhone'];

        if (!is_null($status)) {
            $status = Status::fromCode($status);

            if ($status->equals(Status::deliveredFinish())) {
                $phone = null;
            }
        }

        $statusLabel = is_null($status) ? null : __('status_label_' . $status->getCode(), 'yandex-go-delivery');
        $routePointStatusLabel = is_null($routePointStatus) ? null : __('route_point_status_label_' . $routePointStatus, 'yandex-go-delivery');

        $fields = [
            Constants::getDeliveryIdMetaParamName() => $claimId,
            Constants::getTariffMetaParamName() => $result['tariff'],
            Constants::getShippingStatusMetaParamName() => $statusLabel,
            Constants::getShippingRoutePointMetaParamName() => $routePointStatusLabel,
            Constants::getShippingSlotMetaParamName() => $result['shippingSlot'],
            Constants::getDriverMetaParamName() => $result['driver'],
            Constants::getDriverPhoneMetaParamName() => $phone,
            Constants::getDriverPhoneMetaParamName() => $phone,
            //  Uncomment when feature is implemented
            //  Constants::ACT_URL_LABEL => $result['actUrl'],
        ];

        $order = wc_get_order($post->ID);

        foreach ($order->get_items('shipping') as $shipping) {
            $shippingMethodId = $shipping->get_method_id();
            $shippingMethodTotal = $shipping->get_total();
        }

        if (isset($shippingMethodId, $shippingMethodTotal) && $shippingMethodId === YGO_PLUGIN_ID) {
            $fields[Constants::getDeliveryPriceFromCart()] = wc_price($shippingMethodTotal);
        }

        if (!empty($result['price'])) {
            $fields[Constants::getDeliveryPriceMetaParamName()] = self::getPriceLine(
                $result['price'],
                $repository->getOrdersByClaimId($claimId)
            );
        }

        self::renderHtml($fields);
    }

    private static function renderHtml(array $fields): void
    {
        $html = '<table class="yandex-taxi-delivery_meta-box"><tbody>';

        foreach ($fields as $label => $value) {
            $value = empty($value) ? '–' : $value;

            $html .= "<tr>
	            <td><span class='label'>{$label}</span>
		        <td><span>{$value}</span></td>
	        </tr>";
        }

        $html .= '</tbody></table>';

        echo $html;
    }

    private static function getPriceLine(int $rawPrice, array $orderIds): string
    {
        $price = wc_price($rawPrice / 100);

        $orderIds = array_filter($orderIds);

        if (count($orderIds) === 1) {
            return $price;
        }

        $ordersPart = implode(', ', array_map(function (int $id) {
            return self::getOrderLink($id);
        }, $orderIds));

        return sprintf(esc_html__('%1$s (заявка по мультиточкам, заказы: %2$s)', 'yandex-go-delivery'), $price, $ordersPart);
    }

    private static function getOrderLink(int $id): string
    {
        $url = admin_url("post.php?post={$id}&action=edit");

        return "<a href='{$url}' target='_blank'>№{$id}</a>";
    }
}
