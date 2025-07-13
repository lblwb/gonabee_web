<?php
add_action('woocommerce_checkout_create_order', 'add_shipping_raw_to_order', 9999, 2);

function add_shipping_raw_to_order($order, $data)
{
	error_log("🚀 [shipping_raw] Добавляем в объект заказа кастомное свойство");
	error_log(print_r($data, true));
	error_log(print_r($order, true));

	// Получаем выбранный метод доставки
	$method_slug = $data['shipping_method'] ?? '';
	if (!$method_slug) {
		error_log('🛑 [shipping_raw] Shipping method not found');
		return;
	}
	error_log("📋 [shipping_raw] method_slug");
	error_log(print_r($method_slug, true));


	// Разделяем на ID и instance ID
	list($method_id, $instance_id) = explode(':', $method_slug);
	error_log("📋 [shipping_raw] method_id, instance_id");
	error_log(print_r(array($method_id, $instance_id), true));

	$description = '';

	// Обработка CDEK
	if (strpos($method_id, 'official_cdek') !== false) {
		error_log('🐞 [shipping_raw] Метод доставки = СДЭК');
		$description .= 'CDEK:';

		if (class_exists('Cdek\\Model\\Tariff') && Cdek\Model\Tariff::isToOffice($instance_id)) {
			error_log('🐞 [shipping_raw] Доставка до пункта выдачи');
			$description .= ' Будет доставлен в пункт выдачи по адресу ';

			$api = new Cdek\CdekApi();
			$selectedOffice = Cdek\Helpers\CheckoutHelper::getCurrentValue('office_code');
			try {
				$officeInfo = empty($selectedOffice) ? null : $api->officeGet($selectedOffice);
			} catch (Throwable $e) {
				$officeInfo = null;
			}

			error_log('🐞 [shipping_raw] officeInfo');
			error_log(print_r($officeInfo, true));

			$description .= esc_html($officeInfo['location']['city']) . ' ';
			$description .= esc_html($officeInfo['location']['address']);
		} else {
			error_log('🐞 [shipping_raw] Доставка до двери');
			$description .= ' Будет доставлен по адресу';
			$description .= $order->get_formatted_shipping_address();
		}

		// Обработка самовывоза
	} elseif (strpos($method_id, 'pickup_location') !== false) {
		error_log('🐞 [shipping_raw] Самовывоз');
		$description .= 'Вы выбрали самовывоз.';

		$packages = WC()->shipping()->get_packages();
		$package  = reset($packages);
		$rates = $package['rates'];
		$rate = false;

		error_log('🐞 [shipping_raw] rates');
		error_log(print_r($rates, true));

		if (isset($rates[$method_slug])) {
			$rate = $rates[$method_slug];
		}

		error_log('🐞 [shipping_raw] rate');
		error_log(print_r($rate, true));

		if ($rate) {
			// Получаем весь массив meta_data
			$all_meta = $rate->get_meta_data();

			// Забираем из него нужный ключ, если он есть
			if (! empty($all_meta['pickup_address'])) {
				$address = $all_meta['pickup_address'];
				error_log('✅ [shipping_raw] Адрес имеется, добавляем');
				$description .= ' Заберите заказ по адресу ' . $address;
			} else {
				error_log('❌ [shipping_raw] В meta_data нет ключа pickup_address');
			}
		}

		// Остальные методы
	} else {
		error_log('ℹ️ [shipping_raw] Неизвестный метод доставки');
		$description .= 'Ваш заказ будет доставлен согласно выбранному методу.';
	}

	// Сохраняем мета-данные
	$order->update_meta_data('shipping_raw', $description);
}
