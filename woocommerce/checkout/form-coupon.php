<?php
$isPromocodeEntered = !empty(WC()->cart->get_applied_coupons());

$title = $isPromocodeEntered ? 'Промокод введен' : 'Введите промокод';
$placeholder = $isPromocodeEntered ? WC()->cart->get_applied_coupons()[0] : 'Промокод или сертификат';
$handleBtn = $isPromocodeEntered ? '' : 'onclick="applyPromocode(this)"';
$disableClass = $isPromocodeEntered ? 'disable' : '';
?>

<div class="checkoutBodyRow checkout_coupon woocommerce-form-coupon" method="post" id="woocommerce-checkout-form-coupon" style="display: unset !important;">
	<div class="promocodeWrapper <?= $disableClass ?>"
		data-action="apply_cart_coupon"
		data-nonce="<?= wp_create_nonce('apply_cart_coupon') ?>"
		data-ajax-url="<?= admin_url('admin-ajax.php'); ?>">
		<div class="promocodeBlock" role="region" aria-label="Promocode Section">
			<button class="promocodeBlock__header" aria-expanded="false" aria-controls="promocodeContent" onclick="togglePromocode(this)">
				<span class="promocodeBlock__headerText"><?= $title ?></span>
				<span class="promocodeBlock__arrow"></span>
			</button>
			<div class="promocodeBlock__content" id="promocodeContent" aria-hidden="true" <?= $isPromocodeEntered ? '' : 'style="display: none;"' ?>>
				<input type="text" placeholder="<?= $placeholder ?>" aria-label="Введите промокод или сертификат" class="promocodeBlock__input" />
				<button class="promocodeBlock__applyButton" <?= $handleBtn ?>>
					<!-- <span class="promocodeBlock__loadingSpinner"></span> -->
					<span>Применить</span>
				</button>
				<p class="promocodeBlock__message"></p>
			</div>
		</div>
	</div>
</div>