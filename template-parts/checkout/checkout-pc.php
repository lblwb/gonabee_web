<?php
if (isset($args['checkout'])) {
    $checkout = $args['checkout'];
} elseif (!isset($checkout) || !is_object($checkout)) {
    $checkout = WC()->checkout();
}
?>
<div class="cartPageWrapper gridWrap" v-if="appStateCheckout.isShow">
    <div class="cartPageHeading">
        <div class="cartPageHeadingTitle">
            <h1>Получатель заказа</h1>
        </div>
    </div>
    <div class="cartPageBody">
        <form name="checkout" method="post" class="checkout woocommerce-checkout"
            action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data"
            aria-label="<?php echo esc_attr__('Checkout', 'woocommerce'); ?>">
            <div class="cartPageBodyWrapper">
                <div class="cartPageBodyList">

                    <?php do_action('woocommerce_before_checkout_form', $checkout); ?>


                    <?php do_action('woocommerce_checkout_before_customer_details'); ?>

                    <?php do_action('woocommerce_checkout_billing'); ?>

                    <?php //echo var_dump(WC()->payment_gateways->get_available_payment_gateways()); 
                    ?>
                    <?php //echo var_dump(WC()->shipping()); 
                    ?>

                    <!---->
                    <!--                        <div class="cartPageHeadingTitle">-->
                    <!--                            <h1>Способ получения</h1>-->
                    <!--                        </div>-->
                    <!---->
                    <!--                        <div class="cartCheckoutDelivery">-->
                    <!--                            --><?php //woocommerce_checkout_shipping(); 
                                                        ?>
                    <!--                        </div>-->
                    <!---->
                    <!--                        --><?php //do_action('woocommerce_checkout_shipping'); 
                                                    ?>
                    <!---->
                    <!--                      -->


                    <?php do_action('woocommerce_checkout_before_order_review'); ?>


                    <?php
                    $shipping_methods = WC()->shipping()->get_shipping_methods();
                    $available_gateways = WC()->payment_gateways->get_available_payment_gateways();
                    ?>

                    <!--                    --><?php
                                                //                    $shipping_methods = WC()->shipping()->get_shipping_methods();
                                                //                    $available_gateways = WC()->payment_gateways->payment_gateways();
                                                //                    
                                                ?>

                    <div class="separate" style="margin: 30px 0">
                        <hr>
                    </div>
                    <!-- Блок: Способ получения -->
                    <?php
                    do_action('woocommerce_review_order_before_shipping');

                    wc_get_template('checkout/checkout-shipping.php', array());

                    do_action('woocommerce_review_order_after_shipping');
                    ?>


                    <div class="separate" style="margin: 30px 0">
                        <hr>
                    </div>
                    <!-- Блок: Способ оплаты -->
                    <div class="woocommerce-checkout-payment">
                        <?php
                        // Форма оплаты (кнопка Place order + hidden поля)
                        woocommerce_checkout_payment();
                        ?>
                    </div>

                    <?php do_action('woocommerce_checkout_after_customer_details'); ?>
                    <?php // 
                    ?>
                    <?php do_action('woocommerce_checkout_after_order_review'); ?>

                    <!-- <div id="payment" class="woocommerce-checkout-payment"> -->
                    <?php
                    // Форма оплаты (кнопка Place order + hidden поля)
                    // woocommerce_checkout_payment();
                    ?>
                    <!-- </div> -->
                </div>

                <?php do_action('woocommerce_checkout_order_review'); ?>
            </div>
        </form>
        <?php do_action('woocommerce_after_checkout_form', $checkout); ?>
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

    .shipMethodItem,
    .paymentMethodItem {
        border: 1px solid #ECECEC;
        padding: 20px;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .shipMethodItem:hover,
    .paymentMethodItem:hover {
        border-color: #bbb;
        background: #fdfdfd;
    }

    .shipMethodItem.active,
    .paymentMethodItem.active {
        border-color: #bbb;
        background: #fdfdfd;
    }
</style>