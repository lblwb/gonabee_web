<?php
/**
 * Title: Accessories View Home
 * Slug: ktsportwear/acсsr-view-home
 * @package WordPress
 */

$parent_acs_category = get_term_by('name', 'Аксессуары', 'product_cat');
$parent_acs_category = get_term_by('name', 'Аксессуары', 'product_cat');

$args = [
    'post_type' => 'product',
    'posts_per_page' => 10,
    'orderby' => 'date',
    'order' => 'DESC',
    'tax_query' => [
        'relation' => 'AND', // <-- важно
        [
            'taxonomy' => 'product_cat',
            'field' => 'term_id',
            'terms' => [$parent_acs_category->term_id],
            'include_children' => true,
        ],
        [
            'taxonomy' => 'product_cat',
            'field' => 'term_id',
            'terms' => [$parent_acs_category->term_id],
            'include_children' => true,
        ],
        [
            'taxonomy' => 'product_visibility',
            'field' => 'name',
            'terms' => ['exclude-from-catalog'],
            'operator' => 'NOT IN',
        ],
    ],
];

$loop = new WP_Query($args);

//var_dump($loop);
?>

<?php
// Проверка, есть ли товары
if ($loop->have_posts()) : ?>

    <div class="accesrPreview">
        <div class="accesrPreviewWrapper gridWrap">
            <div class="accesrPreviewHeading" style="margin-bottom: 40px;">
                <div class="accesrPreviewHeadingTitle">
                    Аксессуары
                </div>
            </div>
            <div class="accesrPreviewSlider swiper">
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
                                                    $image_url = wp_get_attachment_image_url($attachment_id, 'woocommerce_thumbnail');
                                                    $color_id = get_post_meta($attachment_id, '_color_id', true); // Например, мета-поле у картинки
                                                    if (!$color_id) $color_id = 'gray'; // fallback
                                                    $colors[$color_id] = true;
                                                    ?>
                                                    <div class="itemImageSubGalleryItem swiper-slide"
                                                         data-color-id="<?php echo esc_attr($color_id); ?>">
                                                        <img src="<?php echo esc_url($image_url); ?>"/>
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
                                        <?php echo $product->get_name(); ?>
                                    </div>
                                    <div class="itemBlockHeadingPrice">
                                        <?php echo wc_price($product->get_price()); ?>
                                    </div>

                                    <?php if (have_rows('product_colors')): ?>
                                        <div class="itemBlockHeadingSelColor">
                                            <?php while (have_rows('product_colors')): the_row();
                                                $color_name = get_sub_field('color_name');
                                                $color_code = get_sub_field('color_code');
                                                $color_slug = get_sub_field('color_slug');
                                                ?>
                                                <div class="colorBox">
                                                    <div class="color-circle" title="<?php echo esc_attr($color_name); ?>" data-color-id="<?php echo esc_attr($color_slug); ?>" style="background-color:<?php echo esc_attr($color_code); ?>;"></div>
                                                </div>
                                            <?php endwhile; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
                <div class="sliderNavBtnNext">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="40" width="40" height="40" rx="20" transform="rotate(90 40 0)" fill="white"/>
                        <path d="M20.9762 20.0014L16.8514 15.8766L18.03 14.6981L23.3333 20.0014L18.0299 25.3047L16.8514 24.1262L20.9762 20.0014Z"
                              fill="#252525"/>
                    </svg>
                </div>
                <div class="sliderNavBtnPrev">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="40" height="40" rx="20" transform="matrix(4.37114e-08 1 1 -4.37114e-08 0 0)"
                              fill="white"/>
                        <path d="M19.0238 20.0014L23.1486 15.8766L21.9701 14.6981L16.6667 20.0014L21.9701 25.3047L23.1486 24.1262L19.0238 20.0014Z"
                              fill="#252525"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
