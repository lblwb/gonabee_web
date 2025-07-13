<?php
add_filter('woocommerce_cart_needs_shipping_address', 'my_disable_shipping_address_for_pickup', 10, 1);
function my_disable_shipping_address_for_pickup($needs_address)
{
	// получаем выбранный метод
	$chosen = WC()->session->get('chosen_shipping_methods', array());
	$method = isset($chosen[0]) ? $chosen[0] : '';

	list($method_id, $instance_id) = explode(':', $method, 2);

	// если это pickup_location (самовывоз) — адрес не нужен
	if ($method_id === 'pickup_location') {
		return false;
	}

	if ($method_id === 'official_cdek' && Cdek\Model\Tariff::isToOffice($instance_id)) {
		return false;
	}

	return $needs_address;
}
