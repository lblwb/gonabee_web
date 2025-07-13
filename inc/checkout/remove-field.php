<?php

/***
 * @woocommerce
 */
add_action('woocommerce_init', function () {
	// если в сессии ещё нет shipping_city — запишем СПб
	if (WC()->session && ! WC()->session->get('shipping_city')) {
		WC()->session->set('shipping_city', 'Санкт-Петербург');
	}
	// чтобы объект Customer тоже «знал» город
	if (WC()->customer && ! WC()->customer->get_shipping_city()) {
		WC()->customer->set_shipping_city('Санкт-Петербург');
	}
});

add_filter('woocommerce_checkout_fields', function ($fields) {

	// Убираем ненужные поля
	unset($fields['billing']['billing_company']);
	unset($fields['billing']['billing_country']);
	unset($fields['billing']['billing_address_1']);
	unset($fields['billing']['billing_address_2']);
	unset($fields['billing']['billing_city']);
	unset($fields['billing']['billing_state']);
	unset($fields['billing']['billing_postcode']);
	unset($fields['billing']['passport_series']);
	unset($fields['billing']['passport_number']);
	unset($fields['billing']['passport_number']);
	unset($fields['billing']['passport_date_of_issue']);
	unset($fields['billing']['passport_organization']);
	unset($fields['billing']['tin']);
	unset($fields['billing']['tin']);
	unset($fields['billing']['passport_date_of_birth']);

	// SHIPPING
	unset($fields['shipping']['shipping_first_name']);
	unset($fields['shipping']['shipping_last_name']);
	unset($fields['shipping']['shipping_address_2']);
	unset($fields['shipping']['shipping_state']);
	unset($fields['shipping']['shipping_country']);
	unset($fields['order']['order_comments']);


	// Переопределяем порядок и placeholders
	$fields['billing']['billing_last_name']['priority'] = 10;
	$fields['billing']['billing_last_name']['placeholder'] = 'Введите фамилию';
	$fields['billing']['billing_first_name']['placeholder'] = 'Введите имя';
	$fields['billing']['billing_first_name']['priority'] = 25;
	$fields['billing']['billing_phone']['label'] = 'Номер телефона';
	$fields['billing']['billing_phone']['required'] = true;
	$fields['billing']['billing_phone']['placeholder'] = '+7 (999) 999-99-99';
	$fields['billing']['billing_email']['placeholder'] = 'ivanov@gmail.com';
	$fields['shipping']['shipping_city']['default'] = 'Санкт-Петербург';
	$fields['shipping']['shipping_city']['priority'] = 0;
	$fields['shipping']['shipping_city']['label'] = 'Город';


	// Добавляем Отчество
	$fields['billing']['billing_middle_name'] = array(
		'type' => 'text',
		'label' => 'Отчество',
		'required' => false,
		'class' => array('form-row-wide'),
		'priority' => 45,
		'placeholder' => 'Введите отчество',
	);

	$fields['billing']['billing_email']['label'] = 'Адрес электронной почты';
	//
	//    echo var_dump($fields);

	return $fields;
}, 2000);
