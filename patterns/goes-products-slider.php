<?php

/**
 * Title: New Goes Together WCM Product Slider
 * Slug: ktsportwear/goes-products-slider
 * @package WordPress
 */

// Получаем последние товары (например, 10 новых товаров по дате добавления)
$args = [
    'post_type' => 'product',
    'posts_per_page' => 16,
    'orderby' => 'date',
    'order' => 'ASC',


    'tax_query' => [
        'relation' => 'AND', // <-- важно
        [
            'taxonomy' => 'product_visibility',
            'field' => 'name',
            'terms' => 'exclude-from-catalog',
            'operator' => 'NOT IN',
        ],
    ],

    'meta_query' => [
        [
            'key' => '_stock_status',
            'value' => 'instock',
        ]
    ],
];

$loop = new WP_Query($args);

// Проверка, есть ли товары
if ($loop->have_posts()) : ?>
    <div class="newProductsPreview">
        <div class="newProductsPreviewWrapper gridWrap">
            <div class="newProductsPreviewHeading" style="margin-bottom: 40px;">
                <div class="newProductsPreviewHeadingTitle">
                    Хорошо сочетается
                </div>
            </div>
            <div class="sliderRelativeWrapper">
                <div class="sliderNavBtnPrev">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="40" height="40" rx="20" transform="matrix(4.37114e-08 1 1 -4.37114e-08 0 0)"
                            fill="white" />
                        <path d="M19.0238 20.0014L23.1486 15.8766L21.9701 14.6981L16.6667 20.0014L21.9701 25.3047L23.1486 24.1262L19.0238 20.0014Z"
                            fill="#252525" />
                    </svg>
                </div>
                <div class="newProductsPreviewSlider swiper">
                    <div class="swiper-wrapper">
                        <?php while ($loop->have_posts()) : $loop->the_post();
                            global $product; ?>
                            <div class="swiper-slide previewSliderItem">
                                <div class="previewSliderItemBlock">
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
                                                        $image_url = wp_get_attachment_image_url($attachment_id, 'woocommerce_single');
                                                        $color_id = get_post_meta($attachment_id, '_color_id', true); // Например, мета-поле у картинки
                                                        if (!$color_id) $color_id = 'gray'; // fallback
                                                        $colors[$color_id] = true;
                                                    ?>
                                                        <div class="itemImageSubGalleryItem swiper-slide"
                                                            data-href="<?php echo esc_url(get_permalink($product->get_id())); ?>"
                                                            @click="showProductNav"
                                                            data-color-id="<?php echo esc_attr($color_id); ?>">
                                                            <img src="<?php echo esc_url($image_url); ?>" />

                                                        </div>

                                                    <?php endforeach; ?>

                                                </div>

                                                <!--pag-->
                                                <div class="bottomPaginate"></div>
                                                <!-- If we need pagination -->

                                            </div>
                                        </div>
                                        <?php
                                        $main_thumb_url = "";
                                        $main_thumb_id = $product->get_image_id();
                                        if ($main_thumb_id) {
                                            $main_thumb_url = wp_get_attachment_image_url($main_thumb_id, 'original');
                                        }
                                        ?>
                                        <div class="previewSliderItemWhiteList" id="favBtnPrd"
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


                                    <div class="previewSliderItemBlockHeading">
                                        <div class="itemBlockHeadingTitle">
                                            <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>"
                                                style="color:#1F1F1F; text-decoration: none;"><?php echo $product->get_name(); ?></a>
                                        </div>
                                        <div class="itemBlockHeadingPrice">
                                            <?php echo wc_price($product->get_price()); ?>
                                        </div>

                                        <?php if (have_rows('product_colors')): ?>
                                            <div class="colorSelectorWrapper" style="display: flex;justify-content: space-between; align-items: center;">
                                                <div class="colorScrollBtn __left" aria-label="Scroll left"><svg height="16" width="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" focusable="false" role="img" data-lll-pl="icon" aria-hidden="true">
                                                        <path d="M11 15 4.54 8.53a.74.74 0 0 1 0-1.06L11 1l.35.35a1 1 0 0 1 0 1.41L6.13 8l5.26 5.24a1 1 0 0 1 0 1.41z" fill="currentColor" fill-rule="evenodd"></path>
                                                    </svg></div>
                                                <div class="itemBlockHeadingSelColor" style="flex: auto; padding: 0 3px;">
                                                    <?php while (have_rows('product_colors')): the_row();
                                                        $color_rel = get_sub_field('color_rel');
                                                        $color_post = is_array($color_rel) ? $color_rel[0] : null;
                                                        $color_id = $color_post->ID;
                                                        $color_name = get_the_title($color_id);
                                                        $color_code = get_field('color_code', $color_id);
                                                        $color_slug = get_field('color_slug', $color_id); ?><div class="colorBox">
                                                            <div class="color-circle" title="<?= esc_attr($color_name) ?>" onclick="window.states.headMainNav.selectCartColorProduct('<?= get_permalink($product->ID) ?>', '<?= $color_slug ?>')" data-color-id="<?= esc_attr($color_slug); ?>" style="background-color:<?= esc_attr($color_code); ?>;"></div>
                                                        </div>
                                                    <?php endwhile; ?>
                                                </div>
                                                <div class="colorScrollBtn __right" aria-label="Scroll right"><svg height="16" width="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" focusable="false" role="img" data-lll-pl="icon" aria-hidden="true">
                                                        <path d="m5 15 6.5-6.47a.74.74 0 0 0 0-1.06L5 1l-.35.35a1 1 0 0 0 0 1.41L9.87 8l-5.26 5.24a1 1 0 0 0 0 1.41z" fill="currentColor" fill-rule="evenodd"></path>
                                                    </svg></div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                    </div>
                </div>

                <div class="sliderNavBtnNext">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="40" width="40" height="40" rx="20" transform="rotate(90 40 0)" fill="white" />
                        <path d="M20.9762 20.0014L16.8514 15.8766L18.03 14.6981L23.3333 20.0014L18.0299 25.3047L16.8514 24.1262L20.9762 20.0014Z"
                            fill="#252525" />
                    </svg>
                </div>
            </div>

        </div>
    </div>
<?php endif; ?>