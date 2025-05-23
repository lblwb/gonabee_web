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
?>


<div class="shopProductDetailWrapper">
    <div class="shopProductDetailBody">
        <div class="shopProductDetailCard">
            <div class="shopProductDetailCardWrapper gridWrap __mobGridUnset">
                <!-- Images -->
                <div class="shopProductDetailCardImages">
                    <div class="shopProductDetailCardImagesWrapper" style="display: flex;gap: 57px;">
                        <div class="shopProductDetailCardImagesThumb">
                            <div class="cardImagesThumbWrapper" style="">
                                <?php
                                $attachment_ids = $product->get_gallery_image_ids();
                                foreach ($attachment_ids as $attachment_id) {
                                    $thumb_url = wp_get_attachment_image_url($attachment_id, 'thumbnail');
                                    ?>
                                    <div class="cardImagesThumbItem">
                                        <img src="<?php echo esc_url($thumb_url); ?>" alt="product thumb"/>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="shopProductDetailCardImagesMainMob">
                            <div class="detailCardImagesMainMobWrapper">
                                <div class="mainMobSlider swiper">
                                    <div class="mainMobSliderWrapper swiper-wrapper">
                                        <?php
                                        $attachment_ids = $product->get_gallery_image_ids();
                                        foreach ($attachment_ids as $attachment_id) {
                                            $thumb_url = wp_get_attachment_image_url($attachment_id, 'original');
                                            ?>
                                            <div class="mainMobSliderItemSlide swiper-slide">
                                                <img src="<?php echo esc_url($thumb_url); ?>" alt="product thumb"/>
                                            </div>
                                        <?php } ?>

                                    </div>
                                </div>

                                <div class="mainMobTop"
                                     style="position: absolute; top: 20px; right: 10px; left: 10px; z-index: 10">
                                    <div class="mainMobTopWrapper"
                                         style="display: flex; align-items: center; justify-content: space-between;">
                                        <div class="mainMobTopBack" onclick="window.navigation.back()">
                                            <svg width="40" height="40" viewBox="0 0 40 40" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <rect width="40" height="40" rx="20" fill="white"/>
                                                <path d="M19.0266 19.9995L23.1514 15.8746L21.9729 14.6961L16.6695 19.9995L21.9729 25.3027L23.1514 24.1242L19.0266 19.9995Z"
                                                      fill="#252525"/>
                                            </svg>
                                        </div>
                                        <div class="mainMobTopWhtAdd">
                                            <a href="#">
                                                <div class="mainMobTopWhtAddBtn">
                                                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none"
                                                         xmlns="http://www.w3.org/2000/svg">
                                                        <rect width="40" height="40" rx="20" fill="white"/>
                                                        <path d="M19.9982 13.7738C21.9557 12.0167 24.9807 12.075 26.8662 13.9645C28.7518 15.8539 28.8166 18.8642 27.0629 20.8275L19.9973 27.9042L12.9319 20.8275C11.1783 18.8642 11.2438 15.8492 13.1285 13.9645C15.0154 12.0776 18.0351 12.0141 19.9982 13.7738ZM25.6866 15.1418C24.4373 13.89 22.4204 13.8392 21.1116 15.0141L19.999 16.0127L18.8858 15.0148C17.5732 13.8383 15.5599 13.8901 14.307 15.143C13.0656 16.3844 13.0033 18.3728 14.1473 19.686L19.9973 25.5453L25.8475 19.686C26.992 18.3723 26.9299 16.3877 25.6866 15.1418Z"
                                                              fill="#1F1F1F"/>
                                                    </svg>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="shopProductDetailCardImagesMain">
                            <?php
                            $main_image_id = $product->get_image_id();
                            $main_image_url = wp_get_attachment_image_url($main_image_id, 'large');
                            ?>
                            <img src="<?php echo esc_url($main_image_url); ?>" alt="
                    <?php echo esc_attr($product->get_name()); ?>"/>
                        </div>
                    </div>
                </div>


                <div class="shopProductDetailCardInfoWrap gridWrap">
                    <!-- Info -->
                    <div class="shopProductDetailCardInfo" style="flex: 1;">

                        <div class="detailCardInfoBread">
                            <?php // Кастомная функция для вывода хлебных крошек
                            function custom_breadcrumbs()
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
                        <div class="detailCardInfoHeading">
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
                                    <?php if ($product->is_on_sale()) : ?>
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

                                        <div class="itemBlockHeadingSelColor">
                                            <?php while (have_rows('product_colors')): the_row();
                                                $color_name = get_sub_field('color_name');
                                                $color_code = get_sub_field('color_code');
                                                $color_slug = get_sub_field('color_slug');
                                                ?>
                                                <div class="colorBox">
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
                            <div class="detailCardInfoHeadingAtr" style="margin-bottom: 20px">
                                <div class="detailCardInfoHeadingSizes">
                                    <?php
                                    //                                    global $product;

                                    $sizes = wc_get_product_terms($product->get_id(), 'pa_sizes', array('fields' => 'names'));

                                    if (!empty($sizes)) :
                                        ?>
                                        <div class="size-selector">
                                            <div class="size-selector__header">
                                                <span class="size-selector__title">Размер</span>
                                                <a href="#size-chart" class="size-selector__link">Размерная сетка</a>
                                            </div>
                                            <div class="size-selector__options">
                                                <?php foreach ($sizes as $size) : ?>
                                                    <label class="size-selector__option">
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
                            <div class="detailCardInfoHeadingAddToCart">
                                <a class="addToCardBtn" @click="addToCartMob(<?= $product->get_id(); ?>)">
                                    <div class="addToCardBtnHeading">
                                        <div class="addToCardBtnHeadingTitle" v-if="appShop.cart.btnAddActive">
                                            Добавить в корзину
                                        </div>
                                        <div class="addToCardBtnHeadingTitle" v-else>
                                            Перейти в корзину
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="detailCardInfoHeadingDesc">
                                <?php echo $product->get_description() ? wp_kses_post($product->get_description()) : '——'; ?>
                            </div>
                            <div class="detailCardHeadingSeparate">
                                <div class="detailCardHeadingLink">
                                    <a href="#detail_info">Состав и уход</a>
                                </div>
                                <div class="detailCardHeadingLink">
                                    <a href="#delivery_payment">Доставка и оплата</a>
                                </div>
                            </div>
                        </div>
                        <div class="detailCardInfoBody">
                            <div class="detailCardInfoBodyWrapper">
                                <div class="detailCardInfoBodyAttr">
                                    <div class="detailCardInfoBodyAttrWrapper">
                                        <?php
                                        $attributes = $product->get_attributes();
                                        foreach ($attributes as $attribute) {
                                            if ($attribute->get_visible()) {
                                                $name = wc_attribute_label($attribute->get_name());
                                                $value = implode(', ', wc_get_product_terms($product->get_id(), $attribute->get_name(), ['fields' => 'names']));
                                                echo '<div class="detailCardInfoBodyAttrItem"><strong>' . esc_html($name) . ':</strong> ' . esc_html($value) . '</div>';
                                            }
                                        }
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
    <!--    -->
    <?php get_template_part("patterns", "prd-card-mb-bar", array("product" => $product)); ?>
</div>

</div>