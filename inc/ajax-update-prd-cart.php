<?php

add_action('wp_ajax_update_prd_cart', 'ajax_update_prd_card');
add_action('wp_ajax_nopriv_update_prd_cart', 'ajax_update_prd_card');

function ajax_update_prd_card()
{
	try {
		check_ajax_referer('update_prd_card_nonce', 'nonce');

		// Получаем ключ товара из корзины
		$cart_item_key = isset($_POST['cartItemKey']) 
			? sanitize_text_field($_POST['cartItemKey']) 
			: false;
		if (!$cart_item_key) {
				wp_send_json_error(['message' => 'Не передан ключ товара в корзине']);
		}
			
		// Проверяем есть ли товар в корзине
		$cart = WC()->cart;
		$cart_contents = $cart->get_cart();
		$product_position = array_search($cart_item_key, array_keys($cart_contents));
		if ($product_position === false || !isset($cart_contents[$cart_item_key])) {
			wp_send_json_error(['message' => 'Товар не найден в корзине']);
		}

		// Получаем данные товара
		$cart_item = $cart_contents[$cart_item_key];
		$product_id = $cart_item['product_id'];
		$product_data = [];

		// Сохраняем порядок ключей до удаления
		$old_keys = array_keys($cart_contents);
		$old_key = $cart_item_key;

		// Удаляем товар из корзины
		$cart->remove_cart_item($cart_item_key);

		// Добавляем новые атрибуты
		$variation = $cart_item['variation'] ?: [];

		$prd_size = isset($_POST['prdSize']) ? sanitize_text_field($_POST['prdSize']) : '';
		if($prd_size) {
			$variation['attribute_pa_size'] = $prd_size;
		}

		$prd_color = isset($_POST['prdColor']) ? sanitize_text_field($_POST['prdColor']) : '';
		if($prd_color) {
			$product_data['color_rel'] = $prd_color;
		}

		// Добавляем товар обратно в корзину с новыми атрибутами
		$added = $cart->add_to_cart(
			$product_id, 
			$cart_item['quantity'], 
			$cart_item['variation_id'] ?: 0, 
			$variation,
			$product_data
		);

		if(!$added) {
			wp_send_json_error(array('message' => __('Не удалось обновить товар в корзине.', 'not_product_cart')));
		}

		// Получаем новые ключи корзины
		$new_cart = $cart->get_cart();
		$new_keys = array_keys($new_cart);
		$new_key = $added;

		// Перестраиваем порядок корзины: новый товар на место старого
		$reordered = [];
		foreach ($old_keys as $key) {
			if ($key === $old_key) {
				// на место старого вставляем новый
				$reordered[$new_key] = $new_cart[$new_key];
			} elseif (isset($new_cart[$key])) {
				// переносим все остальные, которые ещё есть
				$reordered[$key] = $new_cart[$key];
			}
		}
		// добавляем «хвост» — всё, что осталось (новые товары или товары, которые были дальше в списке)
		foreach ($new_cart as $key => $item) {
			if (!isset($reordered[$key])) {
				$reordered[$key] = $item;
			}
		}

		// Заменяем внутренний массив корзины и пересчитываем
		$cart->cart_contents = $reordered;
		$cart->cart_contents_count = count($reordered);
		$cart->set_cart_contents($reordered);
		$cart->calculate_totals();

		wp_send_json_success(['new_key' => $new_key, 'variation' => $variation]);

	} catch (Throwable $e) {
			error_log('Ошибка ajax_change_prd_card: ' . $e->getMessage());
			wp_send_json_error(['message' => 'Ошибка сервера: ' . $e->getMessage()]);
	}

	wp_die();
}