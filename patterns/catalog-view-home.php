<?php

/**
 * Title: Catalog Gender Home
 * Slug: ktsportwear/catalog-gen-home
 * Categories: banner
 * @package WordPress
 */

// Получаем объекты категорий по имени
$cat_women = get_term_by('name', 'Женская одежда', 'product_cat');
$cat_men = get_term_by('name', 'Мужская одежда', 'product_cat');

// Получаем ссылки на архивы категорий
$link_women = $cat_women ? get_term_link($cat_women) : '#';
$link_men = $cat_men ? get_term_link($cat_men) : '#';

?>

<div class="catalogGenBanner">
    <div class="catalogGenBannerWrapper">
        <a href="<?php echo esc_url($link_women); ?>" class="catalogGenBannerBlock">
            <img src="<?php echo get_stylesheet_directory_uri() . '/assets/images/catalog/banner/catalogBtnGrl.png'; ?>" alt="Для нее" class="catalogGenBannerImg" />
            <div class="catalogGenBannerBottom">
                <div class="bannerBottomBtn">
                    <div class="bannerBottomBtnTitle">
                        Для нее
                    </div>
                </div>
            </div>
        </a>
        <a href="<?php echo esc_url($link_men); ?>" class="catalogGenBannerBlock">
            <img src="<?php echo get_stylesheet_directory_uri() . '/assets/images/catalog/banner/catalogBtnMan.png'; ?>" alt="Для него" class="catalogGenBannerImg" />
            <div class="catalogGenBannerBottom">
                <div class="bannerBottomBtn">
                    <div class="bannerBottomBtnTitle">
                        Для него
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>