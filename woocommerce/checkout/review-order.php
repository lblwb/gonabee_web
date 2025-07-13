<?php
$chosen_methods_by_packages = WC()->session->get('chosen_shipping_methods');
$chosen_shipping_slug = $chosen_shipping_id = '';
if (is_array($chosen_methods_by_packages) && !empty($chosen_methods_by_packages[0])) {
	$parts = explode(':', $chosen_methods_by_packages[0], 2);
	$chosen_shipping_slug = $parts[0];
	$chosen_shipping_id = isset($parts[1]) ? $parts[1] : '';
}

$all_methods = WC()->shipping()->get_shipping_methods(true);

$chosen_method = false;
if (isset($all_methods[$chosen_shipping_slug])) {
	$chosen_method = $all_methods[$chosen_shipping_slug];
}

// Получаем пакеты
WC()->cart->calculate_shipping();
$package = WC()->shipping()->get_packages()[0];

$coupons = WC()->cart->get_coupons();
$code = null;
$coupon = null;
if (!empty($coupons) && is_array($coupons)) {
	$firstKey = array_key_first($coupons);
	if ($firstKey !== null && isset($coupons[$firstKey])) {
		$code = $firstKey;
		$coupon = $coupons[$firstKey];
	}
}

// // Получаем текущий WC_Shipping_Rate
// $rate = $packages['rates'][$chosen_method_id];
// $shipping_title = $rate->get_label();
?>

<div class="cartPageBodySide woocommerce-checkout-review-order-table
">
	<div class="cartPageBodySideCheckoutBlock">
		<div class="checkoutBlockHeading">
			<div class="checkoutBlockHeadingTitle">Ваш заказ</div>
		</div>
		<div class="checkoutBlockBody">
			<div class="checkoutBlockBodyRow">
				<div class="checkoutBlockBodyRowWrap">
					<div class="checkoutBlockBodyRowItem">
						Товары, <?php echo WC()->cart->get_cart_contents_count(); ?> шт
					</div>
					<div class="checkoutBlockBodyRowItem"><?php echo WC()->cart->get_cart_subtotal(); ?></div>
				</div>
			</div>
			<?php if ($chosen_method) : ?>
				<div class="checkoutBlockBodyRow">
					<div class="checkoutBlockBodyRowWrap">
						<div class="checkoutBlockBodyRowItem" style="font-weight: 600;">Доставка:
						</div>
						<div class="checkoutBlockBodyRowItem">
							<?php
							if ($chosen_method->id === 'pickup_location') {
								echo 'Самовывоз';
							} else {
								echo $chosen_method->title;
							}
							?>
						</div>
					</div>
				</div>
				<div class="checkoutBlockBodyRow">
					<div class="checkoutBlockBodyRowWrap">
						<div class="checkoutBlockBodyRowItem" style="font-weight: 600;">Стоимость доставки:
						</div>
						<div class="checkoutBlockBodyRowItem">
							<?= WC()->cart->get_shipping_total() ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<?php if ($code && $coupon): ?>
				<?php if ($coupon->get_discount_type() === 'percent') : ?>
					<div class="checkoutBlockBodyRow">
						<div class="checkoutBlockBodyRowWrap">
							<div class="checkoutBlockBodyRowItem" style="font-weight: 600;">Процент скидки:
							</div>

							<div class="checkoutBlockBodyRowItem">
								<?= format_coupon_amount($coupon->get_amount(), $coupon->get_discount_type()) ?>
							</div>

						</div>
					</div>
				<?php endif; ?>
				<div class="checkoutBlockBodyRow">
					<div class="checkoutBlockBodyRowWrap">
						<div class="checkoutBlockBodyRowItem" style="font-weight: 600;">Скидка:
						</div>

						<div class="checkoutBlockBodyRowItem">
							<?= wc_price(WC()->cart->get_coupon_discount_amount($code)) ?>
						</div>

					</div>
				</div>
			<?php endif; ?>
			<div class="checkoutBlockBodyRow">
				<div class="checkoutBlockBodyRowWrap">
					<div class="checkoutBlockBodyRowItem" style="font-weight: 600;">Итоговая сумма:
					</div>

					<div class="checkoutBlockBodyRowItem">
						<strong><?php echo wc_price(WC()->cart->get_total('edit')); ?></strong>
					</div>

				</div>
			</div>
			<div class="checkoutBlockBodyRow">
				<?php
				if (is_user_logged_in() || WC()->checkout()->is_registration_enabled() || !WC()->checkout()->is_registration_required()) {
					wc_get_template(
						'checkout/form-coupon.php',
						array(
							'checkout' => WC()->checkout(),
						)
					);
				}
				?>
			</div>
			<div class="checkoutBlockFooter">
				<?php do_action('woocommerce_review_order_before_submit'); ?>

				<button type="submit" class="checkout-button">К оформлению</button>

				<div class="delivery-note">

					<?php wc_get_template('checkout/terms.php'); ?>

				</div>


			</div>
		</div>
	</div>
</div>