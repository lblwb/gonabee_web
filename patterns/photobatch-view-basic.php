<?php
/**
 * Title: PhotoBatch View Basic
 * Slug: ktsportwear/photobatch-view-basic
 * @package WordPress
 */


// Получаем последние товары (например, 10 новых товаров по дате добавления)
$args = [
    'post_type' => 'product',
    'posts_per_page' => 10,
    'orderby' => 'date',
    'order' => 'DESC',


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
                    Фотоотзывы
                </div>
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
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
<!--                <div class="sliderNavBtnNext">-->
<!--                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">-->
<!--                        <rect x="40" width="40" height="40" rx="20" transform="rotate(90 40 0)" fill="white"/>-->
<!--                        <path d="M20.9762 20.0014L16.8514 15.8766L18.03 14.6981L23.3333 20.0014L18.0299 25.3047L16.8514 24.1262L20.9762 20.0014Z"-->
<!--                              fill="#252525"/>-->
<!--                    </svg>-->
<!--                </div>-->
<!--                <div class="sliderNavBtnPrev">-->
<!--                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">-->
<!--                        <rect width="40" height="40" rx="20" transform="matrix(4.37114e-08 1 1 -4.37114e-08 0 0)"-->
<!--                              fill="white"/>-->
<!--                        <path d="M19.0238 20.0014L23.1486 15.8766L21.9701 14.6981L16.6667 20.0014L21.9701 25.3047L23.1486 24.1262L19.0238 20.0014Z"-->
<!--                              fill="#252525"/>-->
<!--                    </svg>-->
<!--                </div>-->
            </div>
        </div>
    </div>
<?php endif; ?>
