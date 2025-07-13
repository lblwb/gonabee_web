<?php
add_action('init', 'gkvso_remove_all_coupon_forms');
function gkvso_remove_all_coupon_forms()
{
	// Стандартная форма перед чекаутом
	remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
	// Стандартная форма внутри «Ваш заказ»
	remove_action('woocommerce_review_order_after_cart_contents', 'woocommerce_checkout_coupon_form', 10);
	// На всякий случай — если где-то ещё навешена
	remove_action('woocommerce_checkout_order_review', 'woocommerce_checkout_coupon_form', 10);
}
