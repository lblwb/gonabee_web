<?php
add_filter('woocommerce_add_success', function ($message) {
	if (strpos($message, 'Клиент соответствует зоне') !== false) {
		return '';
	}
	return $message;
}, 9999);
