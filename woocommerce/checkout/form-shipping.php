<?php

/**
 * Checkout shipping information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-shipping.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 * @global WC_Checkout $checkout
 */

defined('ABSPATH') || exit;
?>

<div>
    <div class="separate" style="margin: 30px 0">
        <hr>
    </div>

    <div class="cartPageHeadingTitle" style="margin-bottom: 24px">
        <h1>Введите адресс доставки</h1>
    </div>

    <div class="woocommerce-shipping-fields">
        <?php if (true === WC()->cart->needs_shipping_address()) : ?>
            <div class="shipping_address">

                <?php do_action('woocommerce_before_checkout_shipping_form', $checkout); ?>

                <div class="woocommerce-shipping-fields__field-wrapper">
                    <?php
                    $fields = $checkout->get_checkout_fields('shipping');

                    foreach ($fields as $key => $field) {
                        woocommerce_form_field($key, $field, $checkout->get_value($key));
                    }
                    ?>
                </div>

                <?php do_action('woocommerce_after_checkout_shipping_form', $checkout); ?>

            </div>

        <?php endif; ?>
    </div>
    <div class="woocommerce-additional-fields">
        <?php do_action('woocommerce_before_order_notes', $checkout); ?>

        <?php if (apply_filters('woocommerce_enable_order_notes_field', 'yes' === get_option('woocommerce_enable_order_comments', 'yes'))) : ?>

            <?php if (! WC()->cart->needs_shipping() || wc_ship_to_billing_address_only()) : ?>

                <h3><?php esc_html_e('Additional information', 'woocommerce'); ?></h3>

            <?php endif; ?>

            <div class="woocommerce-additional-fields__field-wrapper">
                <?php foreach ($checkout->get_checkout_fields('order') as $key => $field) : ?>
                    <?php woocommerce_form_field($key, $field, $checkout->get_value($key)); ?>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>

        <?php do_action('woocommerce_after_order_notes', $checkout); ?>
    </div>
</div>