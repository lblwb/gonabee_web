<?php

namespace WCYandexTaxiDeliveryPlugin\Json;

defined('ABSPATH') || exit;

/**
 * Class JsonResponse
 *
 * @package WCYandexTaxiDeliveryPlugin\Json
 */
class JsonResponse
{
    public function getString(array $params): string
    {
        return json_encode($params, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
