<?php

//echo var_dump(WC());

//if (WC()->cart->is_empty()) {
//    return '<p>Ваша корзина пуста.</p>';
//}
?>

<div class="cartPage">
    <div class="cartPageWrapper gridWrap">
        <div class="cartPageHeading" style="margin-top: 68px; margin-bottom: 50px">
            <div class="cartPageHeadingTitle">
                <h1>Корзина <sup
                            style="font-family: 'Manrope',sans-serif;font-weight: 500;font-size: 16px;line-height: 125%;letter-spacing: 0; color: #202020;">(<?php echo WC()->cart->get_cart_contents_count(); ?>
                        )</sup></h1>
            </div>
        </div>
        <div class="cartPageBody" style="margin-bottom: 14vh">
            <div class="cartPageBodyWrapper" style="display: flex; align-items: flex-start">
                <div class="cartPageBodyList" style="flex: auto;">
                    <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
                        $product = $cart_item['data'];
                        $product_id = $cart_item['product_id'];
//                        $product_id = $cart_item['product_id'];
                        $product_permalink = $product->is_visible() ? $product->get_permalink($cart_item) : '';
                        $thumbnail = $product->get_image('woocommerce_thumbnail');
                        $product_name = $product->get_name();
                        $quantity = $cart_item['quantity'];
                        $price = wc_price($product->get_price());
                        $variation = wc_get_formatted_cart_item_data($cart_item, true);
                        $attachment_ids = $product->get_gallery_image_ids();
                        ?>
                        <div class="cartPageBodyListItem">
                            <div class="cartPageBodyListItemProduct">
                                <div class="bodyListItemProductWrapper"
                                     style="display: flex; justify-content: space-between">
                                    <div class="bodyListItemProductInfo">
                                        <div class="bodyListItemProductInfoWrapper"
                                             style="display: flex; flex-flow: row wrap; gap: 32px">
                                            <div class="bodyListItemProductInfoWrap">
                                                <div class="bodyListItemProductInfoCard"
                                                     style="position: relative; display: inline-block;">
                                                    <div class="bodyListItemProductInfoCardTop"
                                                         style="position: relative">
                                                        <div class="infoCardTopWhlBtn"
                                                             style="position: absolute; right: 10px; top:10px;">
                                                            <div class="infoCardTopWhlBtnIcon">
                                                                <svg width="30" height="30" viewBox="0 0 30 30"
                                                                     fill="none"
                                                                     xmlns="http://www.w3.org/2000/svg">
                                                                    <rect width="30" height="30" rx="15" fill="white"/>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php foreach ($attachment_ids

                                                                   as $attachment_id) {
                                                        $thumb_url = wp_get_attachment_image_url($attachment_id, 'thumbnail'); ?>
                                                        <div class="bodyListItemProductInfoCardImage"
                                                             style="height: 100%;
                                                                     width: 100%;
                                                                     max-width: 14vw;
                                                                     max-height: 28vw;
                                                                     min-width: 14vw;
                                                                     min-height: 28vh;
                                                                     background-repeat: no-repeat;
                                                                     background-position: center;
                                                                     background-size: cover;
                                                                     border-radius: 5px;
                                                                     background-image: url('<?php echo esc_url($thumb_url); ?>');">
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="infoCardHeading">
                                                <div class="infoCardHeadingTitle"
                                                     style="margin-bottom: 16px;font-style: normal;font-weight: 500;font-size: 18px;line-height: 135%;color: #1F1F1F; max-width: 18vw">
                                                    <?php echo $product_name ?>
                                                </div>
                                                <div class="infoCardHeadingSelColor" style="margin-bottom: 16px">
                                                    <svg width="119" height="16" viewBox="0 0 119 16" fill="none"
                                                         xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M24.448 11.936L24.938 10.9C25.3673 11.236 25.8387 11.488 26.352 11.656C26.8747 11.824 27.3927 11.9127 27.906 11.922C28.4193 11.922 28.8907 11.852 29.32 11.712C29.7587 11.572 30.1087 11.362 30.37 11.082C30.6313 10.802 30.762 10.4567 30.762 10.046C30.762 9.542 30.5567 9.15933 30.146 8.898C29.7447 8.63667 29.1847 8.506 28.466 8.506H26.282V7.372H28.368C28.9933 7.372 29.4787 7.246 29.824 6.994C30.1787 6.73267 30.356 6.37333 30.356 5.916C30.356 5.57067 30.2487 5.28133 30.034 5.048C29.8287 4.80533 29.544 4.62333 29.18 4.502C28.8253 4.38067 28.4193 4.32 27.962 4.32C27.514 4.31067 27.052 4.37133 26.576 4.502C26.1 4.62333 25.6427 4.81933 25.204 5.09L24.756 3.942C25.3533 3.606 25.974 3.37267 26.618 3.242C27.2713 3.102 27.9013 3.06 28.508 3.116C29.1147 3.16267 29.6607 3.30267 30.146 3.536C30.6407 3.76 31.0327 4.05867 31.322 4.432C31.6207 4.796 31.77 5.23467 31.77 5.748C31.77 6.21467 31.6487 6.63 31.406 6.994C31.1633 7.34867 30.8273 7.624 30.398 7.82C29.9687 8.00667 29.4693 8.1 28.9 8.1L28.956 7.722C29.6093 7.722 30.174 7.82933 30.65 8.044C31.1353 8.24933 31.5087 8.54333 31.77 8.926C32.0407 9.30867 32.176 9.752 32.176 10.256C32.176 10.732 32.0547 11.1567 31.812 11.53C31.5693 11.894 31.238 12.202 30.818 12.454C30.4073 12.6967 29.936 12.874 29.404 12.986C28.8813 13.098 28.3307 13.1353 27.752 13.098C27.1733 13.0607 26.5993 12.9487 26.03 12.762C25.47 12.5753 24.9427 12.3 24.448 11.936ZM37.5884 13.084C36.7951 13.084 36.0951 12.9207 35.4884 12.594C34.8911 12.2673 34.4244 11.8193 34.0884 11.25C33.7618 10.6807 33.5984 10.0273 33.5984 9.29C33.5984 8.55267 33.7571 7.89933 34.0744 7.33C34.4011 6.76067 34.8444 6.31733 35.4044 6C35.9738 5.67333 36.6131 5.51 37.3224 5.51C38.0411 5.51 38.6758 5.66867 39.2264 5.986C39.7771 6.30333 40.2064 6.75133 40.5144 7.33C40.8318 7.89933 40.9904 8.56667 40.9904 9.332C40.9904 9.388 40.9858 9.45333 40.9764 9.528C40.9764 9.60267 40.9718 9.67267 40.9624 9.738H34.6484V8.772H40.2624L39.7164 9.108C39.7258 8.632 39.6278 8.20733 39.4224 7.834C39.2171 7.46067 38.9324 7.17133 38.5684 6.966C38.2138 6.75133 37.7984 6.644 37.3224 6.644C36.8558 6.644 36.4404 6.75133 36.0764 6.966C35.7124 7.17133 35.4278 7.46533 35.2224 7.848C35.0171 8.22133 34.9144 8.65067 34.9144 9.136V9.36C34.9144 9.85467 35.0264 10.298 35.2504 10.69C35.4838 11.0727 35.8058 11.3713 36.2164 11.586C36.6271 11.8007 37.0984 11.908 37.6304 11.908C38.0691 11.908 38.4658 11.8333 38.8204 11.684C39.1844 11.5347 39.5018 11.3107 39.7724 11.012L40.5144 11.88C40.1784 12.272 39.7584 12.5707 39.2544 12.776C38.7598 12.9813 38.2044 13.084 37.5884 13.084ZM41.7204 12.986L41.8044 11.852C41.8697 11.8613 41.9304 11.8707 41.9864 11.88C42.0424 11.8893 42.0937 11.894 42.1404 11.894C42.4391 11.894 42.6724 11.7913 42.8404 11.586C43.0177 11.3807 43.1484 11.11 43.2324 10.774C43.3164 10.4287 43.3771 10.0413 43.4144 9.612C43.4517 9.18267 43.4797 8.75333 43.4984 8.324L43.6244 5.58H49.3224V13H47.9784V6.364L48.3004 6.756H44.4924L44.8004 6.35L44.7024 8.408C44.6744 9.06133 44.6231 9.67267 44.5484 10.242C44.4737 10.8113 44.3571 11.3107 44.1984 11.74C44.0491 12.1693 43.8391 12.5053 43.5684 12.748C43.3071 12.9907 42.9664 13.112 42.5464 13.112C42.4251 13.112 42.2944 13.098 42.1544 13.07C42.0237 13.0513 41.8791 13.0233 41.7204 12.986ZM55.2251 13.084C54.4318 13.084 53.7318 12.9207 53.1251 12.594C52.5278 12.2673 52.0611 11.8193 51.7251 11.25C51.3985 10.6807 51.2351 10.0273 51.2351 9.29C51.2351 8.55267 51.3938 7.89933 51.7111 7.33C52.0378 6.76067 52.4811 6.31733 53.0411 6C53.6105 5.67333 54.2498 5.51 54.9591 5.51C55.6778 5.51 56.3125 5.66867 56.8631 5.986C57.4138 6.30333 57.8431 6.75133 58.1511 7.33C58.4685 7.89933 58.6271 8.56667 58.6271 9.332C58.6271 9.388 58.6225 9.45333 58.6131 9.528C58.6131 9.60267 58.6085 9.67267 58.5991 9.738H52.2851V8.772H57.8991L57.3531 9.108C57.3625 8.632 57.2645 8.20733 57.0591 7.834C56.8538 7.46067 56.5691 7.17133 56.2051 6.966C55.8505 6.75133 55.4351 6.644 54.9591 6.644C54.4925 6.644 54.0771 6.75133 53.7131 6.966C53.3491 7.17133 53.0645 7.46533 52.8591 7.848C52.6538 8.22133 52.5511 8.65067 52.5511 9.136V9.36C52.5511 9.85467 52.6631 10.298 52.8871 10.69C53.1205 11.0727 53.4425 11.3713 53.8531 11.586C54.2638 11.8007 54.7351 11.908 55.2671 11.908C55.7058 11.908 56.1025 11.8333 56.4571 11.684C56.8211 11.5347 57.1385 11.3107 57.4091 11.012L58.1511 11.88C57.8151 12.272 57.3951 12.5707 56.8911 12.776C56.3965 12.9813 55.8411 13.084 55.2251 13.084ZM60.7122 13V5.58H62.0562V8.73H66.2842V5.58H67.6282V13H66.2842V9.892H62.0562V13H60.7122ZM77.5775 13V5.58H78.9215V13H77.5775ZM73.5875 8.1C74.5301 8.10933 75.2441 8.324 75.7295 8.744C76.2241 9.164 76.4715 9.75667 76.4715 10.522C76.4715 11.3247 76.2008 11.9453 75.6595 12.384C75.1275 12.8133 74.3621 13.0233 73.3635 13.014L70.2415 13V5.58H71.5855V8.086L73.5875 8.1ZM73.2655 11.992C73.8628 12.0013 74.3201 11.88 74.6375 11.628C74.9548 11.376 75.1135 11.0027 75.1135 10.508C75.1135 10.0227 74.9548 9.668 74.6375 9.444C74.3295 9.22 73.8721 9.10333 73.2655 9.094L71.5855 9.066V11.978L73.2655 11.992ZM81.5344 13V5.58H82.8784V10.984L87.4284 5.58H88.6464V13H87.3024V7.596L82.7664 13H81.5344ZM85.0064 4.614C84.3251 4.614 83.7884 4.45067 83.3964 4.124C83.0138 3.788 82.8178 3.30733 82.8084 2.682H83.7184C83.7278 3.03667 83.8444 3.31667 84.0684 3.522C84.3018 3.72733 84.6098 3.83 84.9924 3.83C85.3751 3.83 85.6831 3.72733 85.9164 3.522C86.1498 3.31667 86.2711 3.03667 86.2804 2.682H87.2184C87.2091 3.30733 87.0084 3.788 86.6164 4.124C86.2244 4.45067 85.6878 4.614 85.0064 4.614ZM95.1829 15.716V2.612H96.4289V15.716H95.1829ZM101.838 13L105.912 7.442V8.52L102.062 3.2H103.658L106.71 7.386L106.08 7.4L109.118 3.2H110.644L106.822 8.436V7.428L110.896 13H109.286L106.038 8.534H106.654L103.448 13H101.838ZM112.218 13V3.2H113.618V11.782H118.924V13H112.218Z"
                                                              fill="#252525"/>
                                                        <circle cx="8" cy="8" r="8" fill="#4E4B42"/>
                                                    </svg>
                                                </div>
                                                <div class="infoCardHeadingSelColor">
                                                    <a href="#" style="color: #E2B53C;">Изменить</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bodyListItemProductQty">
                                        <div class="bodyListItemProductQtyWrap" style="display:flex; gap: 6px">
                                            <div class="bodyListItemProductQtyBtnMns">
                                                <svg width="40" height="40" viewBox="0 0 40 40" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <rect opacity="0.5" x="0.5" y="0.5" width="39" height="39" rx="19.5"
                                                          fill="#FCFCFC" stroke="#ECECEC"/>
                                                    <path d="M25.8327 19.166H14.166V20.8327H25.8327V19.166Z"
                                                          fill="#202020"/>
                                                </svg>
                                            </div>
                                            <div class="bodyListItemProductQtyBtnCnt"
                                                 style="border: 1px solid #ECECEC;background: #FCFCFC;min-width: 80px;border-radius: 50px;text-align: center;display: flex;align-items: center;justify-content: center;">
                                                <?= $quantity ?>
                                            </div>
                                            <div class="bodyListItemProductQtyBtnPls">
                                                <svg width="40" height="40" viewBox="0 0 40 40" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <rect opacity="0.5" x="0.5" y="0.5" width="39" height="39" rx="19.5"
                                                          fill="#FCFCFC" stroke="#ECECEC"/>
                                                    <path d="M19.166 18.166V13.166H20.8327V18.166H25.8327V19.8327H20.8327V24.8327H19.166V19.8327H14.166V18.166H19.166Z"
                                                          fill="#202020"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="cartPageBodySide" style="flex: 1;max-width: 30vw;min-width: 30vw;">
                    <div class="cartPageBodySideCheckoutBlock" style="border: 1px solid #E7E7E7; padding: 30px; margin-bottom: 20px; border-radius: 5px">
                        <div class="checkoutBlockHeading" style="margin-bottom: 20px">
                            <div class="checkoutBlockHeadingTitle">
                                Ваш заказ
                            </div>
                        </div>

                        <div class="checkoutBlockBody">
                            <div class="checkoutBlockBodyRow" style="margin-bottom: 16px">
                                <div class="checkoutBlockBodyRowWrap" style="display: flex; justify-content: space-between;">
                                    <div class="checkoutBlockBodyRowItem">
                                        Товары, <?php echo WC()->cart->get_cart_contents_count(); ?> шт
                                    </div>
                                    <div class="checkoutBlockBodyRowItem">

                                        <strong><?php echo WC()->cart->get_cart_subtotal(); ?></strong>
                                    </div>
                                </div>
                            </div>

                            <div class="checkoutBlockBodyRow" style="margin-bottom: 16px">
                                <div class="checkoutBlockBodyRowWrap"
                                     style="display: flex; justify-content: space-between;">
                                    <div class="checkoutBlockBodyRowItem">
                                        Итоговая сумма:
                                    </div>
                                    <div class="checkoutBlockBodyRowItem">
                                        <strong><?php echo wc_price(WC()->cart->get_total('edit')); ?></strong>
                                    </div>
                                </div>
                            </div>

                            <!--                            <form class="custom-coupon-form" method="post">-->
                            <!--                                <input type="text" name="coupon_code" placeholder="Введите промокод"/>-->
                            <!--                                <button type="submit" name="apply_coupon">Применить</button>-->
                            <!--                            </form>-->
                        </div>

                    </div>

                    <div class="checkoutBlockFooter">
                        <a href="<?php echo wc_get_checkout_url(); ?>" style="background: #F0C224; padding: 16px; display: block; border-radius: 25px; text-align: center; text-decoration: none; color: #252525; margin-bottom: 16px"
                           class="checkout-button">К оформлению</a>

                        <p class="delivery-note" style="text-align: center; color: #1F1F1F;font-weight: 500;font-size: 12px;line-height: 135%;letter-spacing: 0;">Доставку и способ оплаты можно выбрать при оформлении заказа</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-cart-page {
        font-family: Arial, sans-serif;
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .custom-cart-items {
        margin-top: 20px;
    }

    .custom-cart-item {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        border-bottom: 1px solid #eee;
        padding-bottom: 20px;
    }

    .custom-cart-item-image img {
        width: 120px;
        height: auto;
        border-radius: 8px;
    }

    .custom-cart-item-info {
        flex: 1;
        margin-left: 20px;
    }

    .custom-cart-item-info h2 {
        margin: 0 0 10px;
        font-size: 18px;
    }

    .quantity-controls {
        margin-top: 10px;
    }

    .quantity-controls input {
        width: 50px;
        text-align: center;
    }

    .quantity-controls .update-qty-button {
        padding: 5px 10px;
        background: #eee;
        border: none;
        margin-left: 10px;
        cursor: pointer;
    }

    .item-price {
        font-weight: bold;
        margin-top: 10px;
    }

    .remove-item {
        background: none;
        border: none;
        font-size: 16px;
        color: red;
        text-decoration: none;
        margin-top: 10px;
        display: inline-block;
    }

    .custom-cart-summary {
        margin-top: 40px;
        padding: 20px;
        border: 1px solid #eee;
        border-radius: 8px;
    }

    .custom-coupon-form input {
        width: 70%;
        padding: 10px;
    }

    .custom-coupon-form button {
        padding: 10px 20px;
        background: #f5c242;
        border: none;
        cursor: pointer;
    }

    .checkout-button {
        display: block;
        background: #f5c242;
        border: none;
        padding: 15px 30px;
        cursor: pointer;
        font-size: 16px;
        text-align: center;
        margin-top: 20px;
        border-radius: 8px;
        text-decoration: none;
        color: #000;
    }

    .delivery-note {
        font-size: 12px;
        color: #888;
        margin-top: 10px;
        text-align: center;
    }
</style>
