<?php

defined('ABSPATH') || exit;

use WCYandexTaxiDeliveryPlugin\Helpers\CountryRelatedDataHelper;

$country = CountryRelatedDataHelper::getCountry();
$urls = CountryRelatedDataHelper::getConnectUrlSource();
$url = $urls[$country] ?? $urls['default'];
?>

<p><?php echo __('Ваш Токен API Яндекс Доставки не работает.', 'yandex-go-delivery') ?></p>
<p><?php echo __('Возможно, у вас на счете недостаточно средств или не подключена доставка Яндекс Go.', 'yandex-go-delivery') ?></p>