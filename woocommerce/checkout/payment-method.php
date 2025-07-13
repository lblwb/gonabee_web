<?php

/**
 * Output a single payment method
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment-method.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.5.0
 */

if (! defined('ABSPATH')) {
	exit;
}

$gateway = $args['gateway'];
$gateway_id = $args['gateway_id'];
?>
<li class="paymentMethodItem wc_payment_method payment_method_<?php echo esc_attr($gateway->id); ?> <?= $gateway->chosen ? '__Active' : "" ?>"
	data-payment-method="<?php echo esc_attr($gateway_id); ?>"
	onclick="selectPaymentMethod(this);">
	<div class="paymentMethodWrapper"
		style="display: flex; align-items: center; gap: 16px;">
		<div class="paymentMethodLogo">
			<div class="paymentMethodIBox"
				style="border-radius: 100px; border:solid 1px #ECECEC; min-width: 60px; min-height: 60px; max-width: 60px; max-height: 60px;">
				<div class="paymentMethodIBoxWrapper"
					style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; min-height: 60px;">

					<?php if ($gateway_id == 'cod') { ?>
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
							xmlns="http://www.w3.org/2000/svg">
							<path d="M7.00781 7.99975V5.99975C7.00781 3.23833 9.24639 0.999756 12.0078 0.999756C14.7692 0.999756 17.0078 3.23833 17.0078 5.99975V7.99975H20.0078C20.5601 7.99975 21.0078 8.44747 21.0078 8.99975V20.9998C21.0078 21.552 20.5601 21.9998 20.0078 21.9998H4.00781C3.45553 21.9998 3.00781 21.552 3.00781 20.9998V8.99975C3.00781 8.44747 3.45553 7.99975 4.00781 7.99975H7.00781ZM7.00781 9.99975H5.00781V19.9998H19.0078V9.99975H17.0078V11.9998H15.0078V9.99975H9.00781V11.9998H7.00781V9.99975ZM9.00781 7.99975H15.0078V5.99975C15.0078 4.3429 13.6646 2.99975 12.0078 2.99975C10.3509 2.99975 9.00781 4.3429 9.00781 5.99975V7.99975Z"
								fill="#F0C224" />
						</svg>
					<?php } else if ($gateway_id == 'ygo_card') { ?>
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M22.0039 9.99976V19.9998C22.0039 20.5521 21.5562 20.9998 21.0039 20.9998H3.00391C2.45163 20.9998 2.00391 20.5521 2.00391 19.9998V9.99976H22.0039ZM22.0039 7.99976H2.00391V3.99976C2.00391 3.44747 2.45163 2.99976 3.00391 2.99976H21.0039C21.5562 2.99976 22.0039 3.44747 22.0039 3.99976V7.99976ZM15.0039 15.9998V17.9998H19.0039V15.9998H15.0039Z" fill="#F0C224" />
						</svg>
					<?php } else if ($gateway_id == 'ygo_cash') { ?>
						<svg width="30" height="32" viewBox="0 0 30 32" fill="none"
							xmlns="http://www.w3.org/2000/svg">
							<path d="M14.742 29.5385C22.8838 29.5385 29.484 22.9261 29.484 14.7692C29.484 6.61241 22.8838 0 14.742 0C6.60022 0 0 6.61241 0 14.7692C0 22.9261 6.60022 29.5385 14.742 29.5385Z"
								fill="#FC3F1D" />
							<path d="M16.7089 8.36811H15.3575C12.8391 8.36811 11.5492 9.66042 11.5492 11.5066C11.5492 13.5989 12.4706 14.5835 14.3133 15.8758L15.8489 16.922L11.4263 23.5681H8.10938L12.102 17.6604C9.82928 15.9989 8.53935 14.3989 8.53935 11.7527C8.53935 8.36811 10.8735 6.09119 15.3575 6.09119H19.7801V23.6296H16.7089V8.36811Z"
								fill="white" />
						</svg>
					<?php } else if ($gateway_id == 'yookassa_widget') { ?>
						<svg width="38" height="22" viewBox="0 0 38 22" fill="none"
							xmlns="http://www.w3.org/2000/svg">
							<g clip-path="url(#clip0_3_15193)">
								<path d="M38.0964 9.26233V14.641H36.1654V10.868H34.306V14.641H32.375V9.26196H38.0964V9.26233Z"
									fill="black" />
								<path fill-rule="evenodd" clip-rule="evenodd"
									d="M28.4069 14.8332C30.1355 14.8332 31.4192 13.7791 31.4192 12.1809C31.4192 10.6342 30.4721 9.62985 28.8893 9.62985C28.1588 9.62985 27.556 9.8856 27.102 10.3269C27.2104 9.4148 27.9859 8.74894 28.8397 8.74894C29.0367 8.74894 30.5202 8.74582 30.5202 8.74582L31.3591 7.14917C31.3591 7.14917 29.4966 7.19134 28.6309 7.19134C26.6526 7.22562 25.3164 9.01404 25.3164 11.1862C25.3164 13.7167 26.6196 14.8332 28.4069 14.8332ZM28.4174 11.0441C29.0592 11.0441 29.5042 11.4636 29.5042 12.1808C29.5042 12.8263 29.1088 13.3581 28.4174 13.3596C27.756 13.3596 27.311 12.8668 27.311 12.1918C27.311 11.4744 27.756 11.0441 28.4174 11.0441Z"
									fill="black" />
								<path d="M23.7467 12.8408C23.7467 12.8408 23.2906 13.1022 22.6095 13.1517C21.8265 13.1748 21.1288 12.6829 21.1288 11.809C21.1288 10.9565 21.7445 10.4679 22.5898 10.4679C23.1082 10.4679 23.7939 10.8252 23.7939 10.8252C23.7939 10.8252 24.2956 9.90946 24.5555 9.4515C24.0796 9.09271 23.4457 8.896 22.7083 8.896C20.8475 8.896 19.4062 10.1027 19.4062 11.7982C19.4062 13.5153 20.7609 14.6939 22.7083 14.6583C23.2527 14.6382 24.0036 14.4481 24.4613 14.1556L23.7467 12.8408Z"
									fill="black" />
								<path d="M0 4.78882L2.67869 9.5509V12.4556L0.00313362 17.2084L0 4.78882Z"
									fill="#5B57A2" />
								<path d="M10.2852 7.81868L12.7952 6.28858L17.9321 6.28381L10.2852 10.943V7.81868Z"
									fill="#D90751" />
								<path d="M10.2723 4.76062L10.2865 11.0654L7.60156 9.42462V0L10.2723 4.76062Z"
									fill="#FAB718" />
								<path d="M17.9335 6.28302L12.7964 6.28778L10.2723 4.76062L7.60156 0L17.9335 6.28302Z"
									fill="#ED6F26" />
								<path d="M10.2865 17.2347V14.1758L7.60156 12.5662L7.60304 22.0001L10.2865 17.2347Z"
									fill="#63B22F" />
								<path d="M12.7888 15.7186L2.6785 9.5509L0 4.78882L17.9211 15.7124L12.7888 15.7186Z"
									fill="#1487C9" />
								<path d="M7.60156 21.9996L10.2847 17.2342L12.7884 15.718L17.9207 15.7118L7.60156 21.9996Z"
									fill="#017F36" />
								<path d="M0.00390625 17.208L7.62283 12.566L5.06138 11.0029L2.67946 12.4553L0.00390625 17.208Z"
									fill="#984995" />
							</g>
							<defs>
								<clipPath id="clip0_3_15193">
									<rect width="38" height="22" fill="white" />
								</clipPath>
							</defs>
						</svg>
					<?php } else { ?>
						<?php
						$icon_url = $gateway->icon;
						if ($icon_url) { ?>
							<img src="<?= $icon_url ?>" alt="<?= $gateway->get_title() ?>">
					<?php }
					} ?>
				</div>
			</div>
		</div>
		<div class="paymentMethodInfo">
			<div class="paymentMethodInfoHeading">
				<div class="paymentMethodInfoHeadingTitle" style="margin-bottom: 6px">
					<?= wp_kses_post($gateway->get_title()) ?>
				</div>
				<div class="paymentMethodInfoHeadingDesc" style="color: #1F1F1F60;">
					<?= $gateway->get_description() ?>
				</div>
			</div>
		</div>
	</div>
</li>