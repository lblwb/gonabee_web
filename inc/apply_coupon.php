<?php
add_action('wp_ajax_apply_cart_coupon', 'handle_apply_cart_coupon');
add_action('wp_ajax_nopriv_apply_cart_coupon', 'handle_apply_cart_coupon');

function handle_apply_cart_coupon()
{

    // Get coupon code from POST
    $coupon_code = isset($_GET['coupon_code']) ? sanitize_text_field($_GET['coupon_code']) : '';
    $response = array('success' => false);

    if (empty($coupon_code)) {
        $response['data'] = array('message' => 'Промокод не указан');
        wp_send_json($response);
        wp_die();
    }

    //    wp_send_json(WC()->cart->get_applied_coupons());

    // Apply coupon using WooCommerce
    $applied = WC()->cart->apply_coupon($coupon_code);
    if ($applied) {
        wc_clear_notices(); // Clear existing notices to avoid duplicates
        wc_add_notice('Промокод успешно применен!', 'success');

        $discount_raw = WC()->cart->get_coupon_discount_amount($coupon_code, false);
        $discount_formatted = wc_price($discount_raw);

        $response['success'] = true;
        $response['data'] = array(
            'message' => 'Промокод успешно применен!',
            'discount_raw' => $discount_raw,
            'discount_formatted' => $discount_formatted
        );
    } else {
        //        wc_get_notices('error') ? implode(', ', wc_get_notices('error')) :
        $error_message = 'Неверный промокод';
        wc_clear_notices();
        $response['data'] = array('message' => $error_message);
    }

    wp_send_json($response);
    wp_die();
}
