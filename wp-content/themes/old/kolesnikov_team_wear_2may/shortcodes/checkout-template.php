<?php

//echo var_dump(WC());

//if (WC()->cart->is_empty()) {
//    return '<p>Ваша корзина пуста.</p>';
//}
?>

<style>
    .cartPageHeading {
        margin-top: 68px;
        margin-bottom: 50px;
    }

    .cartPageHeadingTitle h1 sup {
        font-family: 'Manrope', sans-serif;
        font-weight: 500;
        font-size: 16px;
        line-height: 125%;
        letter-spacing: 0;
        color: #202020;
    }

    .cartPageBody {
        margin-bottom: 14vh;
    }

    .cartPageBodyWrapper {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
    }

    .cartPageBodyList {
        flex: auto;
        max-width: 56vw;
    }

    .cartPageBodySide {
        flex: 1;
        max-width: 26vw;
        min-width: 26vw;
    }

    .cartPageBodySideCheckoutBlock {
        border: 1px solid #E7E7E7;
        padding: 30px;
        margin-bottom: 20px;
        border-radius: 5px;
    }

    .checkoutBlockHeading {
        margin-bottom: 20px;
    }

    .checkoutBlockBodyRow {
        margin-bottom: 16px;
    }

    .checkoutBlockBodyRowWrap {
        display: flex;
        justify-content: space-between;
    }

    .checkout-button {
        background: #F0C224;
        padding: 16px;
        display: block;
        border-radius: 25px;
        text-align: center;
        text-decoration: none;
        color: #252525;
        margin-bottom: 16px;
    }

    .delivery-note {
        text-align: center;
        color: #1F1F1F;
        font-weight: 500;
        font-size: 12px;
        line-height: 135%;
        letter-spacing: 0;
    }
</style>

<div class="cartPage">
    <div class="cartPageWrapper gridWrap">
        <div class="cartPageHeading">
            <div class="cartPageHeadingTitle">
                <h1>Получатель заказа <sup>(<?php echo WC()->cart->get_cart_contents_count(); ?>)</sup></h1>
            </div>
        </div>
        <div class="cartPageBody">
            <form name="checkout" method="post" class="checkout woocommerce-checkout"
                  action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data"
                  aria-label="<?php echo esc_attr__('Checkout', 'woocommerce'); ?>">
                <div class="cartPageBodyWrapper">
                    <div class="cartPageBodyList">
                        <!--   WC()->cart->get_cart()-->

                        <?php do_action('woocommerce_before_checkout_form', $checkout); ?>

                        <?php
                        // If checkout registration is disabled and not logged in, the user cannot checkout.
                        //                        if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
                        //                            echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
                        //                            return;
                        //                        }
                        ?>


                        <?php do_action('woocommerce_checkout_before_customer_details'); ?>

                        <?php do_action('woocommerce_checkout_billing'); ?>

                        <?php //echo var_dump(WC()->payment_gateways->get_available_payment_gateways()); ?>
                        <?php //echo var_dump(WC()->shipping()); ?>

                        <!---->
                        <!--                        <div class="cartPageHeadingTitle">-->
                        <!--                            <h1>Способ получения</h1>-->
                        <!--                        </div>-->
                        <!---->
                        <!--                        <div class="cartCheckoutDelivery">-->
                        <!--                            --><?php //woocommerce_checkout_shipping(); ?>
                        <!--                        </div>-->
                        <!---->
                        <!--                        --><?php //do_action('woocommerce_checkout_shipping'); ?>
                        <!---->
                        <!--                      -->


                        <?php do_action('woocommerce_checkout_before_order_review'); ?>


                        <?php
                        $shipping_methods = WC()->shipping()->get_shipping_methods();
                        $available_gateways = WC()->payment_gateways->get_available_payment_gateways();
                        ?>

                        <!-- Блок: Способ получения -->
                        <div class="shipping-methods-block">
                            <div class="cartPageHeadingTitle" style="margin-bottom: 24px">
                                <h1>Способ доставки</h1>
                            </div>
                            <div class="shipping-methods-grid">
                                <?php foreach ($shipping_methods as $ship_method_id => $ship_method) : ?>
                                    <?php if ($ship_method->is_available($shipping_methods) && !empty($ship_method->get_title())) { ?>
                                        <div class="shipMethodItem"
                                             data-shipping_method="<?php echo esc_attr($ship_method_id); ?>">
                                            <div class="shipMethodWrapper"
                                                 style="display: flex; align-items: center; gap: 16px;">
                                                <div class="paymentMethodIBox"
                                                     style="border-radius: 100px; border:solid 1px #ECECEC; width: 60px; height: 60px">
                                                    <div class="paymentMethodIBoxWrapper"
                                                         style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;">
                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                             xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M7.00781 7.99975V5.99975C7.00781 3.23833 9.24639 0.999756 12.0078 0.999756C14.7692 0.999756 17.0078 3.23833 17.0078 5.99975V7.99975H20.0078C20.5601 7.99975 21.0078 8.44747 21.0078 8.99975V20.9998C21.0078 21.552 20.5601 21.9998 20.0078 21.9998H4.00781C3.45553 21.9998 3.00781 21.552 3.00781 20.9998V8.99975C3.00781 8.44747 3.45553 7.99975 4.00781 7.99975H7.00781ZM7.00781 9.99975H5.00781V19.9998H19.0078V9.99975H17.0078V11.9998H15.0078V9.99975H9.00781V11.9998H7.00781V9.99975ZM9.00781 7.99975H15.0078V5.99975C15.0078 4.3429 13.6646 2.99975 12.0078 2.99975C10.3509 2.99975 9.00781 4.3429 9.00781 5.99975V7.99975Z"
                                                                  fill="#F0C224"/>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="shipMethodInfo">
                                                    <div class="shipMethodInfoHeading">
                                                        <div class="shipMethodInfoHeadingTitle">
                                                            <strong><?php echo esc_html($ship_method->get_title()); ?></strong>
                                                        </div>
                                                        <div class="shipMethodInfoHeadingDesc">
                                                            <?php echo wp_kses_post($ship_method->get_method_description()); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php endforeach; ?>
                            </div>
                        </div>


                        <div class="separate" style="margin: 30px 0">
                            <hr>
                        </div>


                        <!-- Блок: Способ оплаты -->
                        <div class="payment-methods-block">
                            <div class="cartPageHeadingTitle" style="margin-bottom: 24px">
                                <h1>Способ оплаты</h1>
                            </div>
                            <div class="payment-methods-grid">
                                <?php foreach ($available_gateways as $gateway_id => $gateway) : ?>
                                    <div class="paymentMethodItem"
                                         data-shipping_method="<?php echo esc_attr($gateway_id); ?>">
                                        <div class="paymentMethodWrapper"
                                             style="display: flex; align-items: center; gap: 16px;">
                                            <div class="paymentMethodLogo">
                                                <div class="paymentMethodIBox"
                                                     style="border-radius: 100px; border:solid 1px #ECECEC; width: 60px; height: 60px">
                                                    <div class="paymentMethodIBoxWrapper"
                                                         style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;">
                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                             xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M7.00781 7.99975V5.99975C7.00781 3.23833 9.24639 0.999756 12.0078 0.999756C14.7692 0.999756 17.0078 3.23833 17.0078 5.99975V7.99975H20.0078C20.5601 7.99975 21.0078 8.44747 21.0078 8.99975V20.9998C21.0078 21.552 20.5601 21.9998 20.0078 21.9998H4.00781C3.45553 21.9998 3.00781 21.552 3.00781 20.9998V8.99975C3.00781 8.44747 3.45553 7.99975 4.00781 7.99975H7.00781ZM7.00781 9.99975H5.00781V19.9998H19.0078V9.99975H17.0078V11.9998H15.0078V9.99975H9.00781V11.9998H7.00781V9.99975ZM9.00781 7.99975H15.0078V5.99975C15.0078 4.3429 13.6646 2.99975 12.0078 2.99975C10.3509 2.99975 9.00781 4.3429 9.00781 5.99975V7.99975Z"
                                                                  fill="#F0C224"/>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="paymentMethodInfo">
                                                <div class="paymentMethodInfoHeading">
                                                    <div class="paymentMethodInfoHeadingTitle">
                                                        <strong><?php echo esc_html($gateway->get_title()); ?></strong>
                                                    </div>
                                                    <div class="paymentMethodInfoHeadingDesc">
                                                        <?php echo wp_kses_post($gateway->get_description()); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <?php endforeach; ?>
                            </div>
                        </div>

                        <?php //do_action('woocommerce_checkout_order_review'); ?>
                        <?php do_action('woocommerce_checkout_after_order_review'); ?>

                        <?php do_action('woocommerce_checkout_after_customer_details'); ?>

                    </div>
                    <div class="cartPageBodySide">
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
                                <div class="checkoutBlockBodyRow">
                                    <div class="checkoutBlockBodyRowWrap">
                                        <div class="checkoutBlockBodyRowItem" style="font-weight: 600;">Итоговая сумма:
                                        </div>
                                        <div class="checkoutBlockBodyRowItem">
                                            <strong><?php echo wc_price(WC()->cart->get_total('edit')); ?></strong>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="checkoutBlockBodyRow">
                                    <?php
                                    //                                if (is_user_logged_in() || WC()->checkout()->is_registration_enabled() || !WC()->checkout()->is_registration_required()) {
                                    //                                    wc_get_template(
                                    //                                        'checkout/form-coupon.php',
                                    //                                        array(
                                    //                                            'checkout' => WC()->checkout(),
                                    //                                        )
                                    //                                    );
                                    //                                }
                                    ?>
                                </div>
                            </div>
                        </div>


                        <?php do_action('woocommerce_checkout_before_order_review'); ?>

                        <div class="checkoutBlockFooter">
                            <button type="submit" class="checkout-button button alt wp-element-button"
                                    name="woocommerce_checkout_place_order" id="place_order" value="Подтвердить заказ"
                                    data-value="Подтвердить заказ" style="width: 100%">Подтвердить заказ
                            </button>
                            <p class="delivery-note">Доставку и способ оплаты можно выбрать при оформлении заказа</p>
                        </div>
                    </div>
                </div>
            </form>
            <?php do_action('woocommerce_after_checkout_form', $checkout); ?>
        </div>
    </div>
</div>

<style>
    .shipping-methods-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .payment-methods-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .shipMethodItem, .paymentMethodItem {
        border: 1px solid #ECECEC;
        padding: 20px;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .shipMethodItem:hover, .paymentMethodItem:hover {
        border-color: #bbb;
        background: #fdfdfd;
    }

    .shipMethodItem.active, .paymentMethodItem.active {
        border-color: #bbb;
        background: #fdfdfd;
    }

</style>