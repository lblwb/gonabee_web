<?php

function woocommerce_filter_shortcode()
{
    $parent_id = 0;
    if (is_product_category()) {
        $current_category = get_queried_object();
        $parent_id = $current_category->term_id;
    }


    $excluded_names = ['Misc', 'All'];
    $subcategories = get_subcategories_tree($parent_id, $excluded_names);

    //    $subcategories = get_terms([
//        'taxonomy'   => 'product_cat',
//        'parent'     => $parent_id,
//        'hide_empty' => true,
//        'orderby'    => 'name',
//    ]);
//    $excluded_names = ['Misc', 'All'];

    // Исключаем по имени
//    $subcategories = array_filter($subcategories, function ($term) use ($excluded_names) {
//        return !in_array($term->name, $excluded_names, true);
//    });

    $subcategory_ids = wp_list_pluck($subcategories, 'term_id');

    // Если нет подкатегорий, добавляем текущую категорию
    if (empty($subcategory_ids) && $parent_id) {
        $subcategory_ids[] = $parent_id;
    }

    delete_transient('unique_product_colors_cat_' . md5(implode(',', $subcategory_ids)));

    // Генерируем уникальный ключ для кэша, зависящий от категории
    $cache_key = 'unique_product_colors_cat_' . md5(implode(',', $subcategory_ids));
    $colors = get_transient($cache_key);

    if ($colors === false) {
        $colors = [];

        // Запрос только нужных товаров
        $query_args = [
            'post_type' => 'product',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => 12,
            'no_found_rows' => true,
            'tax_query' => [
                [
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => $subcategory_ids,
                    'operator' => 'IN',
                ],
            ],
        ];

        $query = new WP_Query($query_args);

        if ($query->have_posts()) {
            foreach ($query->posts as $product_id) {
                $product_colors = get_field('product_colors', $product_id);

                if (empty($product_colors) || !is_array($product_colors)) {
                    continue;
                }

                foreach ($product_colors as $color_item) {
                    if (!isset($color_item['color_rel'][0]) || !is_object($color_item['color_rel'][0])) {
                        continue;
                    }

                    $color_post = $color_item['color_rel'][0];
                    $color_id = $color_post->ID;

                    // Ключ по slug
                    $color_slug = get_field('color_slug', $color_id);
                    if (empty($color_slug)) {
                        continue;
                    }

                    $slug = sanitize_title($color_slug);

                    // Не добавляем дубликаты
                    if (isset($colors[$slug])) {
                        continue;
                    }

                    $colors[$slug] = [
                        'slug' => $slug,
                        'name' => get_the_title($color_id) ?: ucfirst($slug),
                        'hex' => get_field('color_code', $color_id) ?: '#fff',
                    ];
                }
            }
        }

        // Упорядочиваем массив и кэшируем
        $colors = array_values($colors);
        set_transient($cache_key, $colors, HOUR_IN_SECONDS);
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

    // Фильтруем только буквы, убираем цифровые размеры
    // Фильтруем только буквенные обозначения
    $letter_sizes = array_filter($sizes, function ($term) {
        // Удалим пробелы и приведем к верхнему регистру (опционально)
        $name = trim($term->name);

        // Регулярное выражение:
        // ^           — начало строки
        // [A-ZА-Я]{1,4} — 1-4 букв латинского или кириллического алфавита (можно адаптировать)
        // $           — конец строки
        return preg_match('/^[A-Za-zА-Яа-я]{1,4}$/u', $name);
    });

    // Преиндексация массива (если нужно)
    $letter_sizes = array_values($letter_sizes);

    $occupations = ['Для йоги', 'Для активного спорта', 'Для бега', 'Для похода в зал'];

    $vwMatch = [
        ['name' => 'Футболки', 'slug' => 'tshirts', 'isPopular' => true, 'gender' => 'unisex', 'season' => 'summer'],
        ['name' => 'Майки', 'slug' => 'tank-tops', 'isPopular' => true, 'gender' => 'unisex', 'season' => 'summer'],
        ['name' => 'Лонгсливы', 'slug' => 'longsleeves', 'isPopular' => false, 'gender' => 'unisex', 'season' => 'allseason'],
        ['name' => 'Худи', 'slug' => 'hoodies', 'isPopular' => true, 'gender' => 'unisex', 'season' => 'winter'],
        ['name' => 'Свитшоты', 'slug' => 'sweatshirts', 'isPopular' => true, 'gender' => 'unisex', 'season' => 'allseason'],
        ['name' => 'Толстовки', 'slug' => 'sweaters', 'isPopular' => false, 'gender' => 'unisex', 'season' => 'winter'],
        ['name' => 'Шорты', 'slug' => 'shorts', 'isPopular' => true, 'gender' => 'unisex', 'season' => 'summer'],
        ['name' => 'Спортивные штаны', 'slug' => 'joggers', 'isPopular' => true, 'gender' => 'unisex', 'season' => 'winter'],
        ['name' => 'Леггинсы', 'slug' => 'leggings', 'isPopular' => true, 'gender' => 'women', 'season' => 'allseason'],
        ['name' => 'Брюки', 'slug' => 'pants', 'isPopular' => false, 'gender' => 'unisex', 'season' => 'allseason'],
        ['name' => 'Ветровки', 'slug' => 'windbreakers', 'isPopular' => false, 'gender' => 'unisex', 'season' => 'allseason'],
        ['name' => 'Спортивные куртки', 'slug' => 'sports-jackets', 'isPopular' => true, 'gender' => 'unisex', 'season' => 'winter'],
        ['name' => 'Термобелье', 'slug' => 'thermal-underwear', 'isPopular' => false, 'gender' => 'unisex', 'season' => 'winter'],
        ['name' => 'Компрессионная одежда', 'slug' => 'compression-wear', 'isPopular' => false, 'gender' => 'unisex', 'season' => 'allseason'],
        ['name' => 'Футболки-поло', 'slug' => 'polo-shirts', 'isPopular' => false, 'gender' => 'men', 'season' => 'summer'],
        ['name' => 'Куртки', 'slug' => 'jackets', 'isPopular' => true, 'gender' => 'unisex', 'season' => 'winter'],
        ['name' => 'Безрукавки', 'slug' => 'vests', 'isPopular' => false, 'gender' => 'men', 'season' => 'summer'],
        ['name' => 'Купальники', 'slug' => 'swimwear', 'isPopular' => true, 'gender' => 'women', 'season' => 'summer'],
        ['name' => 'Купальные шорты', 'slug' => 'swim-shorts', 'isPopular' => true, 'gender' => 'men', 'season' => 'summer'],
        ['name' => 'Спортивные платья', 'slug' => 'sports-dresses', 'isPopular' => false, 'gender' => 'women', 'season' => 'summer'],
        ['name' => 'Юбки', 'slug' => 'skirts', 'isPopular' => false, 'gender' => 'women', 'season' => 'summer'],
        ['name' => 'Топы', 'slug' => 'tops', 'isPopular' => true, 'gender' => 'women', 'season' => 'summer'],
        ['name' => 'Костюмы спортивные', 'slug' => 'tracksuits', 'isPopular' => true, 'gender' => 'unisex', 'season' => 'allseason'],
        ['name' => 'Комбинезоны', 'slug' => 'jumpsuits', 'isPopular' => false, 'gender' => 'women', 'season' => 'allseason'],
        ['name' => 'Анораки', 'slug' => 'anoraks', 'isPopular' => false, 'gender' => 'unisex', 'season' => 'allseason'],
        ['name' => 'Балмакаан', 'slug' => 'balmakan', 'isPopular' => false, 'gender' => 'men', 'season' => 'winter'],
    ];

    global $wpdb;
    $min_price = (float) $wpdb->get_var("SELECT MIN(CAST(meta_value AS UNSIGNED)) FROM {$wpdb->postmeta} WHERE meta_key='_price' AND meta_value != ''");
    $max_price = (float) $wpdb->get_var("SELECT MAX(CAST(meta_value AS UNSIGNED)) FROM {$wpdb->postmeta} WHERE meta_key='_price' AND meta_value != ''");

    ob_start();
    locate_template("template-parts/catalog/filter/ctg-filter-pc.php", true, null, array(
        'subcategories' => array_values($subcategories),
        'colors' => $colors,
        'collections' => $collections,
        'occupations' => $occupations,
        'min_price' => $min_price ? $min_price : 0,
        'max_price' => $max_price ? $max_price : 0,
        'sizes' => $letter_sizes,
        'sortBy' => [
            [
                "name" => "По популярности",
                "slug" => "popular" // сортировка по популярности (sales)
            ],
            [
                "name" => "По возрастанию цены",
                "slug" => "price_asc"
            ],
            [
                "name" => "По убыванию цены",
                "slug" => "price_desc"
            ],
            [
                "name" => "По новизне",
                "slug" => "date_desc"
            ]
        ],
        'vwMatch' => $vwMatch,
    ));

    //    var_dump($sizes);

    return ob_get_clean();
}

function woocommerce_mob_filter_shortcode()
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
    $excluded_names = ['Misc', 'All'];

    // Исключаем по имени
    $subcategories = array_filter($subcategories, function ($term) use ($excluded_names) {
        return !in_array($term->name, $excluded_names, true);
    });

    $subcategory_ids = wp_list_pluck($subcategories, 'term_id');

    // Если нет подкатегорий, добавляем текущую категорию
    if (empty($subcategory_ids) && $parent_id) {
        $subcategory_ids[] = $parent_id;
    }

    delete_transient('unique_product_colors_cat_' . md5(implode(',', $subcategory_ids)));

    // Генерируем уникальный ключ для кэша, зависящий от категории
    $cache_key = 'unique_product_colors_cat_' . md5(implode(',', $subcategory_ids));
    $colors = get_transient($cache_key);

    if ($colors === false) {
        $colors = [];

        // Запрос только нужных товаров
        $query_args = [
            'post_type' => 'product',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => 12,
            'no_found_rows' => true,
            'tax_query' => [
                [
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => $subcategory_ids,
                    'operator' => 'IN',
                ],
            ],
        ];

        $query = new WP_Query($query_args);

        if ($query->have_posts()) {
            foreach ($query->posts as $product_id) {
                $product_colors = get_field('product_colors', $product_id);

                if (empty($product_colors) || !is_array($product_colors)) {
                    continue;
                }

                foreach ($product_colors as $color_item) {
                    if (!isset($color_item['color_rel'][0]) || !is_object($color_item['color_rel'][0])) {
                        continue;
                    }

                    $color_post = $color_item['color_rel'][0];
                    $color_id = $color_post->ID;

                    // Ключ по slug
                    $color_slug = get_field('color_slug', $color_id);
                    if (empty($color_slug)) {
                        continue;
                    }

                    $slug = sanitize_title($color_slug);

                    // Не добавляем дубликаты
                    if (isset($colors[$slug])) {
                        continue;
                    }

                    $colors[$slug] = [
                        'slug' => $slug,
                        'name' => get_the_title($color_id) ?: ucfirst($slug),
                        'hex' => get_field('color_code', $color_id) ?: '#fff',
                    ];
                }
            }
        }

        // Упорядочиваем массив и кэшируем
        $colors = array_values($colors);
        set_transient($cache_key, $colors, HOUR_IN_SECONDS);
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
    $vwMatch = [
        ['name' => 'Футболки', 'slug' => 'tshirts', 'isPopular' => true, 'gender' => 'unisex', 'season' => 'summer'],
        ['name' => 'Майки', 'slug' => 'tank-tops', 'isPopular' => true, 'gender' => 'unisex', 'season' => 'summer'],
        ['name' => 'Лонгсливы', 'slug' => 'longsleeves', 'isPopular' => false, 'gender' => 'unisex', 'season' => 'allseason'],
        ['name' => 'Худи', 'slug' => 'hoodies', 'isPopular' => true, 'gender' => 'unisex', 'season' => 'winter'],
        ['name' => 'Свитшоты', 'slug' => 'sweatshirts', 'isPopular' => true, 'gender' => 'unisex', 'season' => 'allseason'],
        ['name' => 'Толстовки', 'slug' => 'sweaters', 'isPopular' => false, 'gender' => 'unisex', 'season' => 'winter'],
        ['name' => 'Шорты', 'slug' => 'shorts', 'isPopular' => true, 'gender' => 'unisex', 'season' => 'summer'],
        ['name' => 'Спортивные штаны', 'slug' => 'joggers', 'isPopular' => true, 'gender' => 'unisex', 'season' => 'winter'],
        ['name' => 'Леггинсы', 'slug' => 'leggings', 'isPopular' => true, 'gender' => 'women', 'season' => 'allseason'],
        ['name' => 'Брюки', 'slug' => 'pants', 'isPopular' => false, 'gender' => 'unisex', 'season' => 'allseason'],
        ['name' => 'Ветровки', 'slug' => 'windbreakers', 'isPopular' => false, 'gender' => 'unisex', 'season' => 'allseason'],
        ['name' => 'Спортивные куртки', 'slug' => 'sports-jackets', 'isPopular' => true, 'gender' => 'unisex', 'season' => 'winter'],
        ['name' => 'Термобелье', 'slug' => 'thermal-underwear', 'isPopular' => false, 'gender' => 'unisex', 'season' => 'winter'],
        ['name' => 'Компрессионная одежда', 'slug' => 'compression-wear', 'isPopular' => false, 'gender' => 'unisex', 'season' => 'allseason'],
        ['name' => 'Футболки-поло', 'slug' => 'polo-shirts', 'isPopular' => false, 'gender' => 'men', 'season' => 'summer'],
        ['name' => 'Куртки', 'slug' => 'jackets', 'isPopular' => true, 'gender' => 'unisex', 'season' => 'winter'],
        ['name' => 'Безрукавки', 'slug' => 'vests', 'isPopular' => false, 'gender' => 'men', 'season' => 'summer'],
        ['name' => 'Купальники', 'slug' => 'swimwear', 'isPopular' => true, 'gender' => 'women', 'season' => 'summer'],
        ['name' => 'Купальные шорты', 'slug' => 'swim-shorts', 'isPopular' => true, 'gender' => 'men', 'season' => 'summer'],
        ['name' => 'Спортивные платья', 'slug' => 'sports-dresses', 'isPopular' => false, 'gender' => 'women', 'season' => 'summer'],
        ['name' => 'Юбки', 'slug' => 'skirts', 'isPopular' => false, 'gender' => 'women', 'season' => 'summer'],
        ['name' => 'Топы', 'slug' => 'tops', 'isPopular' => true, 'gender' => 'women', 'season' => 'summer'],
        ['name' => 'Костюмы спортивные', 'slug' => 'tracksuits', 'isPopular' => true, 'gender' => 'unisex', 'season' => 'allseason'],
        ['name' => 'Комбинезоны', 'slug' => 'jumpsuits', 'isPopular' => false, 'gender' => 'women', 'season' => 'allseason'],
        ['name' => 'Анораки', 'slug' => 'anoraks', 'isPopular' => false, 'gender' => 'unisex', 'season' => 'allseason'],
        ['name' => 'Балмакаан', 'slug' => 'balmakan', 'isPopular' => false, 'gender' => 'men', 'season' => 'winter'],
    ];


    ob_start();
    locate_template("template-parts/catalog/filter/ctg-filter-mb.php", true, null, array(
        'subcategories' => $subcategories,
        'colors' => $colors,
        'collections' => $collections,
        'occupations' => $occupations,
        'min_price' => $min_price,
        'max_price' => $max_price,
        'sizes' => $sizes,
        'sortBy' => [
            [
                "name" => 'По популярности',
                "slug" => 'popular'
            ]
        ],
        'vwMatch' => $vwMatch,
    ));

    //    var_dump($sizes);

    return ob_get_clean();
}

add_shortcode('woocommerce_filter', 'woocommerce_filter_shortcode');
add_shortcode('woocommerce_mob_filter', 'woocommerce_mob_filter_shortcode');

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
    try {
        if (!is_admin() && $query->is_main_query() && (is_shop() || is_product_category() || is_tax('prd_collection'))) {
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
                        'operator' => 'IN' // или 'IN', в зависимости от логики фильтра
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
                    'value' => (float) $_GET['min_price'],
                    'compare' => '>=',
                    'type' => 'NUMERIC'
                ];
            }

            if (isset($_GET['max_price'])) {
                $meta_query[] = [
                    'key' => '_price',
                    'value' => (float) $_GET['max_price'],
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

            //TODO: REFACTOR CODE

            // Фильтр по цвету
            // if (isset($_GET['color_ex']) && !empty($_GET['color_ex'])) {
            //     $color_slugs = explode(',', sanitize_text_field($_GET['color_ex']));
            //     $color_ids = [];

            //     foreach ($color_slugs as $slug) {
            //         $slug = sanitize_title(trim($slug));
            //         $posts = get_posts([
            //             'post_type' => 'product_color', // тип поста цвета
            //             'meta_key' => 'color_slug',
            //             'meta_value' => $slug,
            //             'posts_per_page' => 1,
            //             'fields' => 'ids',
            //         ]);
            //         if (!empty($posts)) {
            //             $color_ids[] = $posts[0];
            //         }
            //     }

            //     if (!empty($color_ids)) {
            //         $color_meta_query = ['relation' => 'OR'];
            //         foreach ($color_ids as $id) {
            //             $color_meta_query[] = [
            //                 'key' => 'product_colors_%_color_rel',
            //                 'value' => $id,
            //                 'compare' => '=',
            //             ];
            //         }
            //         $meta_query[] = $color_meta_query;
            //     }
            // }


            //        print_r($query->get('post__in'), true);

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

            // === СОРТИРОВКА ===
            $orderby = 'date';
            $order = 'DESC';
            $meta_key = '';


            if (!is_admin() && $query->is_main_query() && (is_shop() || is_product_category() || is_tax('prd_collection'))) {
                $sortBy = isset($_GET['sortBy']) ? sanitize_text_field($_GET['sortBy']) : '';

                switch ($sortBy) {
                    case 'popular':
                        $query->set('meta_key', 'total_sales');
                        $query->set('orderby', 'meta_value_num');
                        $query->set('order', 'DESC');
                        break;

                    case 'price_asc':
                        $query->set('meta_key', '_price');
                        $query->set('orderby', 'meta_value_num');
                        $query->set('order', 'ASC');
                        break;

                    case 'price_desc':
                        $query->set('meta_key', '_price');
                        $query->set('orderby', 'meta_value_num');
                        $query->set('order', 'DESC');
                        break;

                    case 'date_desc':
                    default:
                        $query->set('orderby', 'date');
                        $query->set('order', 'DESC');
                        break;
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

            // применяем сортировку
            $query->set('orderby', $orderby);
            $query->set('order', $order);


            if (!empty($meta_key)) {
                $query->set('meta_key', $meta_key);
            }
        }
    } catch (e) {

        $meta_query = $query->get('meta_query') ?: [];
        $tax_query = $query->get('tax_query') ?: [];

        // Обновляем параметры
        if ($tax_query) {
            $query->set('tax_query', $tax_query);
        }

        if ($meta_query) {
            $query->set('meta_query', $meta_query);
        }
        // применяем сортировку
        $query->set('orderby', $orderby);
        $query->set('order', $order);

        if (!empty($meta_key)) {
            $query->set('meta_key', $meta_key);
        }
    }
}

//add_action('posts_request', function($sql) {
//    print_r('SQL запроса: ' . $sql);
//    return $sql;
//});

function get_subcategories_tree($parent_id, $excluded_names = [])
{
    $subcategories = get_terms([
        'taxonomy' => 'product_cat',
        'parent' => $parent_id,
        'hide_empty' => true,
        'orderby' => 'name',
    ]);

    $result = [];

    foreach ($subcategories as $subcategory) {
        if (in_array($subcategory->name, $excluded_names, true))
            continue;

        $children = get_subcategories_tree($subcategory->term_id, $excluded_names);

        $result[] = [
            'term_id' => $subcategory->term_id,
            'slug' => $subcategory->slug,
            'name' => $subcategory->name,
            'count' => $subcategory->count,
            'children' => $children,
        ];
    }

    return $result;
}


//



function render_filter_view_pc_shortcode($atts)
{
    ob_start();

    //    get_template_part(
//        'shortcodes/product-card-template',
//        null,
//        array('post_id' => get_the_ID())
//    );

    get_template_part("template-parts/catalog/filter/view/view-filter-pc", null, array(
        'sortBy' => [
            [
                "name" => "По популярности",
                "slug" => "popular" // сортировка по популярности (sales)
            ],
            [
                "name" => "По возрастанию цены",
                "slug" => "price_asc"
            ],
            [
                "name" => "По убыванию цены",
                "slug" => "price_desc"
            ],
            [
                "name" => "По новизне",
                "slug" => "date_desc"
            ]
        ],
    ));

    wp_reset_postdata();
    return ob_get_clean();
    //    return "<div style='margin-bottom: 30px'>Filter</div>";
}

add_shortcode('filter_view_pc', 'render_filter_view_pc_shortcode');