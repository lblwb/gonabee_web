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


<div class="previewSliderItem">
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
                                            fill="white" />
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
                                            fill="white" />
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
                                            fill="white" />
                                    </svg>
                                </div>
                                <div class="badgeTitle">Тренд</div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <?php
                $main_thumb_url = "";
                $main_thumb_id = $product->get_image_id();
                if ($main_thumb_id) {
                    $main_thumb_url = wp_get_attachment_image_url($main_thumb_id, 'woocommerce_single');
                }
                ?>
                <div class="previewSliderItemWhiteList" id="favBtnPrd" v-cloak
                    data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                    data-nonce="<?php echo esc_attr(wp_create_nonce('toggle_favorite_nonce')); ?>"
                    data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
                    <div class="whiteListBtn" style="background: #FFFFFF;" @click.stop="addToWhtListMob({imageUrl:'<?php echo $main_thumb_url ?>'})">
                        <!--                        {{getSelectedWhtLst(-->
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

        </div>

             <div class="previewSliderItemImage">
            <?php
            // 1) Собираем все изображения товара
            $gallery_ids = $product->get_gallery_image_ids();
            $all_ids     = array_merge(
                [ get_post_thumbnail_id( $product->get_id() ) ],
                $gallery_ids
            );

            // 2) Сначала пытаемся взять дефолтный цвет из ACF, иначе — из первого изображения
            $def_color = '';
            $product_colors = get_field('product_colors');
            if ( ! empty( $product_colors ) && is_array( $product_colors ) ) {
                $first_rel   = $product_colors[0]['color_rel'] ?? null;
                $first_post  = is_array($first_rel) ? $first_rel[0] : null;
                if ( $first_post ) {
                    $def_color = get_field('color_slug', $first_post->ID) ?: '';
                }
            }
            if ( $def_color === '' && ! empty( $all_ids ) ) {
                // fallback — мета первого attachment
                $first_meta = get_post_meta( $all_ids[0], '_color_id', true ) ?: 'none';
                $def_color  = $first_meta;
            }
            ?>
            <div
                    class="previewSliderItemImageWrapper"
                    data-product-id="<?php echo esc_attr( $product->get_id() ); ?>"
                    data-def-color="<?php echo esc_attr( $def_color ); ?>"
            >
                <!-- дефолтный слайдер -->
                <div
                        class="previewSliderItemImageSubGallery swiper"
                        data-def-color-other="0"
                        v-show="isActiveColorSlider(<?php echo esc_attr( $product->get_id() ); ?>, 'none')"
                >
                    <div class="itemImageSubGalleryWrap swiper-wrapper">
                        <?php foreach ( $all_ids as $att_id ) :
                            $img_url  = wp_get_attachment_image_url( $att_id, 'woocommerce_single' );
                            $color_id = get_post_meta( $att_id, '_color_id', true ) ?: 'none';
                            ?>
                            <div
                                    class="itemImageSubGalleryItem swiper-slide"
                                    ondblclick="window.location.replace('<?php echo esc_url(get_permalink($product->get_id())); ?>')"
                                    ontouchend="window.location.replace('<?php echo esc_url(get_permalink($product->get_id())); ?>')"
                                    data-color-id="'none'"
                                    style="background-image:url('<?php echo esc_url( $img_url ); ?>');"
                            ></div>
                        <?php endforeach; ?>
                    </div>
                    <div class="bottomPaginate"></div>
                </div>

                <!-- остальные цвета -->
                <?php if ( have_rows('product_colors') ): ?>
                    <?php while ( have_rows('product_colors') ): the_row();
                        $rel_post = get_sub_field('color_rel');
                        $post_obj = is_array($rel_post) ? $rel_post[0] : null;
                        $slug     = $post_obj ? get_field('color_slug', $post_obj->ID) : '';
                        $imgs     = get_sub_field('color_images') ?: [];
                        ?>
                        <div
                                class="previewSliderItemImageSubGallery swiper"
                                style="display:none"
                                data-def-color-other="1"
                                v-show="isActiveColorSlider(<?php echo esc_attr( $product->get_id() ); ?>, '<?php echo esc_attr( $slug ); ?>')"
                        >
                            <div class="itemImageSubGalleryWrap swiper-wrapper">
                                <?php foreach ( $imgs as $img ): ?>
                                    <?php $url = wp_get_attachment_image_url($img['id'], 'original'); ?>
                                    <div
                                            class="itemImageSubGalleryItem swiper-slide"
                                            ondblclick="window.location.replace('<?php echo esc_url(get_permalink($product->get_id())); ?>')"
                                            ontouchend="window.location.replace('<?php echo esc_url(get_permalink($product->get_id())); ?>')"
                                            data-color-id="<?php echo esc_attr( $slug ); ?>"
                                            style="background-image:url('<?php echo esc_url( $url ); ?>');"
                                    ></div>
                                <?php endforeach; ?>
                            </div>
                            <div class="bottomPaginate"></div>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="previewSliderItemBlockHeading">
            <div class="itemBlockHeadingTitle">
                <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>"><?php echo $product->get_name(); ?></a>
            </div>
            <div class="itemBlockHeadingPrice">
                <?php if($product->is_in_stock()): ?>
                    <?php echo wc_price($product->get_price()); ?>
                <? else: ?>
                    Товара нет в наличии
                <? endif; ?>
            </div>
             <?php if (have_rows('product_colors')): ?>
                <div class="colorSelectorWrapper" style="display: flex;justify-content: space-between; align-items: center;">
                <div class="colorScrollBtn __left" aria-label="Scroll left"><svg height="16" width="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" focusable="false" role="img" data-lll-pl="icon" aria-hidden="true"><path d="M11 15 4.54 8.53a.74.74 0 0 1 0-1.06L11 1l.35.35a1 1 0 0 1 0 1.41L6.13 8l5.26 5.24a1 1 0 0 1 0 1.41z" fill="currentColor" fill-rule="evenodd"></path></svg></div>
                <div class="itemBlockHeadingSelColor" style="flex: auto; padding: 0 3px;">
                    <?php while (have_rows('product_colors')): the_row();
                        $color_rel = get_sub_field('color_rel');
                        $color_post = is_array($color_rel) ? $color_rel[0] : null;
                        $color_id = $color_post->ID;
                        $color_name = get_the_title($color_id);
                        $color_code = get_field('color_code', $color_id);
                        $color_slug = get_field('color_slug', $color_id); ?><div class="colorBox"><div class="color-circle" title="<?= esc_attr($color_name) ?>" onclick="window.states.slider_<?= $product->get_id(); ?>.selectColor('<?= esc_attr($color_slug); ?>')" data-color-id="<?= esc_attr($color_slug); ?>" style="background-color:<?= esc_attr($color_code); ?>;"></div></div>
                    <?php endwhile; ?>
                </div>
                  <div class="colorScrollBtn __right" aria-label="Scroll right"><svg height="16" width="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" focusable="false" role="img" data-lll-pl="icon" aria-hidden="true"><path d="m5 15 6.5-6.47a.74.74 0 0 0 0-1.06L5 1l-.35.35a1 1 0 0 0 0 1.41L9.87 8l-5.26 5.24a1 1 0 0 0 0 1.41z" fill="currentColor" fill-rule="evenodd"></path></svg></div>
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