<?php

defined( 'ABSPATH' ) || exit;

use WCYandexTaxiDeliveryPlugin\Helpers\CountryRelatedDataHelper;

$country = CountryRelatedDataHelper::getCountry();
$urls    = CountryRelatedDataHelper::getConnectUrlSource();
$url     = $urls[ $country ] ?? $urls['default'];
$tariffs = $tariffs ?? true;

if ( ! $tariffs ) {
	echo __( 'Из данной точки доставка невозможна. Удалите заказ из списка и продолжите отправку.', 'yandex-go-delivery' );
} else {
	echo __( 'Яндекс Доставка не подключена.', 'yandex-go-delivery' );
	?>
	&nbsp;<a href="<?php echo $url; ?>" target="_blank">
		<?php echo __( 'Подключить', 'yandex-go-delivery' ); ?>
	</a>
<?php } ?>