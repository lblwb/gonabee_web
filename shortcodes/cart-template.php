<?php
// Disable wpautop to prevent extra <p> tags
remove_filter('the_content', 'wpautop');

?>
<div class="cartPage">
    <?php if (WC()->cart->get_cart_contents_count()) { ?>
        <div class="cartPageWrapper gridWrap">
            <div class="cartPageHeading">
                <div class="cartPageHeadingTitle">
                    <h1>Корзина
                        <sup>
                            (<span data-total-count><?php echo WC()->cart->get_cart_contents_count(); ?></span>)
                        </sup>
                    </h1>
                </div>
            </div>
            <div class="cartPageBody" id="cartView"
                v-cloak
                data-nonce="<?php echo esc_attr(wp_create_nonce('toggle_favorite_nonce')); ?>"
                data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
                <div class="cartPageBodyWrapper">
                    <div class="cartPageBodyList">
                        <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item):
                            $product = $cart_item['data'];
                            $product_id = $cart_item['product_id'];
                            $product_permalink = $product->is_visible() ? $product->get_permalink($cart_item) : '';
                            $thumbnail = $product->get_image('woocommerce_thumbnail');
                            $product_name = $product->get_name();
                            $quantity = $cart_item['quantity'];
                            $price = wc_price($product->get_price());
                            $variation = wc_get_formatted_cart_item_data($cart_item, true);
                            $attachment_ids = $product->get_gallery_image_ids();
                            $main_image_url = '';
                            $main_image_id = get_post_thumbnail_id($product_id);

                            $color_id = (isset($cart_item['color_rel']) && $cart_item['color_rel'] !== 'false') ? $cart_item['color_rel'] : false;
                            if ($color_id) {
                                // Заполняем переменные для цветов
                                $color_post = get_post($color_id);
                                if ($color_post) {
                                    $color_slug = get_field('color_slug', $color_post->ID);
                                    $color_code = get_field('color_code', $color_post->ID);
                                    $color_name = get_the_title($color_post->ID);
                                }

                                // Получаем картинку товара по цвету
                                $color_list = get_field('product_colors', $product_id);
                                foreach ($color_list as $color_group) {
                                    $current_color_id = $color_group['color_rel'][0]->ID;
                                    if (empty($color_group['color_rel']) || (int) $current_color_id !== (int) $color_id) continue;


                                    $main_image_url = $color_group['color_images'][0]['url'];
                                }
                            } else if ($main_image_id) {
                                $main_image_url = wp_get_attachment_image_url($main_image_id, 'original');
                            }

                            $size = false;
                            if (isset($cart_item['variation']['attribute_pa_size'])) {
                                $size = $cart_item['variation']['attribute_pa_size'];
                            }


                        ?>
                            <div class="cartPageBodyListItem" v-if="count_qty('<?= htmlspecialchars($cart_item_key) ?>')" data-prd-key-id="<?= htmlspecialchars($cart_item_key) ?>">
                                <div class="cartPageBodyListItemProduct">
                                    <div class="bodyListItemProductWrapper">
                                        <div class="bodyListItemProductInfo">
                                            <div class="bodyListItemProductInfoWrapper">
                                                <div class="bodyListItemProductInfoWrap">
                                                    <div class="bodyListItemProductInfoCard">
                                                        <div class="bodyListItemProductInfoCardTop">
                                                            <div class="infoCardTopWhlBtn"
                                                                style="background: #FFFFFF; border-radius: 50px; max-width: 30px; max-height: 30px; width: 100%; height: 100%;"
                                                                @click="addToWhtListMob({imageUrl: '<?= $main_image_url ?>' ,productId: <?= esc_attr($product->get_id()); ?>})">
                                                                <div class="infoCardTopWhlBtnIcon" :data-test="getSelectedWhtLst(<?= esc_attr($product->get_id()); ?>) ? 'true' : 'false'" :class="{disable: !getSelectedWhtLst(<?= esc_attr($product->get_id()); ?>)}">
                                                                    <svg width="30" height="30"
                                                                        viewBox="0 0 30 30" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <rect width="30" height="30" rx="15"
                                                                            fill="white" />
                                                                        <path d="M14.9994 10.019C16.5654 8.61333 18.9854 8.66 20.4938 10.1716C22.0022 11.6831 22.054 14.0913 20.6511 15.662L14.9986 21.3233L9.34628 15.662C7.9434 14.0913 7.99584 11.6793 9.5036 10.1716C11.0131 8.6621 13.4288 8.61125 14.9994 10.019Z"
                                                                            fill="#CE1B19" />
                                                                    </svg>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="bodyListItemProductInfoCardImage"
                                                            style="background-image: url('<?= $main_image_url ?>');"></div>
                                                    </div>
                                                </div>
                                                <div class="infoCardHeading"
                                                    style="display: flex;flex-flow: column;justify-content: space-between;">
                                                    <div class="infoCardHeadingSpWrap">
                                                        <div class="infoCardHeadingTop">
                                                            <div class="infoCardHeadingTitle">
                                                                <?php echo $product_name; ?>
                                                            </div>
                                                            <div class="infoCardHeadingPrice"
                                                                style="font-family: 'Montserrat',sans-serif;font-weight: 600;font-size: 14px;line-height: 125%;letter-spacing: 0%; margin-bottom: 10px;">
                                                                <?php
                                                                if ($product->is_in_stock()) {
                                                                    echo wc_price($product->price);
                                                                } else {
                                                                    echo 'Товара нет в наличии';
                                                                }
                                                                ?>
                                                            </div>
                                                            <div class="infoCardHeadingSelWrap">
                                                                <div class="infoCardHeadingSelColor horizontalList">
                                                                    <?php if ($color_id): ?>

                                                                        <div class="horizontalListItem">
                                                                            <div class="attrPrdColor">
                                                                                <div class="attrPrdColorBox" style="background-color: <?= $color_code ?>;"></div>
                                                                                <div class="attrPrdColorName"><?= $color_name ?></div>
                                                                            </div>
                                                                        </div>

                                                                    <?php endif; ?>

                                                                    <?php if ($size && $product->get_attribute('pa_sizes')): ?>
                                                                        <div class="horizontalListItem">
                                                                            <?= $size ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="infoCardHeadingSelColor">
                                                                    <a onclick="window.states.prdModal.showModal(<?= esc_attr($product->get_id()); ?>, '<?= $cart_item_key ?>')">Изменить</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="infoCardHeadingBottom">
                                                            <div class="infoCardHeadingBottomWrapper">
                                                                <div class="infoCardQtyMb">
                                                                    <div class="bodyListItemProductQtyWrap">
                                                                        <div class="bodyListItemProductQtyBtnMns">
                                                                            <a @click="decrease_cart('<?php echo $cart_item_key ?>')" class="qty-btn-link">
                                                                                <svg width="40" height="40"
                                                                                    viewBox="0 0 40 40" fill="none"
                                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                                    <rect opacity="0.5" x="0.5"
                                                                                        y="0.5" width="39"
                                                                                        height="39" rx="19.5"
                                                                                        fill="#FCFCFC"
                                                                                        stroke="#ECECEC" />
                                                                                    <path d="M25.8327 19.166H14.166V20.8327H25.8327V19.166Z"
                                                                                        fill="#202020" />
                                                                                </svg>
                                                                            </a>
                                                                        </div>
                                                                        <div class="bodyListItemProductQtyBtnCnt"
                                                                            v-show="appCartStates.cart.qty_item !== null"
                                                                            data-cart-key-id="<?= htmlspecialchars($cart_item_key) ?>"
                                                                            data-cart-qty="<?= $quantity ?>"
                                                                            v-text="count_qty('<?= htmlspecialchars($cart_item_key) ?>')">
                                                                        </div>
                                                                        <div class="bodyListItemProductQtyBtnPls">
                                                                            <a @click="increase_cart('<?php echo $cart_item_key ?>')"
                                                                                class="qty-btn-link">
                                                                                <svg width="40" height="40"
                                                                                    viewBox="0 0 40 40" fill="none"
                                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                                    <rect opacity="0.5" x="0.5"
                                                                                        y="0.5" width="39"
                                                                                        height="39" rx="19.5"
                                                                                        fill="#FCFCFC"
                                                                                        stroke="#ECECEC" />
                                                                                    <path d="M19.166 18.166V13.166H20.8327V18.166H25.8327V19.8327H20.8327V24.8327H19.166V19.8327H14.166V18.166H19.166Z"
                                                                                        fill="#202020" />
                                                                                </svg>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="infoCardDelMb">
                                                                    <a @click="remove_cart_item('<?php echo $cart_item_key; ?>')"
                                                                        class="delete-btn-link">
                                                                        <svg width="30" height="30"
                                                                            viewBox="0 0 30 30" fill="none"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                            <rect x="0.5" y="0.5" width="29"
                                                                                height="29" rx="14.5"
                                                                                fill="#FCFCFC" stroke="#ECECEC" />
                                                                            <path d="M18.334 10.9995H21.6673V12.3328H20.334V20.9995C20.334 21.3677 20.0355 21.6662 19.6673 21.6662H10.334C9.9658 21.6662 9.66732 21.3677 9.66732 20.9995V12.3328H8.33398V10.9995H11.6673V8.99949C11.6673 8.6313 11.9658 8.33282 12.334 8.33282H17.6673C18.0355 8.33282 18.334 8.6313 18.334 8.99949V10.9995ZM19.0007 12.3328H11.0007V20.3328H19.0007V12.3328ZM13.0007 9.66616V10.9995H17.0007V9.66616H13.0007Z"
                                                                                fill="#1F1F1F" />
                                                                        </svg>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="infoCardHeadingBuyed">
                                                        <svg width="218" height="30" viewBox="0 0 218 30"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <rect width="218" height="30" rx="15"
                                                                fill="#FCF1CB"></rect>
                                                            <path d="M26.2815 9.54209L28.1278 7.69575C28.4044 7.41912 28.8529 7.41912 29.1295 7.69575L32.1348 10.701C32.4114 10.9776 32.4114 11.4261 32.1348 11.7027L29.4585 14.3789V20.8754C29.4585 21.2667 29.1414 21.5838 28.7502 21.5838H20.2502C19.859 21.5838 19.5419 21.2667 19.5419 20.8754V14.3789L16.8657 11.7027C16.589 11.4261 16.589 10.9776 16.8657 10.701L19.8709 7.69575C20.1475 7.41912 20.596 7.41912 20.8726 7.69575L22.7189 9.54209H26.2815Z"
                                                                fill="#252525"></path>
                                                            <path d="M44.064 19.096C43.424 19.096 42.832 18.992 42.288 18.784C41.752 18.568 41.284 18.268 40.884 17.884C40.492 17.492 40.184 17.036 39.96 16.516C39.736 15.996 39.624 15.424 39.624 14.8C39.624 14.176 39.736 13.604 39.96 13.084C40.184 12.564 40.496 12.112 40.896 11.728C41.296 11.336 41.764 11.036 42.3 10.828C42.844 10.612 43.436 10.504 44.076 10.504C44.724 10.504 45.32 10.616 45.864 10.84C46.416 11.056 46.884 11.38 47.268 11.812L46.488 12.568C46.168 12.232 45.808 11.984 45.408 11.824C45.008 11.656 44.58 11.572 44.124 11.572C43.652 11.572 43.212 11.652 42.804 11.812C42.404 11.972 42.056 12.196 41.76 12.484C41.464 12.772 41.232 13.116 41.064 13.516C40.904 13.908 40.824 14.336 40.824 14.8C40.824 15.264 40.904 15.696 41.064 16.096C41.232 16.488 41.464 16.828 41.76 17.116C42.056 17.404 42.404 17.628 42.804 17.788C43.212 17.948 43.652 18.028 44.124 18.028C44.58 18.028 45.008 17.948 45.408 17.788C45.808 17.62 46.168 17.364 46.488 17.02L47.268 17.776C46.884 18.208 46.416 18.536 45.864 18.76C45.32 18.984 44.72 19.096 44.064 19.096ZM52.9359 16.204V15.352H56.4999V16.204H52.9359ZM53.7879 12.568C54.4439 12.568 55.0319 12.708 55.5519 12.988C56.0719 13.268 56.4799 13.652 56.7759 14.14C57.0719 14.628 57.2199 15.188 57.2199 15.82C57.2199 16.444 57.0719 17.004 56.7759 17.5C56.4799 17.996 56.0719 18.384 55.5519 18.664C55.0319 18.944 54.4439 19.084 53.7879 19.084C53.2439 19.084 52.7399 18.996 52.2759 18.82C51.8199 18.644 51.4359 18.388 51.1239 18.052L51.7959 17.38C52.0599 17.636 52.3519 17.828 52.6719 17.956C52.9999 18.076 53.3559 18.136 53.7399 18.136C54.2039 18.136 54.6159 18.04 54.9759 17.848C55.3359 17.648 55.6199 17.372 55.8279 17.02C56.0439 16.668 56.1519 16.268 56.1519 15.82C56.1519 15.372 56.0439 14.976 55.8279 14.632C55.6199 14.28 55.3359 14.004 54.9759 13.804C54.6159 13.604 54.2039 13.504 53.7399 13.504C53.3559 13.504 52.9999 13.568 52.6719 13.696C52.3519 13.824 52.0599 14.016 51.7959 14.272L51.1239 13.612C51.4359 13.268 51.8199 13.008 52.2759 12.832C52.7399 12.656 53.2439 12.568 53.7879 12.568ZM59.8799 19V13.348L60.1679 13.648H57.5519V12.64H63.3599V13.648H60.7559L61.0319 13.348V19H59.8799ZM64.5379 19V12.64H65.6899V17.272L69.5899 12.64H70.6339V19H69.4819V14.368L65.5939 19H64.5379ZM72.8699 19V12.64H74.0579L76.7939 17.344H76.3139L79.1219 12.64H80.2019V19H79.1579V13.936L79.3499 14.044L76.7819 18.28H76.2779L73.6979 13.96L73.9259 13.912V19H72.8699ZM86.9268 19V13.348L87.2148 13.648H84.5988V12.64H90.4068V13.648H87.8028L88.0788 13.348V19H86.9268ZM94.0275 19.072C93.3875 19.072 92.8195 18.932 92.3235 18.652C91.8275 18.372 91.4355 17.988 91.1475 17.5C90.8595 17.004 90.7155 16.444 90.7155 15.82C90.7155 15.188 90.8595 14.628 91.1475 14.14C91.4355 13.652 91.8275 13.272 92.3235 13C92.8195 12.72 93.3875 12.58 94.0275 12.58C94.6595 12.58 95.2235 12.72 95.7195 13C96.2235 13.272 96.6155 13.652 96.8955 14.14C97.1835 14.62 97.3275 15.18 97.3275 15.82C97.3275 16.452 97.1835 17.012 96.8955 17.5C96.6155 17.988 96.2235 18.372 95.7195 18.652C95.2235 18.932 94.6595 19.072 94.0275 19.072ZM94.0275 18.064C94.4355 18.064 94.7995 17.972 95.1195 17.788C95.4475 17.604 95.7035 17.344 95.8875 17.008C96.0715 16.664 96.1635 16.268 96.1635 15.82C96.1635 15.364 96.0715 14.972 95.8875 14.644C95.7035 14.308 95.4475 14.048 95.1195 13.864C94.7995 13.68 94.4355 13.588 94.0275 13.588C93.6195 13.588 93.2555 13.68 92.9355 13.864C92.6155 14.048 92.3595 14.308 92.1675 14.644C91.9755 14.972 91.8795 15.364 91.8795 15.82C91.8795 16.268 91.9755 16.664 92.1675 17.008C92.3595 17.344 92.6155 17.604 92.9355 17.788C93.2555 17.972 93.6195 18.064 94.0275 18.064ZM98.991 19V12.64H101.943C102.687 12.64 103.271 12.784 103.695 13.072C104.127 13.352 104.343 13.752 104.343 14.272C104.343 14.792 104.139 15.196 103.731 15.484C103.331 15.764 102.799 15.904 102.135 15.904L102.315 15.592C103.075 15.592 103.639 15.732 104.007 16.012C104.375 16.292 104.559 16.704 104.559 17.248C104.559 17.8 104.351 18.232 103.935 18.544C103.527 18.848 102.899 19 102.051 19H98.991ZM100.119 18.112H101.967C102.447 18.112 102.807 18.04 103.047 17.896C103.287 17.744 103.407 17.504 103.407 17.176C103.407 16.84 103.295 16.596 103.071 16.444C102.855 16.284 102.511 16.204 102.039 16.204H100.119V18.112ZM100.119 15.376H101.847C102.287 15.376 102.619 15.296 102.843 15.136C103.075 14.968 103.191 14.736 103.191 14.44C103.191 14.136 103.075 13.908 102.843 13.756C102.619 13.604 102.287 13.528 101.847 13.528H100.119V15.376ZM110.262 19V17.656L110.202 17.404V15.112C110.202 14.624 110.058 14.248 109.77 13.984C109.49 13.712 109.066 13.576 108.498 13.576C108.122 13.576 107.754 13.64 107.394 13.768C107.034 13.888 106.73 14.052 106.482 14.26L106.002 13.396C106.33 13.132 106.722 12.932 107.178 12.796C107.642 12.652 108.126 12.58 108.63 12.58C109.502 12.58 110.174 12.792 110.646 13.216C111.118 13.64 111.354 14.288 111.354 15.16V19H110.262ZM108.174 19.072C107.702 19.072 107.286 18.992 106.926 18.832C106.574 18.672 106.302 18.452 106.11 18.172C105.918 17.884 105.822 17.56 105.822 17.2C105.822 16.856 105.902 16.544 106.062 16.264C106.23 15.984 106.498 15.76 106.866 15.592C107.242 15.424 107.746 15.34 108.378 15.34H110.394V16.168H108.426C107.85 16.168 107.462 16.264 107.262 16.456C107.062 16.648 106.962 16.88 106.962 17.152C106.962 17.464 107.086 17.716 107.334 17.908C107.582 18.092 107.926 18.184 108.366 18.184C108.798 18.184 109.174 18.088 109.494 17.896C109.822 17.704 110.058 17.424 110.202 17.056L110.43 17.848C110.278 18.224 110.01 18.524 109.626 18.748C109.242 18.964 108.758 19.072 108.174 19.072ZM116.801 19.072C116.273 19.072 115.789 18.952 115.349 18.712C114.917 18.464 114.569 18.1 114.305 17.62C114.049 17.14 113.921 16.54 113.921 15.82C113.921 15.1 114.045 14.5 114.293 14.02C114.549 13.54 114.893 13.18 115.325 12.94C115.765 12.7 116.257 12.58 116.801 12.58C117.425 12.58 117.977 12.716 118.457 12.988C118.937 13.26 119.317 13.64 119.597 14.128C119.877 14.608 120.017 15.172 120.017 15.82C120.017 16.468 119.877 17.036 119.597 17.524C119.317 18.012 118.937 18.392 118.457 18.664C117.977 18.936 117.425 19.072 116.801 19.072ZM113.429 21.328V12.64H114.533V14.356L114.461 15.832L114.581 17.308V21.328H113.429ZM116.705 18.064C117.113 18.064 117.477 17.972 117.797 17.788C118.125 17.604 118.381 17.344 118.565 17.008C118.757 16.664 118.853 16.268 118.853 15.82C118.853 15.364 118.757 14.972 118.565 14.644C118.381 14.308 118.125 14.048 117.797 13.864C117.477 13.68 117.113 13.588 116.705 13.588C116.305 13.588 115.941 13.68 115.613 13.864C115.293 14.048 115.037 14.308 114.845 14.644C114.661 14.972 114.569 15.364 114.569 15.82C114.569 16.268 114.661 16.664 114.845 17.008C115.037 17.344 115.293 17.604 115.613 17.788C115.941 17.972 116.305 18.064 116.705 18.064ZM124.414 19.072C123.774 19.072 123.206 18.932 122.71 18.652C122.214 18.372 121.822 17.988 121.534 17.5C121.246 17.004 121.102 16.444 121.102 15.82C121.102 15.188 121.246 14.628 121.534 14.14C121.822 13.652 122.214 13.272 122.71 13C123.206 12.72 123.774 12.58 124.414 12.58C125.046 12.58 125.61 12.72 126.106 13C126.61 13.272 127.002 13.652 127.282 14.14C127.57 14.62 127.714 15.18 127.714 15.82C127.714 16.452 127.57 17.012 127.282 17.5C127.002 17.988 126.61 18.372 126.106 18.652C125.61 18.932 125.046 19.072 124.414 19.072ZM124.414 18.064C124.822 18.064 125.186 17.972 125.506 17.788C125.834 17.604 126.09 17.344 126.274 17.008C126.458 16.664 126.55 16.268 126.55 15.82C126.55 15.364 126.458 14.972 126.274 14.644C126.09 14.308 125.834 14.048 125.506 13.864C125.186 13.68 124.822 13.588 124.414 13.588C124.006 13.588 123.642 13.68 123.322 13.864C123.002 14.048 122.746 14.308 122.554 14.644C122.362 14.972 122.266 15.364 122.266 15.82C122.266 16.268 122.362 16.664 122.554 17.008C122.746 17.344 123.002 17.604 123.322 17.788C123.642 17.972 124.006 18.064 124.414 18.064ZM129.378 19V12.64H130.566L133.302 17.344H132.822L135.63 12.64H136.71V19H135.666V13.936L135.858 14.044L133.29 18.28H132.786L130.206 13.96L130.434 13.912V19H129.378ZM142.175 19V12.64H148.067V19H146.915V13.36L147.191 13.648H143.051L143.327 13.36V19H142.175ZM153.02 19.072C152.38 19.072 151.812 18.932 151.316 18.652C150.82 18.372 150.428 17.988 150.14 17.5C149.852 17.004 149.708 16.444 149.708 15.82C149.708 15.188 149.852 14.628 150.14 14.14C150.428 13.652 150.82 13.272 151.316 13C151.812 12.72 152.38 12.58 153.02 12.58C153.652 12.58 154.216 12.72 154.712 13C155.216 13.272 155.608 13.652 155.888 14.14C156.176 14.62 156.32 15.18 156.32 15.82C156.32 16.452 156.176 17.012 155.888 17.5C155.608 17.988 155.216 18.372 154.712 18.652C154.216 18.932 153.652 19.072 153.02 19.072ZM153.02 18.064C153.428 18.064 153.792 17.972 154.112 17.788C154.44 17.604 154.696 17.344 154.88 17.008C155.064 16.664 155.156 16.268 155.156 15.82C155.156 15.364 155.064 14.972 154.88 14.644C154.696 14.308 154.44 14.048 154.112 13.864C153.792 13.68 153.428 13.588 153.02 13.588C152.612 13.588 152.248 13.68 151.928 13.864C151.608 14.048 151.352 14.308 151.16 14.644C150.968 14.972 150.872 15.364 150.872 15.82C150.872 16.268 150.968 16.664 151.16 17.008C151.352 17.344 151.608 17.604 151.928 17.788C152.248 17.972 152.612 18.064 153.02 18.064ZM162.591 19L160.131 15.904L161.079 15.328L163.947 19H162.591ZM157.983 19V12.64H159.135V19H157.983ZM158.787 16.312V15.328H160.887V16.312H158.787ZM161.175 15.952L160.107 15.808L162.543 12.64H163.779L161.175 15.952ZM165.116 21.4C164.812 21.4 164.516 21.348 164.228 21.244C163.94 21.148 163.692 21.004 163.484 20.812L163.976 19.948C164.136 20.1 164.312 20.216 164.504 20.296C164.696 20.376 164.9 20.416 165.116 20.416C165.396 20.416 165.628 20.344 165.812 20.2C165.996 20.056 166.168 19.8 166.328 19.432L166.724 18.556L166.844 18.412L169.34 12.64H170.468L167.384 19.636C167.2 20.084 166.992 20.436 166.76 20.692C166.536 20.948 166.288 21.128 166.016 21.232C165.744 21.344 165.444 21.4 165.116 21.4ZM166.628 19.204L163.724 12.64H164.924L167.396 18.304L166.628 19.204ZM171.647 19V12.64H177.539V19H176.387V13.36L176.663 13.648H172.523L172.799 13.36V19H171.647ZM183.668 19V17.656L183.608 17.404V15.112C183.608 14.624 183.464 14.248 183.176 13.984C182.896 13.712 182.472 13.576 181.904 13.576C181.528 13.576 181.16 13.64 180.8 13.768C180.44 13.888 180.136 14.052 179.888 14.26L179.408 13.396C179.736 13.132 180.128 12.932 180.584 12.796C181.048 12.652 181.532 12.58 182.036 12.58C182.908 12.58 183.58 12.792 184.052 13.216C184.524 13.64 184.76 14.288 184.76 15.16V19H183.668ZM181.58 19.072C181.108 19.072 180.692 18.992 180.332 18.832C179.98 18.672 179.708 18.452 179.516 18.172C179.324 17.884 179.228 17.56 179.228 17.2C179.228 16.856 179.308 16.544 179.468 16.264C179.636 15.984 179.904 15.76 180.272 15.592C180.648 15.424 181.152 15.34 181.784 15.34H183.8V16.168H181.832C181.256 16.168 180.868 16.264 180.668 16.456C180.468 16.648 180.368 16.88 180.368 17.152C180.368 17.464 180.492 17.716 180.74 17.908C180.988 18.092 181.332 18.184 181.772 18.184C182.204 18.184 182.58 18.088 182.9 17.896C183.228 17.704 183.464 17.424 183.608 17.056L183.836 17.848C183.684 18.224 183.416 18.524 183.032 18.748C182.648 18.964 182.164 19.072 181.58 19.072ZM186.835 19V12.64H187.987V15.244H189.907V16.312H187.987V19H186.835ZM192.463 19.072C191.839 19.072 191.283 18.932 190.795 18.652C190.315 18.372 189.935 17.988 189.655 17.5C189.375 17.004 189.235 16.444 189.235 15.82C189.235 15.188 189.375 14.628 189.655 14.14C189.935 13.644 190.315 13.26 190.795 12.988C191.283 12.716 191.839 12.58 192.463 12.58C193.079 12.58 193.631 12.716 194.119 12.988C194.607 13.26 194.991 13.644 195.271 14.14C195.551 14.628 195.691 15.188 195.691 15.82C195.691 16.452 195.551 17.012 195.271 17.5C194.991 17.988 194.607 18.372 194.119 18.652C193.631 18.932 193.079 19.072 192.463 19.072ZM192.463 18.064C192.863 18.064 193.219 17.972 193.531 17.788C193.843 17.596 194.091 17.336 194.275 17.008C194.459 16.672 194.551 16.276 194.551 15.82C194.551 15.364 194.459 14.972 194.275 14.644C194.091 14.308 193.843 14.048 193.531 13.864C193.219 13.68 192.863 13.588 192.463 13.588C192.071 13.588 191.715 13.68 191.395 13.864C191.083 14.048 190.835 14.308 190.651 14.644C190.467 14.972 190.375 15.364 190.375 15.82C190.375 16.276 190.467 16.672 190.651 17.008C190.835 17.336 191.083 17.596 191.395 17.788C191.715 17.972 192.071 18.064 192.463 18.064ZM198.372 19V13.348L198.66 13.648H196.044V12.64H201.852V13.648H199.248L199.524 13.348V19H198.372Z"
                                                                fill="#252525"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bodyListItemProductView">
                                            <div class="bodyListItemProductViewWrapper"
                                                style="display: flex;flex-flow: column;justify-content: space-between;height: 100%;align-items: flex-end;align-content: space-between;">
                                                <div class="bodyListItemProductViewTop">
                                                    <div class="bodyListItemProductViewWrapper">
                                                        <div class="bodyListItemProductQty">
                                                            <div class="bodyListItemProductQtyWrap">
                                                                <div class="bodyListItemProductQtyBtnMns">
                                                                    <a @click="decrease_cart('<?php echo $cart_item_key ?>')"
                                                                        class="qty-btn-link">
                                                                        <svg width="40" height="40"
                                                                            viewBox="0 0 40 40" fill="none"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                            <rect opacity="0.5" x="0.5"
                                                                                y="0.5" width="39"
                                                                                height="39" rx="19.5"
                                                                                fill="#FCFCFC"
                                                                                stroke="#ECECEC" />
                                                                            <path d="M25.8327 19.166H14.166V20.8327H25.8327V19.166Z"
                                                                                fill="#202020" />
                                                                        </svg>
                                                                    </a>
                                                                </div>
                                                                <div
                                                                    v-show="appCartStates.cart.qty_item !== null"
                                                                    class="bodyListItemProductQtyBtnCnt"
                                                                    data-cart-key-id="<?= htmlspecialchars($cart_item_key) ?>"
                                                                    data-cart-qty="<?= $quantity ?>"
                                                                    v-text="count_qty('<?= htmlspecialchars($cart_item_key) ?>')">
                                                                </div>
                                                                <div class="bodyListItemProductQtyBtnPls">
                                                                    <a @click="increase_cart('<?php echo $cart_item_key ?>')"
                                                                        class="qty-btn-link">
                                                                        <svg width="40" height="40"
                                                                            viewBox="0 0 40 40" fill="none"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                            <rect opacity="0.5" x="0.5"
                                                                                y="0.5" width="39"
                                                                                height="39" rx="19.5"
                                                                                fill="#FCFCFC"
                                                                                stroke="#ECECEC" />
                                                                            <path d="M19.166 18.166V13.166H20.8327V18.166H25.8327V19.8327H20.8327V24.8327H19.166V19.8327H14.166V18.166H19.166Z"
                                                                                fill="#202020" />
                                                                        </svg>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="bodyListItemProductPrice">
                                                            <?php echo $price ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="bodyListItemProductViewBottom">
                                                    <a @click="remove_cart_item('<?php echo $cart_item_key; ?>')"
                                                        class="delete-btn-link">
                                                        <div class="bodyListItemProductViewBtn">
                                                            <svg width="40" height="40" viewBox="0 0 40 40"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <rect opacity="0.5" x="0.5" y="0.5" width="39"
                                                                    height="39" rx="19.5" stroke="#D5D5D5" />
                                                                <path d="M24.166 14.9993H28.3327V16.666H26.666V27.4993C26.666 27.9596 26.2929 28.3327 25.8327 28.3327H14.166C13.7058 28.3327 13.3327 27.9596 13.3327 27.4993V16.666H11.666V14.9993H15.8327V12.4993C15.8327 12.0391 16.2058 11.666 16.666 11.666H23.3327C23.7929 11.666 24.166 12.0391 24.166 12.4993V14.9993ZM24.9993 16.666H14.9993V26.666H24.9993V16.666ZM17.4993 13.3327V14.9993H22.4993V13.3327H17.4993Z"
                                                                    fill="#1F1F1F" />
                                                            </svg>
                                                        </div>
                                                    </a>
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
                                            Товары, <span data-total-count><?php echo WC()->cart->get_cart_contents_count(); ?></span> шт
                                        </div>
                                        <div class="checkoutBlockBodyRowItem" id="cartTotalAmount"><?php echo WC()->cart->get_cart_subtotal(); ?></div>
                                    </div>
                                </div>
                                <div class="checkoutBlockBodyRow" v-if="appPromocodeStates.discountFormatted">
                                    <div class="checkoutBlockBodyRowWrap">
                                        <div class="checkoutBlockBodyRowItem">
                                            Скидка
                                        </div>
                                        <div class="checkoutBlockBodyRowItem" id="cartTotalAmount">{{ appPromocodeStates.discountFormatted }}</div>
                                    </div>
                                </div>
                                <div class="checkoutBlockBodyRow">
                                    <div class="checkoutBlockBodyRowWrap">
                                        <div class="checkoutBlockBodyRowItem checkoutBlockBodyRowItemBold">
                                            Итоговая сумма:
                                        </div>
                                        <div class="checkoutBlockBodyRowItem" id="cartTotalAmount">
                                            <strong><?php echo wc_price(WC()->cart->get_total('edit')); ?></strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="checkoutBodyRow">
                                    <div class="promocodeWrapper" :class="{disable: appPromocodeStates.isDisable}" v-if="appPromocodeStates">
                                        <div class="promocodeBlock" role="region" aria-label="Promocode Section">
                                            <button class="promocodeBlock__header" @click.prevent="togglePromocode" @submit.prevent="togglePromocode" :aria-expanded="appPromocodeStates.isOpen" aria-controls="promocodeContent"><span class="promocodeBlock__headerText">Введите промокод</span><span class="promocodeBlock__arrow" :class="{ 'promocodeBlock__arrowOpen': appPromocodeStates.isOpen }"></span></button>
                                            <div class="promocodeBlock__content" id="promocodeContent" v-show="appPromocodeStates.isOpen" role="group" :aria-hidden="!appPromocodeStates.isOpen">
                                                <input
                                                    v-model.trim="appPromocodeStates.promocode"
                                                    type="text"
                                                    :placeholder="appPromocodeStates.isDisable ? appPromocodeStates.promocode : 'Промокод или сертификат'"
                                                    @keyup.enter="applyPromocode"
                                                    :disabled="appPromocodeStates.isLoading || appPromocodeStates.isDisable"
                                                    aria-label="Введите промокод или сертификат"
                                                    class="promocodeBlock__input" />
                                                <button
                                                    @click="applyPromocode"
                                                    :disabled="!appPromocodeStates.promocode || appPromocodeStates.isLoading || appPromocodeStates.isDisable"
                                                    :aria-busy="appPromocodeStates.isLoading"
                                                    class="promocodeBlock__applyButton">
                                                    <span
                                                        v-if="appPromocodeStates.isLoading"
                                                        class="promocodeBlock__loadingSpinner">
                                                    </span>
                                                    <span v-else>Применить</span>
                                                </button>
                                                <p
                                                    v-if="appPromocodeStates.message"
                                                    class="promocodeBlock__message"
                                                    :class="{ 'promocodeBlock__messageError': appPromocodeStates.isError }">
                                                    {{ appPromocodeStates.message }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="checkoutBlockFooter">
                            <a href="<?php echo wc_get_checkout_url(); ?>" class="checkout-button">К оформлению</a>
                            <div class="delivery-note">Доставку и способ оплаты можно выбрать при оформлении
                                заказа
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--   -->
            <div class="cartMobBottom">
                <div class="cartMobBottomWrapper">
                    <div class="cartMobBottomBlock">
                        <div class="cartMobBottomBlockAction" style="">
                            <a href="<?php echo wc_get_checkout_url(); ?>" class="cartMobBottomBlockActionBtn">
                                <div class="blockActionBtnHeading">
                                    <div class="blockActionBtnHeadingTitle">
                                        К оформлению
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="cartMobBottomBlockSpr" style="opacity: 0.2;">
                        <hr>
                    </div>
                    <div class="cartMobBottomBlockEmpty" style="min-height: 30px;">
                    </div>
                </div>
            </div>
        </div>
        <script>
            window.cartListData = <?php echo json_encode(WC()->cart->get_cart()); ?>;
        </script>


        <div class="detailBlock">
            <?php get_template_part("patterns/new-products-slider"); ?>
        </div>
    <?php } else { ?>
        <div class="emptyCart">
            <div class="emptyCartWrap">
                <div class="emptyCartHead">
                    <div class="emptyCartHeadImg">
                        <svg width="64" height="64" viewBox="0 0 64 64" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M32 0L45.92 7.88985L59.7128 16L59.84 32L59.7128 48L45.92 56.1101L32 64L18.08 56.1101L4.28719 48L4.16 32L4.28719 16L18.08 7.88985L32 0Z"
                                fill="#F0C224" />
                            <path d="M27.0093 27.9996V25.9996C27.0093 23.2382 29.2479 20.9996 32.0093 20.9996C34.7707 20.9996 37.0093 23.2382 37.0093 25.9996V27.9996H40.0093C40.5616 27.9996 41.0093 28.4473 41.0093 28.9996V40.9997C41.0093 41.5519 40.5616 41.9997 40.0093 41.9997H24.0093C23.457 41.9997 23.0093 41.5519 23.0093 40.9997V28.9996C23.0093 28.4473 23.457 27.9996 24.0093 27.9996H27.0093ZM27.0093 29.9996H25.0093V39.9997H39.0093V29.9996H37.0093V31.9997H35.0093V29.9996H29.0093V31.9997H27.0093V29.9996ZM29.0093 27.9996H35.0093V25.9996C35.0093 24.3428 33.6661 22.9996 32.0093 22.9996C30.3524 22.9996 29.0093 24.3428 29.0093 25.9996V27.9996Z"
                                fill="#252525" />
                        </svg>
                    </div>
                </div>
                <div class="emptyCartHeading">
                    <div class="emptyCartHeadingTitle">В корзине пока нет товаров</div>
                    <div class="emptyCartHeadingDesc">Здесь появятся товары, которые вы добавите в корзину</div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
<?php
get_template_part('template-parts/product_card/prd-idea-modal-pc', null, array('modal-type' => 'update'));

?>
<style>
    p:empty {
        display: none;
    }

    a {
        text-decoration: none;
    }
</style>
<?php
// Re-enable wpautop after the template
add_filter('the_content', 'wpautop');
?>