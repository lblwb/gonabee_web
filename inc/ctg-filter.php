<?php

function woocommerce_filter_shortcode()
{
    $parent_id = 0;
    if (is_product_category()) {
        $current_category = get_queried_object();
        $parent_id = $current_category->term_id;
    }

    $subcategories = get_terms([
        'taxonomy' => 'product_cat',
        'parent' => $parent_id,
        'hide_empty' => true,
        'orderby' => 'name',
    ]);

//    $subcategories = [];
//
//    foreach ($subcategories_terms as $subcategory) {
//        $subcategories[] = $subcategory;
//    }

//    var_dump($subcategories);

    // Лучше кэшировать!
    $colors = get_transient('unique_product_colors');
    if (!$colors) {
        $colors = [];
        $product_ids = get_posts([
            'post_type' => 'product',
            'posts_per_page' => -1,
            'fields' => 'ids',
        ]);
        foreach ($product_ids as $product_id) {
            $product_colors = get_field('product_colors', $product_id);
            if ($product_colors) {
                foreach ((array)$product_colors as $color) {
                    if (is_array($color) && isset($color['color_name'])) {
                        $colors[$color['color_name']] = $color['color_code'] ?? '';
                    } else {
                        $colors[$color] = '';
                    }
                }
            }
        }
        $colors = array_map(fn($name, $hex) => ['name' => $name, 'hex' => $hex ?: '#000000'], array_keys($colors), array_values($colors));
        set_transient('unique_product_colors', $colors, HOUR_IN_SECONDS);
    }

    $collections = get_terms([
        'taxonomy' => 'prd_collection',
        'hide_empty' => true,
        'orderby' => 'name',
    ]);

    $occupations = ['Для йоги', 'Для активного спорта', 'Для бега', 'Для похода в зал'];

    global $wpdb;
    $min_price = (float)$wpdb->get_var("SELECT MIN(CAST(meta_value AS UNSIGNED)) FROM {$wpdb->postmeta} WHERE meta_key='_price' AND meta_value != ''");
    $max_price = (float)$wpdb->get_var("SELECT MAX(CAST(meta_value AS UNSIGNED)) FROM {$wpdb->postmeta} WHERE meta_key='_price' AND meta_value != ''");

    ob_start();
    locate_template("template-parts/catalog/filter/ctg-filter-pc.php", true, null, array(
        'subcategories' => $subcategories,
        'colors' => $colors,
        'collections' => $collections,
        'occupations' => $occupations,
        'min_price' => $min_price,
        'max_price' => $max_price,
    ));
    return ob_get_clean();
}


add_shortcode('woocommerce_filter', 'woocommerce_filter_shortcode');

add_action('pre_get_posts', 'custom_woocommerce_filter_query');
function custom_woocommerce_filter_query($query)
{
    if (!is_admin() && $query->is_main_query() && (is_shop() || is_product_category())) {
        $meta_query = $query->get('meta_query') ?: [];
        $tax_query = $query->get('tax_query') ?: [];

        if (isset($_GET['subcategory'])) {
            $tax_query[] = [
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => explode(',', sanitize_text_field($_GET['subcategory']))
            ];
        }

        if (isset($_GET['promotion']) && $_GET['promotion'] == '1') {
            // Add ACF or WooCommerce promotion logic
            $meta_query[] = ['key' => 'promotion_field', 'value' => '1', 'compare' => '='];
        }

        if (isset($_GET['min_price'])) {
            $meta_query[] = ['key' => '_price', 'value' => (float)$_GET['min_price'], 'compare' => '>=', 'type' => 'NUMERIC'];
        }

        if (isset($_GET['max_price'])) {
            $meta_query[] = ['key' => '_price', 'value' => (float)$_GET['max_price'], 'compare' => '<=', 'type' => 'NUMERIC'];
        }

        if (isset($_GET['size'])) {
            $meta_query[] = ['key' => 'size', 'value' => sanitize_text_field($_GET['size']), 'compare' => '='];
        }

        if (isset($_GET['color'])) {
            $meta_query[] = ['key' => 'colors', 'value' => explode(',', sanitize_text_field($_GET['color'])), 'compare' => 'IN'];
        }

        if (isset($_GET['collection'])) {
            $tax_query[] = [
                'taxonomy' => 'collection',
                'field' => 'slug',
                'terms' => explode(',', sanitize_text_field($_GET['collection']))
            ];
        }

        if (isset($_GET['occupation'])) {
            $meta_query[] = ['key' => 'occupation', 'value' => explode(',', sanitize_text_field($_GET['occupation'])), 'compare' => 'IN'];
        }

        if (isset($_GET['on_sale']) && $_GET['on_sale'] == '1') {
            $query->set('post__in', wc_get_product_ids_on_sale());
        }

        if (isset($_GET['new']) && $_GET['new'] == '1') {
            $meta_query[] = ['key' => 'is_new', 'value' => '1', 'compare' => '='];
        }

        if (isset($_GET['trending']) && $_GET['trending'] == '1') {
            $meta_query[] = ['key' => 'is_trending', 'value' => '1', 'compare' => '='];
        }

        $query->set('meta_query', $meta_query);
        $query->set('tax_query', $tax_query);
    }
}