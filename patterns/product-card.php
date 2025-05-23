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
                $slides = [];
                $attachment_ids = $product->get_gallery_image_ids();

                // Если есть изображения в галерее
                if (!empty($attachment_ids)) {
                    foreach ($attachment_ids as $attachment_id) {
                        $thumb_url = wp_get_attachment_image_url($attachment_id, 'original');
                        $slides[] = esc_url($thumb_url);
                    }
                } else {
                    // Если галерея пуста — выводим только основное изображение
                    $main_thumb_id = $product->get_image_id();
                    if ($main_thumb_id) {
                        $main_thumb_url = wp_get_attachment_image_url($main_thumb_id, 'original');
                        $slides[] = esc_url($main_thumb_url);
                    }
                }
                ?>
                <script>
                    window.prdImagesSlides = <?php echo json_encode($slides) ?>;
                </script>
                <!-- Images -->
                <div class="shopProductDetailCardImages" id="shopProductDetailCardImages" v-cloak
                    data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                    data-add-to-cart-nonce="<?php echo esc_attr(wp_create_nonce('add_to_cart_nonce')); ?>"
                    data-nonce="<?php echo esc_attr(wp_create_nonce('toggle_favorite_nonce')); ?>"
                    data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
                    <div class="shopProductDetailCardImagesWrapper" style="display: flex;gap: 57px;">
                        <div class="shopProductDetailCardImagesThumb">
                            <div class="cardImagesThumbWrapper" style="">
                                <div class="cardImagesThumbItem" v-for="slideItem in appShopDetailCardSlider.slides"
                                    :class="{__Active: slideItem === appShopDetailCardSlider.select.slide.src}"
                                    @click="selectSlideImg(slideItem)">
                                    <img :src="slideItem" alt="product thumb" />
                                </div>
                            </div>
                        </div>
                        <div class="shopProductDetailCardImagesMainMob">
                            <div class="detailCardImagesMainMobWrapper">
                                <div class="mainMobSlider swiper">
                                    <div class="mainMobSliderWrapper swiper-wrapper">
                                        <?php
                                        $attachment_ids = $product->get_gallery_image_ids();

                                        // Если есть изображения в галерее
                                        if (!empty($attachment_ids)) {
                                            foreach ($attachment_ids as $attachment_id) {
                                                $thumb_url = wp_get_attachment_image_url($attachment_id, 'original');
                                        ?>
                                                <div class="mainMobSliderItemSlide swiper-slide">
                                                    <img src="<?php echo esc_url($thumb_url); ?>"
                                                        alt="product gallery image" />
                                                </div>
                                            <?php
                                            }
                                        } else {
                                            // Если галерея пуста — выводим только основное изображение
                                            $main_thumb_id = $product->get_image_id();
                                            if ($main_thumb_id) {
                                                $main_thumb_url = wp_get_attachment_image_url($main_thumb_id, 'original');
                                            ?>
                                                <div class="mainMobSliderItemSlide swiper-slide">
                                                    <img src="<?php echo esc_url($main_thumb_url); ?>"
                                                        alt="main product image" />
                                                </div>
                                        <?php
                                            }
                                        }
                                        ?>
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
                                            onclick="try{window.navigation.navigate('<?php echo $url_back ?>')}catch (e) {window.navigation.navigate('/')}">
                                            <svg width="40" height="40" viewBox="0 0 40 40" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <rect width="40" height="40" rx="20" fill="white" />
                                                <path
                                                    d="M19.0266 19.9995L23.1514 15.8746L21.9729 14.6961L16.6695 19.9995L21.9729 25.3027L23.1514 24.1242L19.0266 19.9995Z"
                                                    fill="#252525" />
                                            </svg>
                                        </div>
                                        <!--                                        -->
                                        <div class="mainMobTopWhtAddWrp">
                                            <?php
                                            $main_thumb_url = "";
                                            $main_thumb_id = $product->get_image_id();
                                            if ($main_thumb_id) {
                                                $main_thumb_url = wp_get_attachment_image_url($main_thumb_id, 'original');
                                            }
                                            ?>
                                            <div class="mainMobTopWhtAdd">
                                                <a @click="addToWhtListMob({imageUrl:'<?php echo $main_thumb_url ?>'})">
                                                    <div class="mainMobTopWhtAddBtn"
                                                        v-if="appFavoriteBtn !== null && appFavoriteBtn.status.active">
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
                                                            <path d="M19.9982 13.7738C21.9557 12.0167 24.9807 12.075 26.8662 13.9645C28.7518 15.8539 28.8166 18.8642 27.0629 20.8275L19.9973 27.9042L12.9319 20.8275C11.1783 18.8642 11.2438 15.8492 13.1285 13.9645C15.0154 12.0776 18.0351 12.0141 19.9982 13.7738ZM25.6866 15.1418C24.4373 13.89 22.4204 13.8392 21.1116 15.0141L19.999 16.0127L18.8858 15.0148C17.5732 13.8383 15.5599 13.8901 14.307 15.143C13.0656 16.3844 13.0033 18.3728 14.1473 19.686L19.9973 25.5453L25.8475 19.686C26.992 18.3723 26.9299 16.3877 25.6866 15.1418Z"
                                                                fill="#CE1B19" />
                                                            <path d="M25.6866 15.1418C24.4373 13.89 22.4204 13.8392 21.1116 15.0141L19.999 16.0127L18.8858 15.0148C17.5732 13.8383 15.5599 13.8901 14.307 15.143C13.0656 16.3844 13.0033 18.3728 14.1473 19.686L19.9973 25.5453L25.8475 19.686C26.992 18.3723 26.9299 16.3877 25.6866 15.1418Z"
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
                                        <?php
                                        $main_thumb_url = "";
                                        $main_thumb_id = $product->get_image_id();
                                        if ($main_thumb_id) {
                                            $main_thumb_url = wp_get_attachment_image_url($main_thumb_id, 'original');
                                        }
                                        ?>
                                        <div class="previewSliderItemWhiteList" id="favBtnPrd"
                                            data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                                            data-add-to-cart-nonce="<?php echo esc_attr(wp_create_nonce('add_to_cart_nonce')); ?>"
                                            data-nonce="<?php echo esc_attr(wp_create_nonce('toggle_favorite_nonce')); ?>"
                                            data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>"
                                            data-default-color=""
                                            data-default-size="L"
                                            @click="addToWhtListMob({imageUrl:'<?php echo $main_thumb_url ?>'})">


                                            <div class="whiteListBtn" style="background: #FFFFFF;">
                                                <div class="whiteListBtnIcon"
                                                    v-if="getSelectedWhtLst(<?= esc_attr($product->get_id()); ?>)">
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
                            </div>

                            <div class="cardImagesMainImg">
                                <transition name="fade">
                                    <img :src="appShopDetailCardSlider.select.slide.src"
                                        v-show="appShopDetailCardSlider.select.slide.src"
                                        :key="appShopDetailCardSlider.select.slide.src" />
                                </transition>
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
                        <?php
                        // Берём всё поле repeater как массив
                        $colors = get_field('product_colors');

                        if ($colors && is_array($colors)):
                            // Извлекаем первый элемент
                            $first_color = $colors[0];
                            // Вытащим из него нужные параметры
                            $default_сolor_slug = $first_color['color_slug'];
                            $default_сolor_code = $first_color['color_code'];
                            $default_сolor_name = $first_color['color_name'];
                        else:
                            $default_сolor_slug = "";
                        endif;

                        $default_size_slug = "size_l";
                        //
                        ?>
                        <div class="detailCardInfoHeading" id="singlePrdCard" v-cloak
                            data-product-id="<?php echo esc_attr($product_id); ?>"
                            data-add-to-cart-nonce="<?php echo esc_attr($add_to_cart_nonce); ?>"
                            data-nonce="<?php echo esc_attr($nonce); ?>"
                            data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>"
                            data-default-color="<?php echo esc_attr($default_сolor_slug); ?>"
                            data-default-size="<?php echo esc_attr($default_size_slug); ?>">
                            <div class="detailCardInfoHeadingWrapper">
                                <div class="detailCardInfoHeadingTitle">
                                    <?php echo esc_html($product->get_title()); ?>
                                </div>
                            </div>
                            <div class="cardInfoHeadingPrice" style="">
                                <div class="cardInfoHeadingPriceWrapper">
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
                                </div>
                            </div>
                            <div class="detailCardHeadingSeparate">
                                <div class="detailCardInfoHeadingColor">
                                    <?php if (have_rows('product_colors')): ?>

                                        <div class="infoHeadingColorHead">
                                            <div class="infoHeadingColorHeadTitle" style="margin-bottom: 16px">
                                                Цвет:
                                            </div>
                                        </div>

                                        <div class="itemBlockHeadingSelColor" style="display: flex;gap: 10px;">
                                            <?php while (have_rows('product_colors')):
                                                the_row();
                                                $color_name = get_sub_field('color_name');
                                                $color_code = get_sub_field('color_code');
                                                $color_slug = get_sub_field('color_slug');
                                            ?>
                                                <div class="colorBox"
                                                    :class="{ __Active: appShop.cart.select.color === '<?php echo esc_js($color_slug); ?>' }"
                                                    @click="selectCartColor('<?php echo $color_slug ?>')">
                                                    <div class="color-circle"
                                                        title="<?php echo esc_attr($color_name); ?>"
                                                        data-color-id="<?php echo esc_attr($color_slug); ?>"
                                                        style="background-color:<?php echo esc_attr($color_code); ?>;"></div>
                                                </div>
                                            <?php endwhile; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
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
                                    <div class="addToCardBtn" href="#"
                                        @click="addToCartBtn($event, {imageUrl:'<?php echo $main_thumb_url ?>'})">
                                        <div class="addToCardBtnHeading">
                                            <div class="addToCardBtnHeadingTitle">
                                                Добавить в корзину
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
                                        <div class="footerMainMob__content">
                                            <ul>
                                                <li><a href="#">....</a></li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="footerMainMob__section" style="border-bottom: none; margin-bottom: 0;">
                                        <button class="footerMainMob__toggle detailCardHeadingLink detailCardHeadingLinkPrd"
                                            data-index="0"
                                            style="padding: 0;">
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
                                        <div class="footerMainMob__content">
                                            <ul>
                                                <li><a href="#">...</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- <div class="detailCardHeadingLink">
                                    <a href="#detail_info"></a>
                                </div>
                                <div class="detailCardHeadingLink">
                                    <a href="#delivery_payment"></a>
                                </div> -->
                            </div>

                            <?php get_template_part('template-parts/product_card/prd-idea-modal-pc', null, null); ?>
                            <?php get_template_part('template-parts/product_card/prd-idea-modal-mb', null, null); ?>

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