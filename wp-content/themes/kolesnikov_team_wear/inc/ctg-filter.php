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

//    delete_transient('unique_product_colors');

    // Лучше кэшировать!
    $colors = get_transient('unique_product_colors');
    if (!$colors) {
        $colors = [];

        $product_ids = get_posts([
            'post_type' => 'product',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'post_status' => 'publish',
        ]);

        foreach ($product_ids as $product_id) {
            $product_colors = get_field('product_colors', $product_id);

            if ($product_colors && is_array($product_colors)) {
                foreach ($product_colors as $color) {
                    // Убедимся, что color_slug установлен
                    if (
                        is_array($color) &&
                        isset($color['color_slug']) &&
                        !empty($color['color_slug'])
                    ) {
                        $slug = sanitize_title($color['color_slug']);

                        // Если такой slug ещё не добавлен
                        if (!isset($colors[$slug])) {
                            $colors[$slug] = [
                                'slug' => $slug,
                                'name' => $color['color_name'] ?? ucfirst($slug),
                                'hex' => $color['color_code'] ?? '#000000',
                            ];
                        }
                    }
                }
            }
        }

        // Преобразуем ассоциативный массив в обычный
        $colors = array_values($colors);

        // Кэшируем на 1 час
        set_transient('unique_product_colors', $colors, HOUR_IN_SECONDS);
    }

//    $collections = get_terms([
//        'taxonomy' => 'prd_collection',
//        'hide_empty' => true,
//        'orderby' => 'name',
//    ]);

    $collections = get_terms([
        'taxonomy' => 'prd_collection',
        'hide_empty' => true,
        'orderby' => 'name',
    ]);

    //Размеры
    $sizes = get_terms([
        'taxonomy' => 'pa_sizes',
        'hide_empty' => false, // true — только те, что есть у продуктов
        'orderby' => 'name',
        'order' => 'ASC',
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
        'sizes' => $sizes,
    ));

//    var_dump($sizes);

    return ob_get_clean();
}


add_shortcode('woocommerce_filter', 'woocommerce_filter_shortcode');

// Функция для добавления фильтра в WHERE-условие
function add_color_filter_to_where($where)
{
    global $wpdb, $colors_for_filter;
    if (!empty($colors_for_filter)) {
        // Экранируем значения цветов и преобразуем в строку для SQL
        $color_list = implode("','", array_map('esc_sql', $colors_for_filter));
        // Добавляем EXISTS для проверки существования подходящей мета-записи
        $where .= " AND EXISTS (
            SELECT 1 FROM {$wpdb->postmeta}
            WHERE {$wpdb->postmeta}.post_id = {$wpdb->posts}.ID
            AND {$wpdb->postmeta}.meta_key LIKE 'product_colors_%_color_slug'
            AND {$wpdb->postmeta}.meta_value IN ('$color_list')
        )";
    }
    return $where;
}

add_action('pre_get_posts', 'custom_woocommerce_filter_query');
function custom_woocommerce_filter_query($query)
{
    if (!is_admin() && $query->is_main_query() && (is_shop() || is_product_category())) {
        $meta_query = $query->get('meta_query') ?: [];
        $tax_query = $query->get('tax_query') ?: [];

        // === TAX QUERIES ===

        // Фильтр по подкатегориям
        if (!empty($_GET['subcategory'])) {
            $raw_subcats = explode(',', $_GET['subcategory']);
            $subcats = array_map('sanitize_text_field', $raw_subcats);

            if (!empty($subcats)) {
                $tax_query[] = [
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => $subcats,
                    'operator' => 'AND' // или 'IN', в зависимости от логики фильтра
                ];
            }
        }

        // Фильтр по коллекции
        if (isset($_GET['collection'])) {
            $tax_query[] = [
                'taxonomy' => 'prd_collection',
                'field' => 'slug',
                'terms' => explode(',', sanitize_text_field($_GET['collection'])),
                'operator' => 'AND'
            ];
        }

        // === META QUERIES ===

        // Цена
        if (isset($_GET['min_price'])) {
            $meta_query[] = [
                'key' => '_price',
                'value' => (float)$_GET['min_price'],
                'compare' => '>=',
                'type' => 'NUMERIC'
            ];
        }

        if (isset($_GET['max_price'])) {
            $meta_query[] = [
                'key' => '_price',
                'value' => (float)$_GET['max_price'],
                'compare' => '<=',
                'type' => 'NUMERIC'
            ];
        }

        // Строгая проверка размера (одиночное значение — строгое совпадение)
        if (isset($_GET['size'])) {
            $raw_sizes = explode(',', sanitize_text_field($_GET['size']));
            $sizes = [];

            foreach ($raw_sizes as $size) {
                $size = trim($size);
                if (!empty($size)) {
                    $sizes[] = sanitize_title($size); // добавляем префикс + нормализуем slug
                }
            }

            if (!empty($sizes)) {
                $tax_query[] = [
                    'taxonomy' => 'pa_sizes', // это атрибут, важен префикс pa_
                    'field' => 'slug',
                    'terms' => $sizes,
                    'operator' => 'AND', // означает: товар должен иметь ВСЕ указанные размеры
                ];

//                var_dump($tax_query);
            }

//            var_dump($sizes);
        }

        // Фильтр по цвету
        if (isset($_GET['color']) && !empty($_GET['color'])) {
            $colors = explode(',', sanitize_text_field($_GET['color']));
            $color_query = [
                'relation' => 'OR', // Any of the specified colors
            ];

            foreach ($colors as $color) {
                $color = trim(sanitize_title($color)); // Sanitize and normalize color slug
                if (!empty($color)) {
                    $color_query[] = [
                        'key' => 'product_colors_%_color_slug',
                        'value' => $color, // Use the actual color from the loop
                        'compare' => '=',
                    ];
                }
            }

            if (!empty($color_query['relation'])) {
                $meta_query[] = $color_query; // Append color query to meta_query
            }
        }


        print_r($query->get('post__in'), true);

        // Строгая фильтрация по occupation
        if (isset($_GET['occupation'])) {
            $occupations = explode(',', sanitize_text_field($_GET['occupation']));
            foreach ($occupations as $occupation) {
                $meta_query[] = [
                    'key' => 'occupation',
                    'value' => '"' . $occupation . '"',
                    'compare' => 'LIKE'
                ];
            }
        }

        // Если на скидке — используем post__in
        if (isset($_GET['on_sale']) && $_GET['on_sale'] === '1') {
            $query->set('post__in', wc_get_product_ids_on_sale());
        }

        // === Строгая фильтрация по бейджам ===
        $acf_badges = [
            'is_trending',
            'is_new',
            'has_discount',
            'is_limited',
            'is_exclusive',
            'fast_shipping',
            'his_gift'
        ];

        foreach ($acf_badges as $badge_key) {
            if (isset($_GET[$badge_key]) && $_GET[$badge_key] === '1') {
                $meta_query[] = [
                    'key' => $badge_key,
                    'value' => '1',
                    'compare' => '='
                ];
            }
        }

//        var_dump($meta_query);

        // Обновляем параметры
        if ($tax_query) {
            $query->set('tax_query', $tax_query);
        }

        if ($meta_query) {
            $query->set('meta_query', $meta_query);
        }
    }
}

//add_action('posts_request', function($sql) {
//    print_r('SQL запроса: ' . $sql);
//    return $sql;
//});