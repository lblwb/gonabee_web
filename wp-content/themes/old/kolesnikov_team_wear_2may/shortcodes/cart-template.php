<?php

//echo var_dump(WC());

//if (WC()->cart->is_empty()) {
//    return '<p>Ваша корзина пуста.</p>';
//}
?>

<style>

</style>

<div class="cartPage">
    <?php if (WC()->cart->get_cart_contents_count()) { ?>
        <div class="cartPageWrapper gridWrap">
            <div class="cartPageHeading">
                <div class="cartPageHeadingTitle">
                    <h1>Корзина <sup>(<?php echo WC()->cart->get_cart_contents_count(); ?>)</sup></h1>
                </div>
            </div>
            <div class="cartPageBody">
                <div class="cartPageBodyWrapper">
                    <div class="cartPageBodyList">
                        <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
                            $product = $cart_item['data'];
                            $product_id = $cart_item['product_id'];
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
                                    <div class="bodyListItemProductWrapper">
                                        <div class="bodyListItemProductInfo">
                                            <div class="bodyListItemProductInfoWrapper">
                                                <div class="bodyListItemProductInfoWrap">
                                                    <div class="bodyListItemProductInfoCard">
                                                        <div class="bodyListItemProductInfoCardTop">
                                                            <div class="infoCardTopWhlBtn">
                                                                <div class="infoCardTopWhlBtnIcon">
                                                                    <svg width="30" height="30" viewBox="0 0 30 30"
                                                                         fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <rect width="30" height="30" rx="15"
                                                                              fill="white"/>
                                                                        <path d="M14.9994 10.019C16.5654 8.61333 18.9854 8.66 20.4938 10.1716C22.0022 11.6831 22.054 14.0913 20.6511 15.662L14.9986 21.3233L9.34628 15.662C7.9434 14.0913 7.99584 11.6793 9.5036 10.1716C11.0131 8.6621 13.4288 8.61125 14.9994 10.019ZM19.55 11.1134C18.5506 10.112 16.9371 10.0713 15.89 11.0112L15 11.8102L14.1094 11.0119C13.0594 10.0706 11.4487 10.1121 10.4464 11.1144C9.45325 12.1075 9.40339 13.6982 10.3187 14.7488L14.9986 19.4362L19.6788 14.7488C20.5944 13.6978 20.5447 12.1102 19.55 11.1134Z"
                                                                              fill="#1F1F1F"/>
                                                                    </svg>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php $thumb_url = wp_get_attachment_image_url($attachment_ids[0], 'thumbnail'); ?>
                                                        <div class="bodyListItemProductInfoCardImage"
                                                             style="background-image: url('<?php echo esc_url($thumb_url); ?>');"></div>
                                                    </div>
                                                </div>
                                                <div class="infoCardHeading">
                                                    <div class="infoCardHeadingSpWrap">
                                                        <div class="infoCardHeadingTop">
                                                            <div class="infoCardHeadingTitle">
                                                                <?php echo $product_name; ?>
                                                            </div>
                                                            <div class="infoCardHeadingSelWrap">
                                                                <div class="infoCardHeadingSelColor">
                                                                    <svg width="119" height="16" viewBox="0 0 119 16"
                                                                         fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M24.448 11.936L24.938 10.9C25.3673 11.236 25.8387 11.488 26.352 11.656C26.8747 11.824 27.3927 11.9127 27.906 11.922C28.4193 11.922 28.8907 11.852 29.32 11.712C29.7587 11.572 30.1087 11.362 30.37 11.082C30.6313 10.802 30.762 10.4567 30.762 10.046C30.762 9.542 30.5567 9.15933 30.146 8.898C29.7447 8.63667 29.1847 8.506 28.466 8.506H26.282V7.372H28.368C28.9933 7.372 29.4787 7.246 29.824 6.994C30.1787 6.73267 30.356 6.37333 30.356 5.916C30.356 5.57067 30.2487 5.28133 30.034 5.048C29.8287 4.80533 29.544 4.62333 29.18 4.502C28.8253 4.38067 28.4193 4.32 27.962 4.32C27.514 4.31067 27.052 4.37133 26.576 4.502C26.1 4.62333 25.6427 4.81933 25.204 5.09L24.756 3.942C25.3533 3.606 25.974 3.37267 26.618 3.242C27.2713 3.102 27.9013 3.06 28.508 3.116C29.1147 3.16267 29.6607 3.30267 30.146 3.536C30.6407 3.76 31.0327 4.05867 31.322 4.432C31.6207 4.796 31.77 5.23467 31.77 5.748C31.77 6.21467 31.6487 6.63 31.406 6.994C31.1633 7.34867 30.8273 7.624 30.398 7.82C29.9687 8.00667 29.4693 8.1 28.9 8.1L28.956 7.722C29.6093 7.722 30.174 7.82933 30.65 8.044C31.1353 8.24933 31.5087 8.54333 31.77 8.926C32.0407 9.30867 32.176 9.752 32.176 10.256C32.176 10.732 32.0547 11.1567 31.812 11.53C31.5693 11.894 31.238 12.202 30.818 12.454C30.4073 12.6967 29.936 12.874 29.404 12.986C28.8813 13.098 28.3307 13.1353 27.752 13.098C27.1733 13.0607 26.5993 12.9487 26.03 12.762C25.47 12.5753 24.9427 12.3 24.448 11.936ZM37.5884 13.084C36.7951 13.084 36.0951 12.9207 35.4884 12.594C34.8911 12.2673 34.4244 11.8193 34.0884 11.25C33.7618 10.6807 33.5984 10.0273 33.5984 9.29C33.5984 8.55267 33.7571 7.89933 34.0744 7.33C34.4011 6.76067 34.8444 6.31733 35.4044 6C35.9738 5.67333 36.6131 5.51 37.3224 5.51C38.0411 5.51 38.6758 5.66867 39.2264 5.986C39.7771 6.30333 40.2064 6.75133 40.5144 7.33C40.8318 7.89933 40.9904 8.56667 40.9904 9.332C40.9904 9.388 40.9858 9.45333 40.9764 9.528C40.9764 9.60267 40.9718 9.67267 40.9624 9.738H34.6484V8.772H40.2624L39.7164 9.108C39.7258 8.632 39.6278 8.20733 39.4224 7.834C39.2171 7.46067 38.9324 7.17133 38.5684 6.966C38.2138 6.75133 37.7984 6.644 37.3224 6.644C36.8558 6.644 36.4404 6.75133 36.0764 6.966C35.7124 7.17133 35.4278 7.46533 35.2224 7.848C35.0171 8.22133 34.9144 8.65067 34.9144 9.136V9.36C34.9144 9.85467 35.0264 10.298 35.2504 10.69C35.4838 11.0727 35.8058 11.3713 36.2164 11.586C36.6271 11.8007 37.0984 11.908 37.6304 11.908C38.0691 11.908 38.4658 11.8333 38.8204 11.684C39.1844 11.5347 39.5018 11.3107 39.7724 11.012L40.5144 11.88C40.1784 12.272 39.7584 12.5707 39.2544 12.776C38.7598 12.9813 38.2044 13.084 37.5884 13.084ZM41.7204 12.986L41.8044 11.852C41.8697 11.8613 41.9304 11.8707 41.9864 11.88C42.0424 11.8893 42.0937 11.894 42.1404 11.894C42.4391 11.894 42.6724 11.7913 42.8404 11.586C43.0177 11.3807 43.1484 11.11 43.2324 10.774C43.3164 10.4287 43.3771 10.0413 43.4144 9.612C43.4517 9.18267 43.4797 8.75333 43.4984 8.324L43.6244 5.58H49.3224V13H47.9784V6.364L48.3004 6.756H44.4924L44.8004 6.35L44.7024 8.408C44.6744 9.06133 44.6231 9.67267 44.5484 10.242C44.4737 10.8113 44.3571 11.3107 44.1984 11.74C44.0491 12.1693 43.8391 12.5053 43.5684 12.748C43.3071 12.9907 42.9664 13.112 42.5464 13.112C42.4251 13.112 42.2944 13.098 42.1544 13.07C42.0237 13.0513 41.8791 13.0233 41.7204 12.986ZM55.2251 13.084C54.4318 13.084 53.7318 12.9207 53.1251 12.594C52.5278 12.2673 52.0611 11.8193 51.7251 11.25C51.3985 10.6807 51.2351 10.0273 51.2351 9.29C51.2351 8.55267 51.3938 7.89933 51.7111 7.33C52.0378 6.76067 52.4811 6.31733 53.0411 6C53.6105 5.67333 54.2498 5.51 54.9591 5.51C55.6778 5.51 56.3125 5.66867 56.8631 5.986C57.4138 6.30333 57.8431 6.75133 58.1511 7.33C58.4685 7.89933 58.6271 8.56667 58.6271 9.332C58.6271 9.388 58.6225 9.45333 58.6131 9.528C58.6131 9.60267 58.6085 9.67267 58.5991 9.738H52.2851V8.772H57.8991L57.3531 9.108C57.3625 8.632 57.2645 8.20733 57.0591 7.834C56.8538 7.46067 56.5691 7.17133 56.2051 6.966C55.8505 6.75133 55.4351 6.644 54.9591 6.644C54.4925 6.644 54.0771 6.75133 53.7131 6.966C53.3491 7.17133 53.0645 7.46533 52.8591 7.848C52.6538 8.22133 52.5511 8.65067 52.5511 9.136V9.36C52.5511 9.85467 52.6631 10.298 52.8871 10.69C53.1205 11.0727 53.4425 11.3713 53.8531 11.586C54.2638 11.8007 54.7351 11.908 55.2671 11.908C55.7058 11.908 56.1025 11.8333 56.4571 11.684C56.8211 11.5347 57.1385 11.3107 57.4091 11.012L58.1511 11.88C57.8151 12.272 57.3951 12.5707 56.8911 12.776C56.3965 12.9813 55.8411 13.084 55.2251 13.084ZM60.7122 13V5.58H62.0562V8.73H66.2842V5.58H67.6282V13H66.2842V9.892H62.0562V13H60.7122ZM77.5775 13V5.58H78.9215V13H77.5775ZM73.5875 8.1C74.5301 8.10933 75.2441 8.324 75.7295 8.744C76.2241 9.164 76.4715 9.75667 76.4715 10.522C76.4715 11.3247 76.2008 11.9453 75.6595 12.384C75.1275 12.8133 74.3621 13.0233 73.3635 13.014L70.2415 13V5.58H71.5855V8.086L73.5875 8.1ZM73.2655 11.992C73.8628 12.0013 74.3201 11.88 74.6375 11.628C74.9548 11.376 75.1135 11.0027 75.1135 10.508C75.1135 10.0227 74.9548 9.668 74.6375 9.444C74.3295 9.22 73.8721 9.10333 73.2655 9.094L71.5855 9.066V11.978L73.2655 11.992ZM81.5344 13V5.58H82.8784V10.984L87.4284 5.58H88.6464V13H87.3024V7.596L82.7664 13H81.5344ZM85.0064 4.614C84.3251 4.614 83.7884 4.45067 83.3964 4.124C83.0138 3.788 82.8178 3.30733 82.8084 2.682H83.7184C83.7278 3.03667 83.8444 3.31667 84.0684 3.522C84.3018 3.72733 84.6098 3.83 84.9924 3.83C85.3751 3.83 85.6831 3.72733 85.9164 3.522C86.1498 3.31667 86.2711 3.03667 86.2804 2.682H87.2184C87.2091 3.30733 87.0084 3.788 86.6164 4.124C86.2244 4.45067 85.6878 4.614 85.0064 4.614ZM95.1829 15.716V2.612H96.4289V15.716H95.1829ZM101.838 13L105.912 7.442V8.52L102.062 3.2H103.658L106.71 7.386L106.08 7.4L109.118 3.2H110.644L106.822 8.436V7.428L110.896 13H109.286L106.038 8.534H106.654L103.448 13H101.838ZM112.218 13V3.2H113.618V11.782H118.924V13H112.218Z"
                                                                              fill="#252525"/>
                                                                        <circle cx="8" cy="8" r="8" fill="#4E4B42"/>
                                                                    </svg>
                                                                </div>
                                                                <div class="infoCardHeadingSelColor">
                                                                    <a @click="changeColor()">Изменить</a>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="infoCardHeadingBottom">
                                                            <div class="infoCardHeadingBottomWrapper">
                                                                <div class="infoCardQtyMb">
                                                                    <div class="bodyListItemProductQtyWrap">
                                                                        <div class="bodyListItemProductQtyBtnMns">
                                                                            <a href="?decrease_cart_item=<?php echo $cart_item_key ?>"
                                                                               style="display: flex">
                                                                                <svg width="40" height="40"
                                                                                     viewBox="0 0 40 40"
                                                                                     fill="none"
                                                                                     xmlns="http://www.w3.org/2000/svg">
                                                                                    <rect opacity="0.5" x="0.5" y="0.5"
                                                                                          width="39"
                                                                                          height="39" rx="19.5"
                                                                                          fill="#FCFCFC"
                                                                                          stroke="#ECECEC"/>
                                                                                    <path d="M25.8327 19.166H14.166V20.8327H25.8327V19.166Z"
                                                                                          fill="#202020"/>
                                                                                </svg>
                                                                            </a>
                                                                        </div>
                                                                        <div class="bodyListItemProductQtyBtnCnt"><?= $quantity ?></div>
                                                                        <div class="bodyListItemProductQtyBtnPls">
                                                                            <a href="?increase_cart_item=<?php echo $cart_item_key ?>"
                                                                               style="display: flex">
                                                                                <svg width="40" height="40"
                                                                                     viewBox="0 0 40 40"
                                                                                     fill="none"
                                                                                     xmlns="http://www.w3.org/2000/svg">
                                                                                    <rect opacity="0.5" x="0.5" y="0.5"
                                                                                          width="39"
                                                                                          height="39" rx="19.5"
                                                                                          fill="#FCFCFC"
                                                                                          stroke="#ECECEC"/>
                                                                                    <path d="M19.166 18.166V13.166H20.8327V18.166H25.8327V19.8327H20.8327V24.8327H19.166V19.8327H14.166V18.166H19.166Z"
                                                                                          fill="#202020"/>
                                                                                </svg>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="infoCardDelMb">
                                                                    <a href="<?php echo esc_url(wc_get_cart_remove_url($cart_item_key)) ?>"
                                                                       style="display: flex">
                                                                        <svg width="30" height="30" viewBox="0 0 30 30"
                                                                             fill="none"
                                                                             xmlns="http://www.w3.org/2000/svg">
                                                                            <rect x="0.5" y="0.5" width="29" height="29"
                                                                                  rx="14.5" fill="#FCFCFC"
                                                                                  stroke="#ECECEC"/>
                                                                            <path d="M18.334 10.9995H21.6673V12.3328H20.334V20.9995C20.334 21.3677 20.0355 21.6662 19.6673 21.6662H10.334C9.9658 21.6662 9.66732 21.3677 9.66732 20.9995V12.3328H8.33398V10.9995H11.6673V8.99949C11.6673 8.6313 11.9658 8.33282 12.334 8.33282H17.6673C18.0355 8.33282 18.334 8.6313 18.334 8.99949V10.9995ZM19.0007 12.3328H11.0007V20.3328H19.0007V12.3328ZM13.0007 9.66616V10.9995H17.0007V9.66616H13.0007Z"
                                                                                  fill="#1F1F1F"/>
                                                                        </svg>
                                                                    </a>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bodyListItemProductQty">
                                            <div class="bodyListItemProductQtyWrap">
                                                <div class="bodyListItemProductQtyBtnMns">
                                                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none"
                                                         xmlns="http://www.w3.org/2000/svg">
                                                        <rect opacity="0.5" x="0.5" y="0.5" width="39" height="39"
                                                              rx="19.5"
                                                              fill="#FCFCFC" stroke="#ECECEC"/>
                                                        <path d="M25.8327 19.166H14.166V20.8327H25.8327V19.166Z"
                                                              fill="#202020"/>
                                                    </svg>
                                                </div>
                                                <div class="bodyListItemProductQtyBtnCnt"><?= $quantity ?></div>
                                                <div class="bodyListItemProductQtyBtnPls">
                                                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none"
                                                         xmlns="http://www.w3.org/2000/svg">
                                                        <rect opacity="0.5" x="0.5" y="0.5" width="39" height="39"
                                                              rx="19.5"
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
                            </div>
                        </div>
                        <div class="checkoutBlockFooter">
                            <a href="<?php echo wc_get_checkout_url(); ?>" class="checkout-button">К оформлению</a>
                            <p class="delivery-note">Доставку и способ оплаты можно выбрать при оформлении заказа</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="emptyCart" style="height: 100%;">
            <div class="emptyCartWrap" style="display:flex; flex-flow: column; justify-content: center; align-items: center; height: 100%;">
                <div class="emptyCartHead" style="display: flex; margin-bottom: 16px">
                    <div class="emptyCartHeadImg">
                        <svg width="56" height="64" viewBox="0 0 56 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M28 0L41.92 7.88985L55.7128 16L55.84 32L55.7128 48L41.92 56.1101L28 64L14.08 56.1101L0.287188 48L0.16 32L0.287188 16L14.08 7.88985L28 0Z"
                                  fill="#F0C224"/>
                            <path d="M23.0098 27.9996V25.9996C23.0098 23.2382 25.2483 20.9996 28.0098 20.9996C30.7712 20.9996 33.0098 23.2382 33.0098 25.9996V27.9996H36.0098C36.5621 27.9996 37.0098 28.4473 37.0098 28.9996V40.9997C37.0098 41.5519 36.5621 41.9997 36.0098 41.9997H20.0098C19.4575 41.9997 19.0098 41.5519 19.0098 40.9997V28.9996C19.0098 28.4473 19.4575 27.9996 20.0098 27.9996H23.0098ZM23.0098 29.9996H21.0098V39.9997H35.0098V29.9996H33.0098V31.9997H31.0098V29.9996H25.0098V31.9997H23.0098V29.9996ZM25.0098 27.9996H31.0098V25.9996C31.0098 24.3428 29.6666 22.9996 28.0098 22.9996C26.3529 22.9996 25.0098 24.3428 25.0098 25.9996V27.9996Z"
                                  fill="#252525"/>
                        </svg>
                    </div>
                </div>
                <div class="emptyCartHeading" style="display: flex; flex-flow: column; justify-content: center; align-items: center;">
                    <div class="emptyCartHeadingTitle" style="margin-bottom: 12px;font-family: 'Sofia Sans',sans-serif;font-weight: 600;font-size: 20px;line-height: 125%;letter-spacing: 0%;text-align: center;">
                        В корзине пока нет товаров
                    </div>
                    <div class="emptyCartHeadingDesc" style="font-family: 'Montserrat',sans-serif;font-weight: 500;font-size: 14px;line-height: 145%;letter-spacing: 0%;text-align: center;">
                        Здесь появятся товары, которые вы добавите в корзину
                    </div>
                </div>
            </div>
        </div>

    <?php } ?>
</div>
