<?php

$add_cart_btn = $args['add_cart_btn'] ?? false;

$post_id = $args['post_id'] ?? get_the_ID();

$product = wc_get_product($post_id);
if (!$product) return;

$product_link = get_permalink($post_id);
$product_title = get_the_title($post_id);
$product_price = $product->get_price_html();
$product_thumbnail = get_the_post_thumbnail($post_id, 'medium');

// Проверяем, трендовый ли товар
$is_trending = get_post_meta($post_id, 'is_trending', true) === '1';
$is_new = get_post_meta($post_id, 'is_new', true) === '1';
$is_sale = get_post_meta($post_id, 'has_discount', true) === '1';

?>


<div class="previewSliderItem" onclick="navigation.navigate('<?php echo esc_url(get_permalink($product->get_id())); ?>')">
    <div class="previewSliderItemBlock" style="position: relative;">
        <div class="itemBlockHeading">
            <div class="itemBlockHeadingWrapper" style="display: flex; justify-content: space-between;">
                <div class="previewSliderItemBadges">
                    <?php
                    // Приоритет: SALE > NEW > TREND
                    if ($is_sale): ?>
                        <div class="previewSliderItemBadge">
                            <div class="badgeWrapper" style="display: flex; flex-flow: row; align-items: center;">
                                <div class="badgeIcon" style="display: flex;">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10 19.1667C6.54822 19.1667 3.75 16.3684 3.75 12.9167C3.75 11.1218 4.50655 9.50375 5.71816 8.36383C6.83669 7.31146 9.58333 5.41626 9.16667 1.25C14.1667 4.58333 16.6667 7.91667 11.6667 12.9167C12.5 12.9167 13.75 12.9167 15.8333 10.858C16.0581 11.5027 16.25 12.1954 16.25 12.9167C16.25 16.3684 13.4517 19.1667 10 19.1667Z"
                                              fill="white"/>
                                    </svg>
                                </div>
                                <div class="badgeTitle">SALE</div>
                            </div>
                        </div>
                    <?php elseif ($is_new): ?>
                        <div class="previewSliderItemBadge">
                            <div class="badgeWrapper" style="display: flex; flex-flow: row; align-items: center;">
                                <div class="badgeIcon" style="display: flex;">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10 19.1667C6.54822 19.1667 3.75 16.3684 3.75 12.9167C3.75 11.1218 4.50655 9.50375 5.71816 8.36383C6.83669 7.31146 9.58333 5.41626 9.16667 1.25C14.1667 4.58333 16.6667 7.91667 11.6667 12.9167C12.5 12.9167 13.75 12.9167 15.8333 10.858C16.0581 11.5027 16.25 12.1954 16.25 12.9167C16.25 16.3684 13.4517 19.1667 10 19.1667Z"
                                              fill="white"/>
                                    </svg>
                                </div>
                                <div class="badgeTitle">NEW</div>
                            </div>
                        </div>
                    <?php elseif ($is_trending): ?>
                        <div class="previewSliderItemBadge">
                            <div class="badgeWrapper" style="display: flex; flex-flow: row; align-items: center;">
                                <div class="badgeIcon" style="display: flex;">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10 19.1667C6.54822 19.1667 3.75 16.3684 3.75 12.9167C3.75 11.1218 4.50655 9.50375 5.71816 8.36383C6.83669 7.31146 9.58333 5.41626 9.16667 1.25C14.1667 4.58333 16.6667 7.91667 11.6667 12.9167C12.5 12.9167 13.75 12.9167 15.8333 10.858C16.0581 11.5027 16.25 12.1954 16.25 12.9167C16.25 16.3684 13.4517 19.1667 10 19.1667Z"
                                              fill="white"/>
                                    </svg>
                                </div>
                                <div class="badgeTitle">Тренд</div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="previewSliderItemWhiteList" id="favBtnPrd"
                     data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                     data-nonce="<?php echo esc_attr(wp_create_nonce('toggle_favorite_nonce')); ?>"
                     data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
                    <div class="whiteListBtn" style="background: #FFFFFF;" @click="addToWhtListMob({imageUrl:''})">
                        <!--                        {{getSelectedWhtLst(-->
                        <div class="whiteListBtnIcon" v-if="!appFavoriteBtn.status.active">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.99839 3.01902C9.56439 1.61333 11.9844 1.66 13.4928 3.17157C15.0013 4.68315 15.0531 7.09133 13.6501 8.662L7.99765 14.3233L2.34531 8.662C0.94242 7.09133 0.99486 4.67934 2.50263 3.17157C4.0121 1.6621 6.42785 1.61125 7.99839 3.01902ZM12.5491 4.1134C11.5497 3.11196 9.93612 3.07134 8.88905 4.01125L7.99899 4.81016L7.10845 4.01187C6.05837 3.07065 4.44776 3.11205 3.44543 4.11438C2.45227 5.10754 2.40241 6.6982 3.31767 7.7488L7.99765 12.4362L12.6778 7.7488C13.5934 6.6978 13.5437 5.11017 12.5491 4.1134Z"
                                      fill="#1F1F1F"/>
                            </svg>
                        </div>
                        <div class="whiteListBtnIcon" v-else>
                            <svg width="30" height="30" viewBox="0 0 30 30" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <rect width="30" height="30" rx="15" fill="white"/>
                                <path d="M14.9994 10.019C16.5654 8.61333 18.9854 8.66 20.4938 10.1716C22.0022 11.6831 22.054 14.0913 20.6511 15.662L14.9986 21.3233L9.34628 15.662C7.9434 14.0913 7.99584 11.6793 9.5036 10.1716C11.0131 8.6621 13.4288 8.61125 14.9994 10.019Z"
                                      fill="#CE1B19"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="previewSliderItemImage">
            <div class="previewSliderItemImageWrapper">
                <div class="previewSliderItemImageSubGallery swiper">
                    <div class="itemImageSubGalleryWrap swiper-wrapper">
                        <?php
                        $gallery_images = $product->get_gallery_image_ids();
                        // Включаем главное изображение тоже
                        $all_images = array_merge(
                            [get_post_thumbnail_id($product->get_id())],
                            $gallery_images
                        );
                        foreach ($all_images as $attachment_id) :
                            $image_url = wp_get_attachment_image_url($attachment_id, 'woocommerce_thumbnail');
                            $color_id = get_post_meta($attachment_id, '_color_id', true); // Например, мета-поле у картинки
                            if (!$color_id) $color_id = 'gray'; // fallback
                            $colors[$color_id] = true;
                            ?>
                            <div class="itemImageSubGalleryItem swiper-slide"
                                 data-color-id="<?php echo esc_attr($color_id); ?>"
                                 data-href="<?php echo esc_url(get_permalink($product->get_id())); ?>"
                                 @click="showProductNav"
                                 style="background-image: url('<?php echo esc_url($image_url); ?>'); text-decoration: none;">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <!--pag-->
                    <div class="bottomPaginate"></div>
                </div>
            </div>
        </div>
        <div class="previewSliderItemBlockHeading">
            <div class="itemBlockHeadingTitle">
                <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>"><?php echo $product->get_name(); ?></a>
            </div>
            <div class="itemBlockHeadingPrice">
                <?php echo wc_price($product->get_price()); ?>
            </div>
            <?php if (have_rows('product_colors')): ?>
                <div class="itemBlockHeadingSelColor">
                    <?php while (have_rows('product_colors')): the_row();
                        $color_name = get_sub_field('color_name');
                        $color_code = get_sub_field('color_code');
                        $color_slug = get_sub_field('color_slug'); ?>
                        <div class="colorBox"
                             vibe-class="__Active:SparkVibe.getValueByPath('appShop.cart.select.color') === '<?php echo $color_slug ?>'">
                            <div class="color-circle"
                                 title="<?php echo esc_attr($color_name); ?>"
                                 @click="selectCartColorProduct"
                                 data-color-id="<?php echo esc_attr($color_slug); ?>"
                                 style="background-color:<?php echo esc_attr($color_code); ?>;"></div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>

            <?php if ($add_cart_btn) { ?>

                <a href="/cart/?add-to-cart=<?php echo $product->get_id(); ?>&quantity=1" class="itemBlockHeadingAddToCart" style="text-decoration: none;">
                    <div class="blockHeadingAddToCartBtn"
                         style="border: solid 1px #E2B53C; border-radius: 100px; color: #E2B53C; padding: 10px 20px;">
                        <div class="addToCartBtnHeading">
                            <div class="addToCartBtnHeadingTitle"
                                 style="font-family: 'Montserrat',sans-serif;font-weight: 600;font-size: 14px;line-height: 125%;letter-spacing: 0%;text-align: center;">
                                В корзину
                            </div>
                        </div>
                    </div>
                </a>
            <?php } ?>

        </div>
    </div>
</div>

