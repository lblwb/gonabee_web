<?php

/**
 * Title: Product Card
 * Slug: ktsportwear/product-card
 * @package WordPress
 */

if (!defined('ABSPATH')) {
    exit; // Защита от прямого доступа
}

$product_id = get_the_ID(); // или напрямую ID товара
$product = wc_get_product($product_id);

if (!$product || !$product->is_visible()) {
    return;
}

// Generate a nonce for AJAX
$nonce = wp_create_nonce('toggle_favorite_nonce');

$product_id = $product->get_id();
$favorite_nonce = wp_create_nonce('toggle_favorite_nonce');
$add_to_cart_nonce = wp_create_nonce('add_to_cart_nonce');
$ajax_url = esc_url(admin_url('admin-ajax.php'));

?>

<div class="shopProductDetailWrapper">
    <div class="shopProductDetailBody">
        <div class="shopProductDetailCard">
            <div class="shopProductDetailCardWrapper gridWrap __mobGridUnset">
                <?php
                // Новая структура для хранения изображений по цветам
                $images_by_color = [];
                $default_color_slug = 'default'; // Устанавливаем значение по умолчанию
                $default_color_code = '';
                $default_color_name = '';
                $default_color_id = '';

                // 1. Добавляем изображения из галереи WooCommerce как "default" цвет
                $default_images = [];
                $attachment_ids = $product->get_gallery_image_ids();
                $main_thumb_url = '';

                if (!empty($attachment_ids)) {
                    foreach ($attachment_ids as $attachment_id) {
                        $thumb_url = wp_get_attachment_image_url($attachment_id, 'original');
                        if ($thumb_url) {
                            $default_images[] = esc_url($thumb_url);
                        }
                    }
                } else {
                    // Если галерея пуста — выводим только основное изображение
                    $main_thumb_id = $product->get_image_id();
                    if ($main_thumb_id) {
                        $main_thumb_url = wp_get_attachment_image_url($main_thumb_id, 'original');
                        if ($main_thumb_url) {
                            $default_images[] = esc_url($main_thumb_url);
                        }
                    }
                }

                // 2. Добавляем изображения из ACF поля 'color_images' для каждого цвета
                if (have_rows('product_colors')) {
                    $color_index = 0;
                    while (have_rows('product_colors')) {
                        the_row();
                        $color_rel = get_sub_field('color_rel');
                        $color_post = is_array($color_rel) && !empty($color_rel) ? $color_rel[0] : null;

                        if ($color_post) {
                            $color_id = $color_post->ID;
                            $color_slug = get_field('color_slug', $color_id);
                            $color_name = get_the_title($color_id);
                            $color_code = get_field('color_code', $color_id);

                            // Проверяем, что color_slug не пустой
                            if (empty($color_slug)) {
                                $color_slug = 'color-' . $color_id; // Запасной slug, если color_slug пуст
                            }

                            // Устанавливаем первый цвет как дефолтный
                            if ($color_index === 0) {
                                $default_color_slug = $color_slug;
                                $default_color_code = $color_code;
                                $default_color_name = $color_name;
                                $default_color_id = $color_id;
                            }

                            // Получаем изображения для этого цвета
                            $color_images = get_sub_field('color_images', $color_id) ?: [];
                            $color_image_urls = [];

                            if (!empty($color_images) && is_array($color_images)) {
                                foreach ($color_images as $image) {
                                    if (is_array($image) && isset($image['url'])) {
                                        $color_image_urls[] = esc_url($image['url']);
                                    } elseif (is_numeric($image)) {
                                        $url = wp_get_attachment_image_url($image, 'original');
                                        if ($url) {
                                            $color_image_urls[] = esc_url($url);
                                        }
                                    } elseif (is_string($image)) {
                                        $color_image_urls[] = esc_url($image);
                                    }
                                }
                            }

                            // Если для цвета нет изображений, используем дефолтные
                            if (empty($color_image_urls)) {
                                $color_image_urls = $default_images;
                            }

                            $images_by_color[$color_slug] = $color_image_urls;
                            $color_index++;
                        }
                    }
                }

                // Если нет цветов, создаем дефолтную группу
                if (empty($images_by_color)) {
                    $images_by_color['default'] = $default_images;
                    $default_color_slug = 'default';
                }

                if (isset($_GET['color_slug'])) {
                    $default_color_slug = $_GET['color_slug'];
                }

                // Устанавливаем main_thumb_url
                if (!empty($images_by_color) && isset($images_by_color[$default_color_slug]) && !empty($images_by_color[$default_color_slug])) {
                    $main_thumb_url = $images_by_color[$default_color_slug][0];
                } else {
                    $main_thumb_url = wc_placeholder_img_src('original'); // Заглушка WooCommerce
                }
                ?>
                <script>
                    window.prdImagesByColor = <?php echo json_encode($images_by_color); ?>;
                    window.defaultColorSlug = '<?php echo esc_js($default_color_slug); ?>';
                </script>

                <!-- Images -->
                <div class="shopProductDetailCardImages" id="shopProductDetailCardImages" v-cloak
                    data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                    data-add-to-cart-nonce="<?php echo esc_attr(wp_create_nonce('add_to_cart_nonce')); ?>"
                    data-nonce="<?php echo esc_attr(wp_create_nonce('toggle_favorite_nonce')); ?>"
                    data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" data-prd-group-id="main-prd">

                    <div class="shopProductDetailCardImagesWrapper" style="display: flex;gap: 57px;">
                        <div class="shopProductDetailCardImagesThumb">

                            <div class="cardImagesThumbSliderNavBlock">
                                <button class="cardImagesThumbSliderNavPrev cardImagesThumbSliderNavigation"
                                    @click="prevSlide">
                                    <svg width="12" height="8" viewBox="0 0 12 8" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M6.00143 3.02379L1.8766 7.14856L0.698096 5.97005L6.00143 0.666709L11.3047 5.97005L10.1262 7.14856L6.00143 3.02379Z"
                                            fill="#252525" />
                                    </svg>
                                </button>
                            </div>

                            <div class="cardImagesThumbSlider">
                                <div class="cardImagesThumbWrapper">
                                    <!-- Слайды миниатюр -->
                                    <div class="cardImagesThumbItem"
                                        v-for="(slideItem, index) in appShopDetailCardSlider.currentSlides"
                                        :key="slideItem"
                                        :class="{ '__Active': index === appShopDetailCardSlider.currentIndex }"
                                        @click="selectSlideByIndex(index)">
                                        <img :src="slideItem" alt="product thumb" />
                                    </div>
                                </div>
                            </div>

                            <div class="cardImagesThumbSliderNavBlock">
                                <button class="cardImagesThumbSliderNavNext cardImagesThumbSliderNavigation"
                                    @click="nextSlide">
                                    <svg width="12" height="8" viewBox="0 0 12 8" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M5.99857 4.97621L10.1234 0.851442L11.3019 2.02995L5.99857 7.33329L0.695312 2.02995L1.87382 0.851441L5.99857 4.97621Z"
                                            fill="#252525" />
                                    </svg>
                                </button>
                            </div>

                        </div>
                        <div class="shopProductDetailCardImagesMainMob">
                            <div class="detailCardImagesMainMobWrapper">
                                <div class="mainMobSlider swiper" id="productMobileSlider">
                                    <div class="mainMobSliderWrapper swiper-wrapper">
                                        <div class="mainMobSliderItemSlide swiper-slide"
                                            v-for="slideItem in appShopDetailCardSlider.currentSlides" :key="slideItem">
                                            <img :src="slideItem" alt="product gallery image" />
                                        </div>
                                    </div>
                                    <div class="bottomPaginate"></div>
                                </div>

                                <div class="mainMobTop"
                                    style="position: absolute; top: 20px; right: 10px; left: 10px; z-index: 10">
                                    <div class="mainMobTopWrapper"
                                        style="display: flex; align-items: center; justify-content: space-between;">
                                        <?php
                                        global $post;
                                        $terms = get_the_terms($post->ID, 'product_cat');
                                        if ($terms && !is_wp_error($terms)) {
                                            $term = reset($terms);
                                            $url_back = get_term_link($term);
                                        }
                                        ?>
                                        <div class="mainMobTopBack"
                                            onclick="try{window.location.href = '<?php echo $url_back ?>'}catch (e) {window.location.href = '/'}">
                                            <svg width="40" height="40" viewBox="0 0 40 40" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <rect width="40" height="40" rx="20" fill="white" />
                                                <path
                                                    d="M19.0266 19.9995L23.1514 15.8746L21.9729 14.6961L16.6695 19.9995L21.9729 25.3027L23.1514 24.1242L19.0266 19.9995Z"
                                                    fill="#252525" />
                                            </svg>
                                        </div>

                                        <div class="mainMobTopWhtAddWrp">
                                            <div class="mainMobTopWhtAdd">
                                                <a
                                                    @click="addToWhtListMob({imageUrl:'<?php echo $main_thumb_url ?>, productId: <?= esc_attr($product->get_id()) ?>'})">
                                                    <div class="mainMobTopWhtAddBtn"
                                                        v-if="appFavoriteBtn !== null && !appFavoriteBtn.status.active">
                                                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <rect width="40" height="40" rx="20" fill="white" />
                                                            <path
                                                                d="M19.9982 13.7738C21.9557 12.0167 24.9807 12.075 26.8662 13.9645C28.7518 15.8539 28.8166 18.8642 27.0629 20.8275L19.9973 27.9042L12.9319 20.8275C11.1783 18.8642 11.2438 15.8492 13.1285 13.9645C15.0154 12.0776 18.0351 12.0141 19.9982 13.7738ZM25.6866 15.1418C24.4373 13.89 22.4204 13.8392 21.1116 15.0141L19.999 16.0127L18.8858 15.0148C17.5732 13.8383 15.5599 13.8901 14.307 15.143C13.0656 16.3844 13.0033 18.3728 14.1473 19.686L19.9973 25.5453L25.8475 19.686C26.992 18.3723 26.9299 16.3877 25.6866 15.1418Z"
                                                                fill="#1F1F1F" />
                                                        </svg>
                                                    </div>
                                                    <div class="mainMobTopWhtAddBtn" v-else>
                                                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <rect width="40" height="40" rx="20" fill="white" />
                                                            <path
                                                                d="M19.9982 13.7738C21.9557 12.0167 24.9807 12.075 26.8662 13.9645C28.7518 15.8539 28.8166 18.8642 27.0629 20.8275L19.9973 27.9042L12.9319 20.8275C11.1783 18.8642 11.2438 15.8492 13.1285 13.9645C15.0154 12.0776 18.0351 12.0141 19.9982 13.7738ZM25.6866 15.1418C24.4373 13.89 22.4204 13.8392 21.1116 15.0141L19.999 16.0127L18.8858 15.0148C17.5732 13.8383 15.5599 13.8901 14.307 15.143C13.0656 16.3844 13.0033 18.3728 14.1473 19.686L19.9973 25.5453L25.8475 19.686C26.992 18.3723 26.9299 16.3877 25.6866 15.1418Z"
                                                                fill="#CE1B19" />
                                                            <path
                                                                d="M25.6866 15.1418C24.4373 13.89 22.4204 13.8392 21.1116 15.0141L19.999 16.0127L18.8858 15.0148C17.5732 13.8383 15.5599 13.8901 14.307 15.143C13.0656 16.3844 13.0033 18.3728 14.1473 19.686L19.9973 25.5453L25.8475 19.686C26.992 18.3723 26.9299 16.3877 25.6866 15.1418Z"
                                                                fill="#CE1B19" />
                                                        </svg>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="shopProductDetailCardImagesMain" style="position: relative">
                            <div class="cardImagesMainTop">
                                <div class="cardImagesMainHeading">
                                    <div class="cardImagesMainHeadingFav">
                                        <div class="previewSliderItemWhiteList"
                                            data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                                            data-add-to-cart-nonce="<?php echo esc_attr(wp_create_nonce('add_to_cart_nonce')); ?>"
                                            data-nonce="<?php echo esc_attr(wp_create_nonce('toggle_favorite_nonce')); ?>"
                                            data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>"
                                            data-default-color="" data-default-size="L"
                                            @click="addToWhtListMob({imageUrl:'<?php echo $main_thumb_url ?>', productId: <?= esc_attr($product->get_id()) ?>})">
                                            <div class="whiteListBtn" style="background: #FFFFFF;">
                                                <div class="whiteListBtnIcon"
                                                    v-if="appFavoriteBtn !== null && !appFavoriteBtn.status.active">
                                                    <svg width="30" height="30" viewBox="0 0 30 30" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <g clip-path="url(#clip0_1365_42)">
                                                            <mask id="mask0_1365_42" style="mask-type:luminance"
                                                                maskUnits="userSpaceOnUse" x="0" y="0" width="30"
                                                                height="30">
                                                                <path d="M30 0H0V30H30V0Z" fill="white" />
                                                            </mask>
                                                            <g mask="url(#mask0_1365_42)">
                                                                <path
                                                                    d="M30 15C30 6.71573 23.2843 0 15 0C6.71573 0 0 6.71573 0 15C0 23.2843 6.71573 30 15 30C23.2843 30 30 23.2843 30 15Z"
                                                                    fill="white" />
                                                                <path
                                                                    d="M14.9994 10.019C16.5654 8.61333 18.9854 8.66 20.4938 10.1716C22.0023 11.6831 22.0541 14.0913 20.6511 15.662L14.9986 21.3233L9.34629 15.662C7.9434 14.0913 7.99584 11.6793 9.50361 10.1716C11.0131 8.6621 13.4288 8.61125 14.9994 10.019ZM19.5501 11.1134C18.5507 10.112 16.9371 10.0713 15.89 11.0112L15 11.8102L14.1094 11.0119C13.0593 10.0706 11.4487 10.112 10.4464 11.1144C9.45325 12.1075 9.40339 13.6982 10.3186 14.7488L14.9986 19.4362L19.6788 14.7488C20.5944 13.6978 20.5447 12.1102 19.5501 11.1134Z"
                                                                    fill="#1F1F1F" />
                                                            </g>
                                                        </g>
                                                        <defs>
                                                            <clipPath id="clip0_1365_42">
                                                                <rect width="30" height="30" fill="white" />
                                                            </clipPath>
                                                        </defs>
                                                    </svg>
                                                </div>
                                                <div class="whiteListBtnIcon" v-else>
                                                    <svg width="30" height="30" viewBox="0 0 30 30" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <rect width="30" height="30" rx="15" fill="white" />
                                                        <path
                                                            d="M14.9994 10.019C16.5654 8.61333 18.9854 8.66 20.4938 10.1716C22.0022 11.6831 22.054 14.0913 20.6511 15.662L14.9986 21.3233L9.34628 15.662C7.9434 14.0913 7.99584 11.6793 9.5036 10.1716C11.0131 8.6621 13.4288 8.61125 14.9994 10.019Z"
                                                            fill="#CE1B19" />
                                                    </svg>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Основное изображение -->
                            <div class="cardImagesMainImg">
                                <img :src="appShopDetailCardSlider.currentImage"
                                    :key="appShopDetailCardSlider.currentImage" alt="product main image" />
                            </div>

                        </div>
                    </div>
                </div>
                <div class="shopProductDetailCardInfoWrap gridWrap">
                    <!-- Info -->
                    <div class="shopProductDetailCardInfo" style="flex: 1;">

                        <div class="detailCardInfoBread">
                            <?php // Кастомная функция для вывода хлебных крошек
                            function custom_breadcrumbs(): void
                            {
                                // Получаем текущий объект
                                global $post;

                                // Начинаем вывод хлебных крошек
                                echo '<nav class="woocommerce-breadcrumb"><ul class="breadcrumb-list">';

                                // Ссылка на главную страницу
                                echo '<li class="breadcrumb-item"><a href="' . home_url() . '">Главная</a></li>';

                                // Проверяем, находимся ли мы на странице категории
                                if (is_product_category()) {
                                    $category = get_queried_object();
                                    echo '<li class="breadcrumb-item">' . esc_html($category->name) . '</li>';
                                    echo '<li class="breadcrumb-separator"> > </li>';
                                }

                                // Проверяем, находимся ли мы на странице товара
                                if (is_product()) {
                                    $terms = get_the_terms($post->ID, 'product_cat');
                                    if ($terms && !is_wp_error($terms)) {
                                        $term = reset($terms); // Получаем первую категорию
                                        echo '<li class="breadcrumb-separator"> > </li>';
                                        echo '<li class="breadcrumb-item"><a href="' . get_term_link($term) . '">' . esc_html($term->name) . '</a></li>';
                                    }
                                    echo '<li class="breadcrumb-separator"> > </li>';
                                    echo '<li class="breadcrumb-item active">' . get_the_title() . '</li>';
                                }

                                echo '</ul></nav>';
                            }

                            custom_breadcrumbs() ?>
                        </div>

                        <div class="detailCardInfoHeading" id="singlePrdCard" v-cloak
                            data-product-id="<?php echo esc_attr($product_id); ?>"
                            data-add-to-cart-nonce="<?php echo esc_attr($add_to_cart_nonce); ?>"
                            data-nonce="<?php echo esc_attr($nonce); ?>"
                            data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>"
                            data-default-color="<?php echo esc_attr($default_color_slug); ?>" data-default-size="L"
                            data-prd-group-id="main-prd"
                            data-default-color-id="<?php echo esc_attr($default_color_id); ?>">
                            <div class="detailCardInfoHeadingWrapper">
                                <div class="detailCardInfoHeadingTitle">
                                    <?php echo esc_html($product->get_title()); ?>
                                </div>
                            </div>
                            <div class="cardInfoHeadingPrice" style="">
                                <div class="cardInfoHeadingPriceWrapper">
                                    <?php if ($product->is_in_stock()): ?>
                                        <div class="cardInfoHeadingPriceFull">
                                            <?php echo $product->get_price_html(); ?>
                                        </div>
                                        <?php if ($product->is_on_sale()): ?>
                                            <div class="cardInfoHeadingPriceSalePerc">
                                                <?php
                                                $regular_price = $product->get_regular_price();
                                                $sale_price = $product->get_sale_price();
                                                if ($regular_price && $sale_price) {
                                                    $discount = round((($regular_price - $sale_price) / $regular_price) * 100);
                                                    echo '-' . $discount . '%';
                                                }
                                                ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="cardInfoHeadingPriceFull">
                                            Товара нет в наличии
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php
                            $color_rows = get_field('product_colors');
                            ?>
                            <?php if (have_rows('product_colors') && !empty($color_rows[0]['color_rel'])): ?>
                                <div class="detailCardHeadingSeparate">
                                    <div class="detailCardInfoHeadingColor">


                                        <div class="infoHeadingColorHead">
                                            <div class="infoHeadingColorHeadTitle" style="margin-bottom: 16px">
                                                Цвет:
                                            </div>
                                        </div>

                                        <!-- В секции выбора цвета -->
                                        <div class="itemBlockHeadingSelColor">
                                            <?php while (have_rows('product_colors')):
                                                the_row();
                                                $color_rel = get_sub_field('color_rel');
                                                $color_post = is_array($color_rel) ? $color_rel[0] : null;

                                                if (!$color_post)
                                                    continue;

                                                $color_id = $color_post->ID;
                                                $color_name = get_the_title($color_id);
                                                $color_code = get_field('color_code', $color_id);
                                                $color_slug = get_field('color_slug', $color_id);
                                                $is_none_color = (strtolower($color_slug) === 'none' || strtolower($color_name) === 'none');
                                            ?>
                                                <div class="colorBox"
                                                    :class="{ '__Active': appShop.cart.select.color === '<?php echo esc_js($color_slug); ?>' }"
                                                    @click="selectCartColor('<?php echo esc_js($color_slug); ?>', '<?php echo esc_js($color_id); ?>')">
                                                    <?php if ($is_none_color): ?>
                                                        <!-- Иконка для цвета "none" -->
                                                        <div class="color-none-icon" title="<?php echo esc_attr($color_name); ?>">
                                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <circle cx="10" cy="10" r="8" stroke="#ccc" stroke-width="1.5"
                                                                    fill="white" />
                                                                <line x1="4" y1="4" x2="16" y2="16" stroke="#e74c3c"
                                                                    stroke-width="2" />
                                                            </svg>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="color-circle" title="<?php echo esc_attr($color_name); ?>"
                                                            data-color-id="<?php echo esc_attr($color_slug); ?>"
                                                            style="background-color:<?php echo esc_attr($color_code); ?>;"></div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endwhile; ?>
                                        </div>


                                    </div>
                                </div>
                            <? endif; ?>
                            <div class="detailCardInfoHeadingAtr" style="">
                                <div class="detailCardInfoHeadingSizes">
                                    <?php
                                    $sizes = wc_get_product_terms($product->get_id(), 'pa_sizes', array('fields' => 'names'));
                                    if (!empty($sizes)):
                                    ?>
                                        <div class="size-selector">
                                            <div class="size-selector__header">
                                                <span class="size-selector__title">Размер</span>
                                                <a href="#size-chart" class="size-selector__link">Размерная сетка</a>
                                            </div>
                                            <div class="size-selector__options">
                                                <?php foreach ($sizes as $size): ?>
                                                    <label class="size-selector__option"
                                                        @click="selectCartSize('<?php echo $size ?>')"
                                                        :class="{__Active: appShop.cart.select.size === '<?php echo $size; ?>'}">
                                                        <input type="radio" name="attribute_pa_size"
                                                            value="<?php echo esc_attr($size); ?>" required>
                                                        <span><?php echo esc_html($size); ?></span>
                                                    </label>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="detailCardInfoHeadingAddToWrapper">
                                <div class="detailCardInfoHeadingAddToCart">
                                    <div class="addToCardBtn <?= $product->is_in_stock() ?: 'disabled' ?>"
                                        :class="{ '__Disabled': isAddToCartDisabled }"
                                        @click="!isAddToCartDisabled && addToCartBtn($event, { imageUrl: '<?php echo $main_thumb_url ?>' })">
                                        <div class="addToCardBtnHeading">
                                            <div class="addToCardBtnHeadingTitle">
                                                <?php if ($product->is_in_stock()): ?>
                                                    <!-- Тоже через {{ … }} -->
                                                    {{ addToCartButtonText }}
                                                <?php else: ?>
                                                    Товара нет в наличии
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="detailCardHeadingSeparateBottom"></div>
                            </div>
                            <?php if (!empty($product->get_description())) { ?>
                                <div class="detailCardInfoHeadingDesc">
                                    <?php echo $product->get_description() ? wp_kses_post($product->get_description()) : ''; ?>
                                </div>
                                <div class="detailCardHeadingSeparateBottom"></div>
                            <?php } ?>
                            <div class="detailCardHeadingSeparateBottom">
                                <div class="footerMainMob__accordion">
                                    <div class="footerMainMob__section" style="border-bottom: none; margin-bottom: 0;">
                                        <button class="footerMainMob__toggle detailCardHeadingLink" data-index="0"
                                            style="padding: 0;">
                                            Состав и уход
                                            <span class="footer__icon">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M9.99948 10.9736L5.87464 6.84888L4.69614 8.02739L9.99948 13.3307L15.3027 8.02739L14.1242 6.84888L9.99948 10.9736Z"
                                                        fill="#000" />
                                                </svg>
                                            </span>
                                        </button>
                                        <div class="footerMainMob__content"
                                            style="max-height: 0px;margin-bottom: 20px;padding: 0;color: #444;">
                                            <?php $cmps_care = get_field('cmps_care', $product->get_id());
                                            echo wp_kses_post($cmps_care); ?>
                                        </div>
                                    </div>

                                    <div class="footerMainMob__section" style="border-bottom: none; margin-bottom: 0;">
                                        <button
                                            class="footerMainMob__toggle detailCardHeadingLink detailCardHeadingLinkPrd"
                                            data-index="0" style="padding: 0;">
                                            Доставка и оплата
                                            <span class="footer__icon">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M9.99948 10.9736L5.87464 6.84888L4.69614 8.02739L9.99948 13.3307L15.3027 8.02739L14.1242 6.84888L9.99948 10.9736Z"
                                                        fill="#000" />
                                                </svg>
                                            </span>
                                        </button>
                                        <div class="footerMainMob__content"
                                            style="max-height: 0px;margin-bottom: 20px;padding: 0;color: #444;">
                                            <?php $delivery_pay = get_field('delivery_pay', $product->get_id());
                                            echo wp_kses_post($delivery_pay); ?>
                                        </div>
                                    </div>
                                </div>

                            </div>



                        </div>
                        <div class="detailCardInfoBody">
                            <div class="detailCardInfoBodyWrapper">
                                <div class="detailCardInfoBodyAttr">
                                    <div class="detailCardInfoBodyAttrWrapper">
                                        <?php
                                        //                                        $attributes = $product->get_attributes();
                                        //                                        foreach ($attributes as $attribute) {
                                        //                                            if ($attribute->get_visible()) {
                                        //                                                $name = wc_attribute_label($attribute->get_name());
                                        //                                                $value = implode(', ', wc_get_product_terms($product->get_id(), $attribute->get_name(), ['fields' => 'names']));
                                        //                                                echo '<div class="detailCardInfoBodyAttrItem"><strong>' . esc_html($name) . ':</strong> ' . esc_html($value) . '</div>';
                                        //                                            }
                                        //                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="detailCardInfoFooter">
                            <div class="detailCardInfoFooterWrapper">
                                <?php woocommerce_template_single_add_to_cart(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_template_part('template-parts/product_card/prd-idea-modal-pc', null, null); ?>
<?php get_template_part(
    "template-parts/product_card/prdcardbar-mb",
    null,
    array(
        'nonce' => $nonce,
        'product_id' => $product_id,
        'product' => $product,
    )
); ?>
</div>

</div>