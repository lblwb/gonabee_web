<?php

// Получаем заказы текущего пользователя
$active_orders = wc_get_orders([
    'customer_id' => get_current_user_id(),
    'status'      => ['pending', 'processing', 'on-hold', 'draft'],
    'orderby'     => 'date',
    'order'       => 'DESC',
    'limit'       => -1,
]);

// Архивные заказы
$archived_orders = wc_get_orders([
    'customer_id' => get_current_user_id(),
    'status'      => ['failed', 'cancelled', 'completed'],
    'orderby'     => 'date',
    'order'       => 'DESC',
    'limit'       => -1,
]);

if (empty($active_orders) && empty($archived_orders)) {
    echo '<p>Вы ещё не сделали ни одного заказа.</p>';
    return;
}



?>


<div class="orderPage">
    <div class="orderPageBlock" style="">
        <div class="orderPageBlockWrapper" style="">
            <div class="orderPageTitle">
                История заказов
            </div>

            <div class="orderPageBlockTab" style="">
                <div class="orderPageBlockTabWrapper">
                    <?php if (!empty($active_orders)) : ?>
                        <div class="orderPageBlockTabItem __Active" data-target="#activeOrderWrapper">
                            Активные заказы
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($archived_orders)): ?>
                        <div class="orderPageBlockTabItem" data-target="#archiveOrderWrapper">
                            Архивные заказы
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($active_orders)) : ?>
                <div class="orderPageBlockContent __Active" id="activeOrderWrapper">
                    <div class="orderList">
                        <?php foreach ($active_orders as $order) : ?>
                            <?php
                            $order_id = $order->get_id();
                            $shipping_raw = $order->get_meta('shipping_raw');
                            $status = wc_get_order_status_name($order->get_status());
                            $total = $order->get_formatted_order_total();
                            $created = $order->get_date_created()->date_i18n('d.m.Y H:i');
                            $items = $order->get_items();
                            $items_total = count($order->get_items());
                            $delivery_address = $order->get_address();
                            ?>
                            <div class="orderBlock" style="">
                                <div class="orderHeading">
                                    <div class="orderItemHeading"
                                        style="">
                                        <div class="orderItemHeadingTitle">
                                            Заказ № <?php echo $order_id ?> на <span
                                                class="__Act"><?php echo $total ?></span>
                                            <!--                    echo "{$name} — {$qty} шт.";-->
                                        </div>
                                        <div class="orderItemHeadingStatus <?= $status_class ?>"
                                            style="">
                                            <div class="orderItemHeadingIcon">
                                                <svg width="8" height="8" viewBox="0 0 8 8" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="4" cy="4" r="4" fill="currentColor" />
                                                </svg>
                                            </div>
                                            <div class="orderItemHeadingTitle">
                                                <?php echo $status ?>
                                            </div>
                                        </div>
                                        <button class="orderBtnToggle">
                                            <svg width="14" height="8" viewBox="0 0 14 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M7.00063 2.83153L11.9504 7.78125L13.3646 6.36704L7.00063 0.00302982L0.636719 6.36704L2.05093 7.78125L7.00063 2.83153Z" fill="#252525" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="orderItemDelivery" style="">
                                    <?php
                                    if ($shipping_raw) {
                                        echo $shipping_raw;
                                    } else {
                                        echo 'Будет доставлен по адресу' . $delivery_address['billing'];
                                    }
                                    ?>
                                </div>

                                <div class="orderLineBottom"
                                    style=""></div>

                                <div class="orderProductsTotal">
                                    Товары в заказе, <?php echo $items_total ?> шт
                                </div>

                                <div class="orderProducts" style="display: flex; flex-flow: column; gap: 20px">
                                    <?php
                                    foreach ($items as $cart_item) {
                                        $product = $cart_item->get_product();
                                        $meta_data = $cart_item->get_meta_data();
                                        $name = $cart_item->get_name();
                                        $qty = $cart_item->get_quantity();
                                        $status = $cart_item->get_tax_status();

                                        //
                                        $product_permalink = $product->is_visible() ? $product->get_permalink($cart_item) : '';
                                        //                                    $thumbnail = $product->get_image('woocommerce_thumbnail');
                                        $product_name = $product->get_name();
                                        //                                    $quantity = $cart_item['quantity'];
                                        $price = wc_price($product->get_price());
                                        //                                    $variation = wc_get_formatted_cart_item_data($cart_item, true);
                                        $attachment_ids = $product->get_gallery_image_ids();
                                        $cart_item_key = "";

                                        $image_id = $product->get_image_id();
                                        $image_url = '';
                                        $color_id = $cart_item->get_meta('color_rel', true);
                                        if (str_to_bool($color_id)) {
                                            // Заполняем переменные для цветов
                                            $color_post = get_post($color_id);
                                            if ($color_post) {
                                                $color_slug = get_field('color_slug', $color_post->ID);
                                                $color_code = get_field('color_code', $color_post->ID);
                                                $color_name = get_the_title($color_post->ID);
                                            }

                                            // Получаем картинку товара по цвету
                                            $color_list = get_field('product_colors', $product->get_id());
                                            foreach ($color_list as $color_group) {
                                                $current_color_id = $color_group['color_rel'][0]->ID;
                                                if (empty($color_group['color_rel']) || (int) $current_color_id !== (int) $color_id) continue;


                                                $image_url = $color_group['color_images'][0]['url'];
                                            }
                                        } else if ($image_id) {
                                            $image_url = wp_get_attachment_image_url($image_id, 'medium_large');
                                        }

                                        $size = false;
                                        // Проверяем есть ли у товара атрибуты размеров, что-бы не вытащить размер по умолчанию
                                        if ($product->get_attribute('pa_sizes')) {
                                            $size = $cart_item->get_meta('pa_size', true);
                                        }
                                    ?>

                                        <div class="orderProductItem" style="">
                                            <div class="cartPageBodyListItemProduct">
                                                <div class="bodyListItemProductWrapper">
                                                    <div class="bodyListItemProductInfo">
                                                        <div class="bodyListItemProductInfoWrapper">
                                                            <div class="bodyListItemProductInfoWrap">
                                                                <div class="bodyListItemProductInfoCard">
                                                                    <div class="bodyListItemProductInfoCardTop">
                                                                        <div class="infoCardTopWhlBtn" id="favBtnPrd"
                                                                            data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                                                                            data-nonce="<?php echo esc_attr(wp_create_nonce('toggle_favorite_nonce')); ?>"
                                                                            data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
                                                                            <div class="infoCardTopWhlBtnIcon whiteListBtn"
                                                                                style="background: rgb(255, 255, 255);"
                                                                                @click.stop="addToWhtListMob({imageUrl:'<?php echo esc_url($image_url) ?>', productId: <?php echo esc_attr($product->get_id()); ?>})">
                                                                                <div class="whiteListBtnIcon" v-if="!appFavoriteBtn.status.active">
                                                                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                                        <path d="M7.99839 3.01902C9.56439 1.61333 11.9844 1.66 13.4928 3.17157C15.0013 4.68315 15.0531 7.09133 13.6501 8.662L7.99765 14.3233L2.34531 8.662C0.94242 7.09133 0.99486 4.67934 2.50263 3.17157C4.0121 1.6621 6.42785 1.61125 7.99839 3.01902ZM12.5491 4.1134C11.5497 3.11196 9.93612 3.07134 8.88905 4.01125L7.99899 4.81016L7.10845 4.01187C6.05837 3.07065 4.44776 3.11205 3.44543 4.11438C2.45227 5.10754 2.40241 6.6982 3.31767 7.7488L7.99765 12.4362L12.6778 7.7488C13.5934 6.6978 13.5437 5.11017 12.5491 4.1134Z"
                                                                                            fill="#1F1F1F" />
                                                                                    </svg>
                                                                                </div>
                                                                                <div class="whiteListBtnIcon" v-else>
                                                                                    <svg width="30" height="30" viewBox="0 0 30 30" fill="none"
                                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                                        <rect width="30" height="30" rx="15" fill="white" />
                                                                                        <path d="M14.9994 10.019C16.5654 8.61333 18.9854 8.66 20.4938 10.1716C22.0022 11.6831 22.054 14.0913 20.6511 15.662L14.9986 21.3233L9.34628 15.662C7.9434 14.0913 7.99584 11.6793 9.5036 10.1716C11.0131 8.6621 13.4288 8.61125 14.9994 10.019Z"
                                                                                            fill="#CE1B19" />
                                                                                    </svg>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="bodyListItemProductInfoCardImage"
                                                                        style="background-image: url('<?php echo esc_url($image_url); ?>');"></div>
                                                                </div>
                                                            </div>
                                                            <div class="infoCardHeading"
                                                                style="display: flex;flex-flow: column;justify-content: space-between;">
                                                                <div class="infoCardHeadingSpWrap">
                                                                    <div class="infoCardHeadingTop">
                                                                        <div class="infoCardHeadingTitle">
                                                                            <?php echo $product_name; ?>
                                                                        </div>
                                                                        <div class="infoCardHeadingTitle">
                                                                            <?php echo $price; ?>
                                                                        </div>
                                                                        <?php if (str_to_bool($color_id) || str_to_bool($size)) : ?>
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

                                                                                    <?php if ($size) : ?>
                                                                                        <div class="horizontalListItem">
                                                                                            <?= $size ?>
                                                                                        </div>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                        <div class="infoCardHeadingQty" style="
                                                                            font-family: 'Montserrat',sans-serif;
                                                                            font-style: normal;
                                                                            font-weight: 500;
                                                                            font-size: 16px;
                                                                            line-height: 125%;
                                                                            /* identical to box height, or 18px */
                                                                            color: #252525;
                                                                            ">
                                                                            <?php echo $qty; ?> шт
                                                                        </div>
                                                                    </div>
                                                                    <div class="infoCardHeadingBottom">
                                                                        <div class="infoCardHeadingBottomWrapper">

                                                                        </div>
                                                                    </div>
                                                                </div>


                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="bodyListItemProductView">
                                                        <div class="bodyListItemProductViewWrapper"
                                                            style="display: flex;flex-flow: column;justify-content: space-between;height: 100%;align-items: flex-end;align-content: space-between;">
                                                            <div class="bodyListItemProductViewTop">
                                                            </div>
                                                            <div class="bodyListItemProductViewBottom">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (!empty($archived_orders)): ?>
                <div class="orderPageBlockContent" id="archiveOrderWrapper">
                    <div class="orderList">
                        <?php foreach ($archived_orders as $order) : ?>
                            <?php
                            $order_id = $order->get_id();
                            $shipping_raw = $order->get_meta('shipping_raw');
                            $status = wc_get_order_status_name($order->get_status());
                            $total = $order->get_formatted_order_total();
                            $created = $order->get_date_created()->date_i18n('d.m.Y H:i');
                            $items = $order->get_items();
                            $items_total = count($order->get_items());
                            $delivery_address = $order->get_address();
                            $status_class = $status == strtolower('Отменён') ? '__Red' : '';
                            ?>
                            <div class="orderBlock" style="">
                                <div class="orderHeading">
                                    <div class="orderItemHeading"
                                        style="">
                                        <div class="orderItemHeadingTitle">
                                            Заказ № <?php echo $order_id ?> на <span
                                                class="__Act"><?php echo $total ?></span>
                                            <!--                    echo "{$name} — {$qty} шт.";-->
                                        </div>
                                        <div class="orderItemHeadingStatus <?= $status_class ?>"
                                            style="">
                                            <div class="orderItemHeadingIcon">
                                                <svg width="8" height="8" viewBox="0 0 8 8" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="4" cy="4" r="4" fill="currentColor" />
                                                </svg>
                                            </div>
                                            <div class="orderItemHeadingTitle">
                                                <?php echo $status ?>
                                            </div>
                                        </div>
                                        <button class="orderBtnToggle">
                                            <svg width="14" height="8" viewBox="0 0 14 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M7.00063 2.83153L11.9504 7.78125L13.3646 6.36704L7.00063 0.00302982L0.636719 6.36704L2.05093 7.78125L7.00063 2.83153Z" fill="#252525" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="orderItemDelivery" style="">
                                    <?php
                                    if ($shipping_raw) {
                                        echo $shipping_raw;
                                    } else {
                                        echo 'Будет доставлен по адресу' . $delivery_address['billing'];
                                    }
                                    ?>
                                </div>

                                <div class="orderLineBottom"
                                    style=""></div>

                                <div class="orderProductsTotal">
                                    Товары в заказе, <?php echo $items_total ?> шт
                                </div>

                                <div class="orderProducts" style="display: flex; flex-flow: column; gap: 20px">
                                    <?php
                                    foreach ($items as $cart_item) {
                                        $product = $cart_item->get_product();
                                        $meta_data = $cart_item->get_meta_data();
                                        $name = $cart_item->get_name();
                                        $qty = $cart_item->get_quantity();
                                        $status = $cart_item->get_tax_status();

                                        //
                                        $product_permalink = $product->is_visible() ? $product->get_permalink($cart_item) : '';
                                        //                                    $thumbnail = $product->get_image('woocommerce_thumbnail');
                                        $product_name = $product->get_name();
                                        //                                    $quantity = $cart_item['quantity'];
                                        $price = wc_price($product->get_price());
                                        //                                    $variation = wc_get_formatted_cart_item_data($cart_item, true);
                                        $attachment_ids = $product->get_gallery_image_ids();
                                        $cart_item_key = "";

                                        $image_id = $product->get_image_id();
                                        $image_url = '';
                                        $color_id = $cart_item->get_meta('color_rel', true);
                                        if (str_to_bool($color_id)) {
                                            // Заполняем переменные для цветов
                                            $color_post = get_post($color_id);
                                            if ($color_post) {
                                                $color_slug = get_field('color_slug', $color_post->ID);
                                                $color_code = get_field('color_code', $color_post->ID);
                                                $color_name = get_the_title($color_post->ID);
                                            }

                                            // Получаем картинку товара по цвету
                                            $color_list = get_field('product_colors', $product->get_id());
                                            foreach ($color_list as $color_group) {
                                                $current_color_id = $color_group['color_rel'][0]->ID;
                                                if (empty($color_group['color_rel']) || (int) $current_color_id !== (int) $color_id) continue;


                                                $image_url = $color_group['color_images'][0]['url'];
                                            }
                                        } else if ($image_id) {
                                            $image_url = wp_get_attachment_image_url($image_id, 'medium_large');
                                        }

                                        $size = false;
                                        // Проверяем есть ли у товара атрибуты размеров, что-бы не вытащить размер по умолчанию
                                        if ($product->get_attribute('pa_sizes')) {
                                            $size = $cart_item->get_meta('pa_size', true);
                                        }
                                    ?>

                                        <div class="orderProductItem" style="">
                                            <div class="cartPageBodyListItemProduct">
                                                <div class="bodyListItemProductWrapper">
                                                    <div class="bodyListItemProductInfo">
                                                        <div class="bodyListItemProductInfoWrapper">
                                                            <div class="bodyListItemProductInfoWrap">
                                                                <div class="bodyListItemProductInfoCard">
                                                                    <div class="bodyListItemProductInfoCardTop">
                                                                        <div class="infoCardTopWhlBtn" id="favBtnPrd"
                                                                            data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                                                                            data-nonce="<?php echo esc_attr(wp_create_nonce('toggle_favorite_nonce')); ?>"
                                                                            data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
                                                                            <div class="infoCardTopWhlBtnIcon whiteListBtn"
                                                                                style="background: rgb(255, 255, 255);"
                                                                                @click.stop="addToWhtListMob({imageUrl:'<?php echo esc_url($image_url) ?>', productId: <?php echo esc_attr($product->get_id()); ?>})">
                                                                                <div class="whiteListBtnIcon" v-if="!appFavoriteBtn.status.active">
                                                                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                                        <path d="M7.99839 3.01902C9.56439 1.61333 11.9844 1.66 13.4928 3.17157C15.0013 4.68315 15.0531 7.09133 13.6501 8.662L7.99765 14.3233L2.34531 8.662C0.94242 7.09133 0.99486 4.67934 2.50263 3.17157C4.0121 1.6621 6.42785 1.61125 7.99839 3.01902ZM12.5491 4.1134C11.5497 3.11196 9.93612 3.07134 8.88905 4.01125L7.99899 4.81016L7.10845 4.01187C6.05837 3.07065 4.44776 3.11205 3.44543 4.11438C2.45227 5.10754 2.40241 6.6982 3.31767 7.7488L7.99765 12.4362L12.6778 7.7488C13.5934 6.6978 13.5437 5.11017 12.5491 4.1134Z"
                                                                                            fill="#1F1F1F" />
                                                                                    </svg>
                                                                                </div>
                                                                                <div class="whiteListBtnIcon" v-else>
                                                                                    <svg width="30" height="30" viewBox="0 0 30 30" fill="none"
                                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                                        <rect width="30" height="30" rx="15" fill="white" />
                                                                                        <path d="M14.9994 10.019C16.5654 8.61333 18.9854 8.66 20.4938 10.1716C22.0022 11.6831 22.054 14.0913 20.6511 15.662L14.9986 21.3233L9.34628 15.662C7.9434 14.0913 7.99584 11.6793 9.5036 10.1716C11.0131 8.6621 13.4288 8.61125 14.9994 10.019Z"
                                                                                            fill="#CE1B19" />
                                                                                    </svg>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="bodyListItemProductInfoCardImage"
                                                                        style="background-image: url('<?php echo esc_url($image_url); ?>');"></div>
                                                                </div>
                                                            </div>
                                                            <div class="infoCardHeading"
                                                                style="display: flex;flex-flow: column;justify-content: space-between;">
                                                                <div class="infoCardHeadingSpWrap">
                                                                    <div class="infoCardHeadingTop">
                                                                        <div class="infoCardHeadingTitle">
                                                                            <?php echo $product_name; ?>
                                                                        </div>
                                                                        <div class="infoCardHeadingTitle">
                                                                            <?php echo $price; ?>
                                                                        </div>
                                                                        <?php if (str_to_bool($color_id) || str_to_bool($size)) : ?>
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

                                                                                    <?php if ($size) : ?>
                                                                                        <div class="horizontalListItem">
                                                                                            <?= $size ?>
                                                                                        </div>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                        <div class="infoCardHeadingQty" style="
                                                                    font-family: 'Montserrat',sans-serif;
                                                                    font-style: normal;
                                                                    font-weight: 500;
                                                                    font-size: 16px;
                                                                    line-height: 125%;
                                                                    /* identical to box height, or 18px */
                                                                    color: #252525;
                                                                    ">
                                                                            <?php echo $qty; ?> шт
                                                                        </div>
                                                                    </div>
                                                                    <div class="infoCardHeadingBottom">
                                                                        <div class="infoCardHeadingBottomWrapper">

                                                                        </div>
                                                                    </div>
                                                                </div>


                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="bodyListItemProductView">
                                                        <div class="bodyListItemProductViewWrapper"
                                                            style="display: flex;flex-flow: column;justify-content: space-between;height: 100%;align-items: flex-end;align-content: space-between;">
                                                            <div class="bodyListItemProductViewTop">
                                                            </div>
                                                            <div class="bodyListItemProductViewBottom">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>