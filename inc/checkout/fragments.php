<?php
// В functions.php вашей темы или в файле плагина:
add_filter('woocommerce_update_order_review_fragments', 'billing_fields_fragment', 10, 1);
function billing_fields_fragment($fragments)
{
	// 1. Собираем нужный HTML (можно получить из буфера или через ob_start)
	ob_start();

	do_action('woocommerce_checkout_billing');

	$html = ob_get_clean();

	// 2. Добавляем запись в массив фрагментов: ключ — селектор, значение — HTML
	$fragments['.woocommerce-billing-fields'] = $html;

	return $fragments;
}


add_filter('woocommerce_update_order_review_fragments', 'shipping_fields_fragment', 10, 1);
function shipping_fields_fragment($fragments)
{
	// Получаем выбранные методы доставки по пакетам
	$chosen_shipping_by_packages = WC()->session->get('chosen_shipping_methods');
	if (empty($chosen_shipping_by_packages)) {
		return $fragments;
	}

	// Выбираем основной пакет и если это самовывоз возвращаем пустую строку
	$chosen_shipping = $chosen_shipping_by_packages[0];
	list($method_id, $instance_id) = explode(':', $chosen_shipping);

	// Если самовывоз то не показываем поля доставки
	if ($method_id === 'pickup_location') {
		$fragments['#shippingFields'] = '<div id="shippingFields"></div>';
		return $fragments;
	}

	$rate = false;
	$rates = null;
	if ($method_id === 'official_cdek') {

		// 1) Берём первый пакет (обычно он один)
		$packages = WC()->shipping()->get_packages();
		$package  = reset($packages);
		$rates = $package['rates'];

		if (isset($rates[$chosen_shipping])) {
			$rate = $rates[$chosen_shipping];
		} else {
			// тариф не найден
		}
	}

	// 1. Собираем нужный HTML (можно получить из буфера или через ob_start)
	ob_start();

	echo '<div id="shippingFields">';

	$checkoutMap = new Cdek\UI\CheckoutMap();
	$checkoutMap($rate);

	// Если карта не отображается 
	if (!Cdek\Model\Tariff::isToOffice($instance_id)) {
		do_action('woocommerce_checkout_shipping');
	}

	echo '</div>';

	$html = ob_get_clean();

	// 2. Добавляем запись в массив фрагментов: ключ — селектор, значение — HTML
	$fragments['#shippingFields'] = $html;

	return $fragments;
}
