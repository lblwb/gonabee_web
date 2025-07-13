<?php

/**
 * Checkout Payment Section
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.8.0
 */

defined('ABSPATH') || exit;

if (! wp_doing_ajax()) {
	do_action('woocommerce_review_order_before_payment');
}

$chosen_id = WC()->session->get('chosen_payment_method');
if ($payment_method) {
	$chosen_id = $payment_method;
}

if ((empty($chosen_id) || $chosen_id == 'undefined') && !empty($available_gateways)) {
	// Получить первый доступный способ оплаты
	$first_gateway = current($available_gateways);
	$chosen_id = $first_gateway->id;
}
?>
<?php if (WC()->cart && WC()->cart->needs_payment()) : ?>
	<div class="payment-methods-block woocommerce-checkout-payment">
		<input
			type="hidden"
			name="payment_method"
			value="<?= $chosen_id ?>">
		<div class="cartPageHeadingTitle" style="margin-bottom: 24px">
			<h1>Способ оплаты</h1>
		</div>

		<ul id="payment" class="payment-methods-grid">
			<?php
			if (! empty($available_gateways)) {
				foreach ($available_gateways as $gateway) {
					wc_get_template('checkout/payment-method.php', array('gateway' => $gateway, 'gateway_id' => $gateway->id, 'active_class' => $gateway->id === $chosen_id ? '__active' : ''));
				}
			} else { ?>
				<div class="paymentMethodItem">
					<div class="paymentMethodWrapper">
						<div class="paymentMethodInfo">
							<div class="paymentMethodInfoHeading">
								<strong>Нет доступных способов оплаты</strong>
								<div class="paymentMethodInfoHeadingDesc" style="color: #1F1F1F60;">
									<?php esc_html_e('Пожалуйста, заполните данные выше, чтобы увидеть доступные способы оплаты.', 'woocommerce'); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
		</ul>
	</div>
	<div class="form-row place-order">
		<noscript>
			<?php
			/* translators: $1 and $2 opening and closing emphasis tags respectively */
			printf(esc_html__('Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce'), '<em>', '</em>');
			?>
			<br /><button type="submit" class="button alt<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e('Update totals', 'woocommerce'); ?>"><?php esc_html_e('Update totals', 'woocommerce'); ?></button>
		</noscript>

		<?php do_action('woocommerce_review_order_after_submit'); ?>

		<?php wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce'); ?>
	</div>
<?php endif ?>

<?php
if (! wp_doing_ajax()) {
	do_action('woocommerce_review_order_after_payment');
}
