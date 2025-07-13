<?php
add_action('woocommerce_checkout_create_order_line_item', 'add_color_to_order', 10, 4);
function add_color_to_order($item, $cart_item_key, $values, $order)
{
	error_log("📝 [color_rel] Создания order");

	if (isset($values['color_rel']) && $values['color_rel']) {
		// Добавляем мета
		$item->add_meta_data('color_rel', $values['color_rel'], true);

		// Логируем успешное добавление
		error_log("🟢 [color_rel] добавлен: {$values['color_rel']} (cart_item_key: {$cart_item_key})");
	} else {
		// Логируем отсутствие поля
		error_log("⚠️ [color_rel] не найден или пуст (cart_item_key: {$cart_item_key})");
	}
}
