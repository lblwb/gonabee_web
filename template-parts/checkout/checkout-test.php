<?php
if (isset($args['checkout'])) {
	$checkout = $args['checkout'];
} elseif (! isset($checkout) || ! is_object($checkout)) {
	$checkout = WC()->checkout();
}

wc_print_notices();

// Хук перед всем checkout‐формой
do_action('woocommerce_before_checkout_form', $checkout);
?>
<form name="checkout" method="post" class="checkout woocommerce-checkout"
	action="<?php echo esc_url(wc_get_checkout_url()); ?>"
	enctype="multipart/form-data">

	<?php if ($checkout->get_checkout_fields()) : ?>

		<?php
		// Перед выводом полей клиента (billing/shipping)
		do_action('woocommerce_checkout_before_customer_details');
		?>

		<div class="col2-set" id="customer_details">

			<div class="col-1">
				<?php
				// Блок полей Billing
				do_action('woocommerce_checkout_billing');
				?>
			</div>

			<div class="col-2">
				<?php do_action('woocommerce_review_order_before_shipping'); ?>

				test
				<?php wc_cart_totals_shipping_html(); ?>

				<?php do_action('woocommerce_review_order_after_shipping'); ?>

				<?php if (WC()->cart->needs_shipping_address() && apply_filters('woocommerce_checkout_show_shipping', true)) : ?>
					<div class="woocommerce-shipping-fields">
						<!-- 1) Адрес и калькулятор доставки -->
						Список полей Shipping
						<?php woocommerce_shipping_calculator(); ?>
					</div>
				<?php endif; ?>

				<?php do_action('woocommerce_checkout_shipping'); ?>
				?>
			</div>

		</div>

		<?php
		// После блока Customer Details
		do_action('woocommerce_checkout_after_customer_details');
		?>

	<?php endif; ?>

	<?php
	// Перед обзором заказа
	do_action('woocommerce_checkout_before_order_review');
	?>

	<div id="order_review" class="woocommerce-checkout-review-order">
		<?php
		// Заголовок «Your order»
		// do_action('woocommerce_checkout_before_order_review_heading');
		?>
		<h3 id="order_review_heading"><?php esc_html_e('Your order', 'woocommerce'); ?></h3>
		<div id="order_review" class="woocommerce-checkout-review-order">
			<?php
			// Сам обзор заказа: товары, методы доставки, итоги

			do_action('woocommerce_checkout_order_review');
			?>
		</div>

	</div>

	<?php
	// После обзора заказа
	do_action('woocommerce_checkout_after_order_review');
	?>

	<div id="payment" class="woocommerce-checkout-payment">
		<?php
		// Форма оплаты (кнопка Place order + hidden поля)
		woocommerce_checkout_payment();
		?>
	</div>

</form>

<?php
// Хук после всей checkout‐формы
do_action('woocommerce_after_checkout_form', $checkout);
?>