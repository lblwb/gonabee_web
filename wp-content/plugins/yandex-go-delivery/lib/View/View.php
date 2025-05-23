<?php

namespace WCYandexTaxiDeliveryPlugin\View;

defined('ABSPATH') || exit;

/**
 * Class View
 *
 * @package WCYandexTaxiDeliveryPlugin\View
 */
class View
{
    public function buildHtml(string $viewPath, array $params = [])
    {
        extract($params);

        ob_start();

        require($viewPath);

        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }
}
