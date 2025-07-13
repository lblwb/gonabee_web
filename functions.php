<?php

function favicon_link()
{
    echo '<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" />' . "\n";
}

add_action('wp_head', 'favicon_link');

// Disable REST API endpoints
//add_filter('rest_endpoints', function() {
//    return [];
//});

// Disable REST API HTTP header link
remove_action('template_redirect', 'rest_output_link_header', 11);

/**
 * Basic setup style & scripts
 * @return void
 */
function kts_setup()
{
    // Не админ часть
    if (!is_admin()) {
        wp_enqueue_script(
            'variables-gonna',
            get_template_directory_uri() . '/assets/js/variables.js',
            array(),
            null,
            false // в <head>
        );
        wp_enqueue_script('gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js', array(), null, true);
        wp_enqueue_script("notify", get_stylesheet_directory_uri() . '/assets/js/notify.js', array(), null, true);
        wp_enqueue_script("axios", get_stylesheet_directory_uri() . '/assets/js/axios.js', array(), null, true);
        //        wp_enqueue_script("state", get_stylesheet_directory_uri() . '/assets/js/alpine.js',  array(), null true);
        //        wp_enqueue_script("spark-vibe", get_stylesheet_directory_uri() . '/assets/js/spark_vibe.js', array(), null, false);
        wp_enqueue_script("vue", get_stylesheet_directory_uri() . '/assets/js/vue.global.js', array(), null, false);
        wp_enqueue_style("swpr-sldr-bnd-sty", get_stylesheet_directory_uri() . '/assets/css/slider/swiper-bundle.min.css');
        wp_enqueue_script("swpr-sldr-bnd-scr", get_stylesheet_directory_uri() . '/assets/js/slider/swiper-bundle.min.js');

        wp_enqueue_style("style-main", get_stylesheet_directory_uri() . '/assets/css/main.css', array(), time());
        //        wp_enqueue_script("script-main", get_stylesheet_directory_uri() . '/assets/js/main.js', '', '', true);
        wp_enqueue_script('script-main', get_template_directory_uri() . '/assets/js/main.js', array(), '1.0', true);
        wp_enqueue_script('script-sergey', get_template_directory_uri() . '/assets/js/sergey.js', array(), '1.0', true);

        wp_enqueue_script('nouislider-js', 'https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.js', array(), null, true);
        wp_enqueue_style('nouislider-css', 'https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.css', array(), null);
        //

    } else {
        // Добавить поддержку редактора
        // add_theme_support('editor-styles');
        // add_editor_style('assets/css/editor.css');
    }
}

add_action('wp_enqueue_scripts', 'kts_setup');


/**
 * Оптимизация темы PressedSteel: удаление лишнего, совместимость с FSE + WooCommerce
 */

function optimize_pressed_steel_theme(): void
{
    // 🔌 Отключаем emoji
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

    // 🔒 Удаление лишнего из <head>
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
    remove_action('wp_head', 'rest_output_link_wp_head', 10);
    remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
    remove_action('template_redirect', 'rest_output_link_header', 11, 0);

    // 🛡 Отключаем XML-RPC
    add_filter('xmlrpc_enabled', '__return_false');

    // 🚫 Отключаем wp-embed
    //    add_action('wp_footer', function () {
    //        wp_deregister_script('wp-embed');
    //    });

    // 🚫 Удаляем jQuery Migrate (если не нужен)
    add_action('wp_default_scripts', function ($scripts) {
        if (!is_admin() && isset($scripts->registered['jquery'])) {
            $scripts->registered['jquery']->deps = array_diff(
                $scripts->registered['jquery']->deps,
                ['jquery-migrate']
            );
        }
    });

    // 🛍 WooCommerce: убираем стили, если используем кастомные
    //    add_filter('woocommerce_enqueue_styles', '__return_empty_array');

    // 🛒 WooCommerce: отключаем скрипты на лишних страницах
    //    add_action('wp_enqueue_scripts', function () {
    //        if (!is_woocommerce() && !is_cart() && !is_checkout()) {
    //            wp_dequeue_style('woocommerce-general');
    //            wp_dequeue_script('wc-cart-fragments');
    //        }
    //    }, 99);

    // ✅ Подключаем поддержку WooCommerce и галерей
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}

add_action('after_setup_theme', 'optimize_pressed_steel_theme');

//add_action('wp_footer', function () {
//    echo '<pre>';
//    print_r(wp_scripts());
//    echo '</pre>';
//});


function get_wc_category_title_shortcode() {
    // Проверяем наличие $_GET['collection'] и его валидность
    if (isset($_GET['collection']) && !empty($_GET['collection'])) {
        $collection_slug = sanitize_text_field($_GET['collection']);
        $term = get_term_by('slug', $collection_slug, 'prd_collection');

        if ($term && !is_wp_error($term)) {
            return esc_html($term->name);
        }
    }

    // Если нет валидной коллекции, проверяем категорию продукта
    if (is_product_category()) {
        $term = get_queried_object();
        return esc_html($term->name);
    }

    // Если ни коллекция, ни категория не найдены, возвращаем "Магазин"
    return esc_html__('Магазин', 'text-domain');
}

add_shortcode('product_category_title', 'get_wc_category_title_shortcode');

//

function render_filter_main_pc_shortcode($atts)
{
    // Параметры шорткода с значениями по умолчанию
    $atts = shortcode_atts(
        array(
            'id' => '123', // ID Filter Set
            'title' => '"Мои фильтры', // Заголовок виджета
            'show_count' => '1', // Показывать количество постов (1 = включено, 0 = выключено)
            'chips' => '1', // Показывать chips (1 = включено, 0 = выключено)
            'horizontal' => '0', // Горизонтальная раскладка (1 = включено, 0 = выключено)
            //            'cols_count'  => '3', // Количество столбцов
        ),
        $atts,
        'filter_everything'
    );
    // Буфер для захвата вывода виджета
    ob_start();
    the_widget(
        'FiltersWidget',
        array(
            'id' => $atts['id'],
            'title' => $atts['title'],
            'show_count' => $atts['show_count'],
            'chips' => $atts['chips'],
            'horizontal' => $atts['horizontal'],
            'cols_count' => $atts['cols_count'],
        ),
        array(
            'before_widget' => '<div class="widget filters-widget">',
            'after_widget' => '</div>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        )
    );
    return ob_get_clean();
}

add_shortcode('filter_main_pc', 'render_filter_main_pc_shortcode');

function render_category_products_shortcode($atts)
{
    $atts = shortcode_atts(array(
        'paged' => 1,
        'term_id' => null,
    ), $atts);

    if (!$atts['term_id']) {
        $term = get_queried_object();
        $atts['term_id'] = $term->term_id ?? 0;
    }

    // Получаем глобальный запрос WordPress
    global $wp_query;

    // Инициализируем meta_query и tax_query из глобального запроса, если они есть
    $meta_query = $wp_query->query_vars['meta_query'] ?? [];
    $tax_query = $wp_query->query_vars['tax_query'] ?? [];

    $type_view = $_GET['view'] ?? false;

    // Добавляем фильтр по категории, если term_id передан, но аккуратно — чтобы не дублировать фильтр по product_cat
    if ($atts['term_id']) {
        // Проверим, есть ли уже фильтр по product_cat в tax_query
        $has_product_cat_filter = false;
        foreach ($tax_query as $tax_filter) {
            if (isset($tax_filter['taxonomy']) && $tax_filter['taxonomy'] === 'product_cat') {
                $has_product_cat_filter = true;
                break;
            }
        }

        if (!$has_product_cat_filter) {
            $tax_query[] = array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => intval($atts['term_id']),
            );
        }
    }

    // 🔽 Обработка sortBy (если передан)
    // var_dump($_GET['sortBy']);

    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'paged' => intval($atts['paged']),
//        'posts_per_page' => 12,
        'posts_per_page' => 12,
        'meta_query' => [
            [
                'key' => '_stock_status',
                'value' => 'instock',
                'compare' => '='
            ]
        ],
        // 'meta_key' => 'date',
        // 'order' => 'DESC',
        // 'meta_query'     => array(
        //     array(
        //         'key'     => '_stock_status',
        //         'value'   => 'instock',
        //     ),
        // ),
    );

    $sort_by = isset($_GET['sortBy']) ? sanitize_text_field($_GET['sortBy']) : '';
    switch ($sort_by) {
        case 'popular':
            $args['meta_key'] = 'total_sales';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
            break;

        case 'price_asc':
            $args['meta_key'] = '_price';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'ASC';
            break;

        case 'price_desc':
            $args['meta_key'] = '_price';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
            break;

        case 'date_desc':
        default:
            $args['orderby'] = 'date';
            $args['order'] = 'DESC';
            break;
    }

    // echo '<pre>'; print_r($args); echo '</pre>';

    // Передаём meta_query и tax_query из глобального запроса
    if (!empty($meta_query)) {
        $args['meta_query'] = $meta_query;
    }

    if (!empty($tax_query)) {
        $args['tax_query'] = $tax_query;
    }


    $products = new WP_Query($args);

    //    var_dump($products->request);

    ob_start();
    if ($type_view && $type_view == 'grid') {
?>
        <style>
            :root {
                --catalog-raw-count: 3;
            }
        </style>
    <?
    }
    //    var_dump($products);

    // 🔽 Получаем цветовые фильтры из GET
    $selected_colors = [];
    if (!empty($_GET['color_ex'])) {
        $selected_colors = array_filter(array_map('sanitize_title', explode(',', $_GET['color_ex'])));
    }



    $has_products = false;

    //    var_dump($products);


    // 🔍 Фильтрация по цветам после выборки

    if ($products->have_posts()) {
        while ($products->have_posts()) {
            $products->the_post();


            $post_id = get_the_ID();



            // 🔍 Получаем все цвета товара
            $product_colors = [];
            if (have_rows('product_colors', $post_id)) {
                while (have_rows('product_colors', $post_id)) {
                    the_row();
                    $color_rel = get_sub_field('color_rel');
                    $color_post = is_array($color_rel) ? $color_rel[0] ?? null : $color_rel;
                    if ($color_post && is_object($color_post)) {
                        $color_slug = get_field('color_slug', $color_post->ID);
                        if (!empty($color_slug)) {
                            $product_colors[] = sanitize_title($color_slug);
                        }
                    }
                }
            }

            // 🧠 Фильтрация — если заданы цвета, то проверяем наличие пересечений
            if (!empty($selected_colors)) {
                $intersect = array_intersect($selected_colors, $product_colors);
                if (empty($intersect)) {
                    continue; // пропускаем товар, если ни одного совпадения
                }
            }

            // ✅ Если дошли сюда — товар удовлетворяет фильтру
            $has_products = true;

            //var_dump($products);

            get_template_part(
                'shortcodes/product-card-template',
                null,
                array('post_id' => get_the_ID())
            );
        }

        if (!$has_products) {
            echo "<div class='notFound'><div class='notFoundTitle'>Товары не найдены.</div></div>";
        }
    } else {
        echo "<div class='notFound'><div class='notFoundTitle'>Товары не найдены.</div></div>";
    }
    wp_reset_postdata();

    $output = ob_get_clean();

    return $output;
}

add_shortcode('category_products', 'render_category_products_shortcode');


add_action('wp_ajax_load_more_category_products', 'ajax_load_more_category_products');
add_action('wp_ajax_nopriv_load_more_category_products', 'ajax_load_more_category_products');

function ajax_load_more_category_products()
{
    $paged = intval($_POST['paged'] ?? 1);
    $term_id = intval($_POST['term_id'] ?? 0);
    echo do_shortcode('[category_products paged="' . $paged . '" term_id="' . $term_id . '"]');
    wp_die();
}

function render_load_more_products_shortcode($atts) {
    // Define default attributes
    $atts = shortcode_atts([
        'paged' => 1,
        'term_id' => null,
    ], $atts);

    // Set term_id from queried object if not provided
    if (!$atts['term_id']) {
        $term = get_queried_object();
        $atts['term_id'] = isset($term->term_id) ? $term->term_id : 0;
    }

    global $wp_query;
    $meta_query = $wp_query->query_vars['meta_query'] ?? [];
    $tax_query = $wp_query->query_vars['tax_query'] ?? [];

    // Add product category filter if term_id is provided
    if ($atts['term_id']) {
        $has_product_cat_filter = false;
        foreach ($tax_query as $tax_filter) {
            if (isset($tax_filter['taxonomy']) && $tax_filter['taxonomy'] === 'product_cat') {
                $has_product_cat_filter = true;
                break;
            }
        }

        if (!$has_product_cat_filter) {
            $tax_query[] = [
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => intval($atts['term_id']),
            ];
        }
    }

    // Handle subcategory filter
    if (!empty($_GET['subcategory'])) {
        $subcats = array_map('sanitize_text_field', explode(',', $_GET['subcategory']));
        if ($subcats) {
            $tax_query[] = [
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => array_filter($subcats),
                'operator' => 'IN',
            ];
        }
    }

    // Handle collection filter
    if (!empty($_GET['collection'])) {
        $tax_query[] = [
            'taxonomy' => 'prd_collection',
            'field' => 'slug',
            'terms' => array_map('sanitize_text_field', explode(',', $_GET['collection'])),
            'operator' => 'AND',
        ];
    }

    // Handle price filters
    if (!empty($_GET['min_price'])) {
        $meta_query[] = [
            'key' => '_price',
            'value' => (float) $_GET['min_price'],
            'compare' => '>=',
            'type' => 'NUMERIC',
        ];
    }

    if (!empty($_GET['max_price'])) {
        $meta_query[] = [
            'key' => '_price',
            'value' => (float) $_GET['max_price'],
            'compare' => '<=',
            'type' => 'NUMERIC',
        ];
    }

    // Handle size filter
    if (!empty($_GET['size'])) {
        $sizes = array_map('sanitize_title', array_filter(array_map('trim', explode(',', sanitize_text_field($_GET['size'])))));
        if ($sizes) {
            $tax_query[] = [
                'taxonomy' => 'pa_sizes',
                'field' => 'slug',
                'terms' => $sizes,
                'operator' => 'AND',
            ];
        }
    }

    // Handle occupation filter
    if (!empty($_GET['occupation'])) {
        foreach (array_map('sanitize_text_field', explode(',', $_GET['occupation'])) as $occupation) {
            $meta_query[] = [
                'key' => 'occupation',
                'value' => '"' . $occupation . '"',
                'compare' => 'LIKE',
            ];
        }
    }

    // Setup WP_Query arguments
    $args = [
        'post_type' => 'product',
        'post_status' => 'publish',
        'paged' => intval($atts['paged']),
        'posts_per_page' => 12,
        'meta_query' => [
            [
                'key' => '_stock_status',
                'value' => 'instock',
                'compare' => '=',
            ],
        ],
    ];

    if ($meta_query) {
        $args['meta_query'] = array_merge($args['meta_query'], $meta_query);
    }

    if ($tax_query) {
        $args['tax_query'] = $tax_query;
    }

    $selected_colors = [];

    $products_query = new WP_Query($args);
    error_log('Query args: ' . print_r($args, true));
    error_log('Found posts: ' . $products_query->found_posts);
    error_log('Max num pages: ' . $products_query->max_num_pages);

    ob_start();
    $has_products = false;
    if ($products_query->have_posts()) {
        while ($products_query->have_posts()) {
            $products_query->the_post();
            $post_id = get_the_ID();
            $product_colors = [];
            if (have_rows('product_colors', $post_id)) {
                while (have_rows('product_colors', $post_id)) {
                    the_row();
                    $color_rel = get_sub_field('color_rel');
                    $color_post = is_array($color_rel) ? ($color_rel[0] ?? null) : $color_rel;
                    if ($color_post && is_object($color_post)) {
                        $color_slug = get_field('color_slug', $color_post->ID);
                        if ($color_slug) {
                            $product_colors[] = sanitize_title($color_slug);
                        }
                    }
                }
            }
            error_log('Product ID: ' . $post_id . ', Colors: ' . print_r($product_colors, true));
            error_log('Selected colors: ' . print_r($selected_colors, true));

            if ($selected_colors && !array_intersect($selected_colors, $product_colors)) {
                error_log('Product ID: ' . $post_id . ' skipped due to color filter');
                continue;
            }
            $has_products = true;
        }

        wp_reset_postdata();
        wp_reset_query();

        if ($has_products) {
            error_log('Loading template for page: ' . $atts['paged']);
            get_template_part('shortcodes/load-more-template', null, $atts);
        }
    }

    $output = ob_get_clean();
    error_log('Shortcode output length: ' . strlen($output));
    return $has_products ? $output : '';
}

add_shortcode('load_more_products', 'render_load_more_products_shortcode');

function enqueue_load_more_script()
{
    // Подключаем скрипт inline в футер
    add_action('wp_footer', function () {
    ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Добавляем CSS, как раньше
                const style = document.createElement('style');
                style.textContent = `
    .loading-spinner {
      display: inline-block;
      width: 16px;
      height: 16px;
      margin-left: 8px;
      border: 2px solid rgba(255, 255, 255, 0.3);
      border-top-color: #000;
      border-radius: 50%;
      animation: spinner 0.6s linear infinite;
      vertical-align: middle;
    }
    @keyframes spinner {
      to { transform: rotate(360deg); }
    }
  `;
                document.head.appendChild(style);

                const button = document.getElementById('load_more_products');
                if (!button) return;

                let spinner = button.querySelector('.loading-spinner');
                if (!spinner) {
                    spinner = document.createElement('span');
                    spinner.className = 'loading-spinner';
                    spinner.style.display = 'none';
                    button.appendChild(spinner);
                }

                // Обернём текст кнопки в span, чтобы удобно скрывать
                let textSpan = button.querySelector('.btn-text');
                if (!textSpan) {
                    const nodes = Array.from(button.childNodes);
                    textSpan = document.createElement('span');
                    textSpan.className = 'btn-text';
                    nodes.forEach(n => {
                        if (n !== spinner) textSpan.appendChild(n);
                    });
                    button.insertBefore(textSpan, spinner);
                }

                button.addEventListener('click', function() {
                    const paged = parseInt(this.dataset.paged) || 1;
                    const term = this.dataset.term || '';
                    const ajaxUrl = this.dataset.url;
                    const nextPage = paged + 1;

                    // Скрываем текст и показываем спиннер
                    textSpan.style.display = 'none';
                    spinner.style.display = 'inline-block';
                    button.disabled = true;

                    fetch(ajaxUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: new URLSearchParams({
                                action: 'load_more_products',
                                paged: nextPage,
                                term_id: term,
                            }),
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                const {
                                    html,
                                    max_num_pages,
                                    current_page
                                } = data.data;

                                if (html.trim()) {
                                    const container = document.querySelector('.shopCatalogCollection');
                                    if (container) {
                                        container.insertAdjacentHTML('beforeend', html);
                                    }
                                }

                                button.dataset.paged = current_page;

                                if (current_page >= max_num_pages) {
                                    button.remove();
                                } else {
                                    button.disabled = false;
                                }
                            } else {
                                button.disabled = false;
                            }
                        })
                        .catch((err) => {
                            console.error(err);
                            button.disabled = false;
                        })
                        .finally(() => {
                            // Скрываем спиннер, показываем текст обратно
                            spinner.style.display = 'none';
                            textSpan.style.display = '';
                        });
                    setTimeout(() => {
                        // favBtnPrd();
                        swipperInit().then();
                        // favBtnPrdSync().then();
                        subGalleryProductCard().then();
                    }, 1800);
                });

            });
        </script>
<?php
    }, 400); // 100 — чтобы скрипт точно после основного контента
}
add_action('wp_enqueue_scripts', 'enqueue_load_more_script');

function custom_woocommerce_cart_shortcode()
{
    ob_start();
    get_template_part('shortcodes/cart-template');
    return ob_get_clean();
}

add_shortcode('custom_cart', 'custom_woocommerce_cart_shortcode');

function custom_woocommerce_ideaprod_shortcode()
{
    ob_start();
    locate_template('shortcodes/idea-product-template.php', true, null);
    return ob_get_clean();
}

add_shortcode('idea_product', 'custom_woocommerce_ideaprod_shortcode');

function custom_woocommerce_checkout_shortcode()
{
    ob_start();
    locate_template('shortcodes/checkout-template.php', true, null);
    return ob_get_clean();
}

add_shortcode('custom_checkout', 'custom_woocommerce_checkout_shortcode');



// Сохраняем отчество
add_action('woocommerce_checkout_update_order_meta', function ($order_id) {
    if (!empty($_POST['billing_middle_name'])) {
        update_post_meta($order_id, '_billing_middle_name', sanitize_text_field($_POST['billing_middle_name']));
    }
});

add_action('woocommerce_before_checkout_form', function () {
    echo '<div class="legal-entity-toggle" onclick="toggleCheckbox(this, \'__Active\')" style="display: flex; align-items: center; gap: 10px"><div class="icon"><svg width="42" height="26" viewBox="0 0 42 26" fill="none" xmlns="http://www.w3.org/2000/svg">
<rect x="2" width="40" height="22" rx="11" fill="#E5E5E5"/>
<g filter="url(#filter0_d_3_15151)">
<rect x="4" y="2" width="18" height="18" rx="9" fill="white"/>
</g>
<defs>
<filter id="filter0_d_3_15151" x="0" y="0" width="26" height="26" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
<feFlood flood-opacity="0" result="BackgroundImageFix"/>
<feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
<feOffset dy="2"/>
<feGaussianBlur stdDeviation="2"/>
<feComposite in2="hardAlpha" operator="out"/>
<feColorMatrix type="matrix" values="0 0 0 0 0.152941 0 0 0 0 0.152941 0 0 0 0 0.152941 0 0 0 0.1 0"/>
<feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_3_15151"/>
<feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_3_15151" result="shape"/>
</filter>
</defs>
</svg>
</div>
        <label style="display: flex; margin-bottom: 0.5em;">
        <input type="checkbox" name="legal_entity" id="legal_entity">
        Юридическое лицо</label>
    </div> <div class="separate" style="padding: 20px 0; opacity: 0.3"><hr></div>';
});


add_action('init', function () {
    if (!isset($_GET['decrease_cart_item']))
        return;

    $product_id = intval($_GET['decrease_cart_item']);
    $cart = WC()->cart;

    if (!$cart)
        return;

    foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
        if ($cart_item['product_id'] == $product_id) {
            $new_quantity = $cart_item['quantity'] - 1;

            if ($new_quantity <= 0) {
                $cart->remove_cart_item($cart_item_key);
            } else {
                $cart->set_quantity($cart_item_key, $new_quantity);
            }

            // Редирект, чтобы избежать повторного уменьшения при обновлении страницы
            wp_safe_redirect(wc_get_cart_url());
            exit;
        }
    }
});

add_action('init', function () {
    if (!isset($_GET['increase_cart_item']))
        return;

    $product_id = intval($_GET['increase_cart_item']);
    $cart = WC()->cart;

    if (!$cart)
        return;

    foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
        if ($cart_item['product_id'] == $product_id) {
            $new_quantity = $cart_item['quantity'] + 1;

            if ($new_quantity <= 0) {
                $cart->remove_cart_item($cart_item_key);
            } else {
                $cart->set_quantity($cart_item_key, $new_quantity);
            }

            // Редирект, чтобы избежать повторного уменьшения при обновлении страницы
            wp_safe_redirect(wc_get_cart_url());
            exit;
        }
    }
});


// В functions.php или в вашем плагине
add_action('wp_ajax_load_more_products', 'ajax_load_more_products');
add_action('wp_ajax_nopriv_load_more_products', 'ajax_load_more_products');

function ajax_load_more_products()
{
    $paged = isset($_REQUEST['paged']) ? max(1, intval($_REQUEST['paged'])) : 1;
    $term_id = isset($_REQUEST['term_id']) ? intval($_REQUEST['term_id']) : 0;
    $current_loaded_page = isset($_REQUEST['current_loaded_page']) ? intval($_REQUEST['current_loaded_page']) : 0;

    // Проверка: если запрос пытается загрузить страницу, которую клиент уже загрузил — просто возвращаем пустой ответ
    if ($paged <= $current_loaded_page) {
        wp_send_json_success([
            'html' => '',            // пустой html — нет новых товаров
            'max_num_pages' => $current_loaded_page,
            'current_page' => $current_loaded_page,
            'message' => 'No new pages to load',
        ]);
    }

    // Далее всё как обычно — подготавливаем WP_Query с paged
    global $wp_query;

    $meta_query = $wp_query->query_vars['meta_query'] ?? [];
    $tax_query = $wp_query->query_vars['tax_query'] ?? [];

    if ($term_id) {
        $has_product_cat_filter = false;
        foreach ($tax_query as $tax_filter) {
            if (isset($tax_filter['taxonomy']) && $tax_filter['taxonomy'] === 'product_cat') {
                $has_product_cat_filter = true;
                break;
            }
        }
        if (!$has_product_cat_filter) {
            $tax_query[] = [
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $term_id,
            ];
        }
    }

    $args = [
        'post_type' => 'product',
        'post_status' => 'publish',
        'paged' => $paged,
        'posts_per_page' => 12,
        'meta_query' => [
            [
                'key' => '_stock_status',
                'value' => 'instock',
                'compare' => '='
            ]
        ],
    ];

    if (!empty($meta_query)) {
        $args['meta_query'] = $meta_query;
    }
    if (!empty($tax_query)) {
        $args['tax_query'] = $tax_query;
    }

    $products = new WP_Query($args);


    // 🔽 Получаем цветовые фильтры из GET
    $selected_colors = [];
    if (!empty($_GET['color_ex'])) {
        $selected_colors = array_filter(array_map('sanitize_title', explode(',', $_GET['color_ex'])));
    }

    $has_products = false;

    //    var_dump($products);


    ob_start();

    // 🔍 Фильтрация по цветам после выборки

    if ($products->have_posts()) {
        while ($products->have_posts()) {
            $products->the_post();


            $post_id = get_the_ID();

            // 🔍 Получаем все цвета товара
            $product_colors = [];
            if (have_rows('product_colors', $post_id)) {
                while (have_rows('product_colors', $post_id)) {
                    the_row();
                    $color_rel = get_sub_field('color_rel');
                    $color_post = is_array($color_rel) ? $color_rel[0] ?? null : $color_rel;
                    if ($color_post && is_object($color_post)) {
                        $color_slug = get_field('color_slug', $color_post->ID);
                        if (!empty($color_slug)) {
                            $product_colors[] = sanitize_title($color_slug);
                        }
                    }
                }
            }

            // 🧠 Фильтрация — если заданы цвета, то проверяем наличие пересечений
            if (!empty($selected_colors)) {
                $intersect = array_intersect($selected_colors, $product_colors);
                if (empty($intersect)) {
                    continue; // пропускаем товар, если ни одного совпадения
                }
            }

            // ✅ Если дошли сюда — товар удовлетворяет фильтру
            $has_products = true;

            get_template_part('shortcodes/product-card-template', null, ['post_id' => get_the_ID()]);
            //var_dump($products)

        }

        if (!$has_products) {
            return '';
        }
    }

    wp_reset_postdata();

    $html = ob_get_clean();

    wp_send_json_success([
        'html' => $html,
        'max_num_pages' => $products->max_num_pages,
        'current_page' => $paged,
    ]);
}



// 2. Обновляем пункты меню
add_filter('woocommerce_account_menu_items', 'custom_my_account_menu_items');
function custom_my_account_menu_items($items)
{
    // Удалим ненужные
    unset($items['dashboard']);
    unset($items['downloads']);
    unset($items['edit-address']);
    unset($items['payment-methods']);

    // Переименуем
    $items['edit-account'] = 'Мои данные';
    $items['orders'] = 'Мои заказы';
    $items['customer-logout'] = 'Выйти из аккаунта';

    // Добавим свои
    $custom_links = array(
        'bonuses' => 'Бонусы',
        'help' => 'Помощь',
    );

    // Задать нужный порядок
    $new_order = array('edit-account', 'orders', 'bonuses', 'help', 'customer-logout');
    $sorted_items = array();

    foreach ($new_order as $key) {
        if (isset($items[$key])) {
            $sorted_items[$key] = $items[$key];
        } elseif (isset($custom_links[$key])) {
            $sorted_items[$key] = $custom_links[$key];
        }
    }

    return $sorted_items;
}

// 2. Добавляем их в меню "Мой аккаунт"
add_filter('woocommerce_account_menu_items', function ($items) {
    // Переупорядочим, если нужно
    $new_items = [];

    foreach ($items as $key => $label) {
        $new_items[$key] = $label;

        // После заказов вставим "Бонусы"
        if ($key === 'orders') {
            $new_items['bonuses'] = 'Бонусы';
            $new_items['help'] = 'Помощь';
        }
    }

    return $new_items;
});


//Account Redirect
//add_action('template_redirect', function () {
//    if (trim($_SERVER['REQUEST_URI'], '/') === 'account') {
//        wp_redirect(home_url('/account/edit/'), 301); // 301 — постоянный редирект
//        exit;
//    }
//});


/**
 * Автоматически добавляем пункт "Распродажа" в конец FSE‑меню
 */
add_filter('render_block', 'mytheme_add_sale_to_navigation', 10, 2);
function mytheme_add_sale_to_navigation(string $block_content, array $block): string
{
    // Проверяем, что это именно core/navigation
    if (isset($block['blockName']) && $block['blockName'] === 'core/navigation') {
        // Получаем параметры для вывода меню QuadMenu
        $args = quadmenu_get_nav_menu_args();

        // Генерируем меню с помощью wp_nav_menu
        ob_start();
        wp_nav_menu($args);
        $wp_nav = ob_get_clean();

        // Заменяем стандартное меню на вывод QuadMenu
        $block_content = preg_replace(
            '/<nav[^>]*>(.*?)<\/nav>/is',
            $wp_nav,  // Вставляем результат работы wp_nav_menu в блок
            $block_content
        );

        //        var_dump($block_content);

        //
    }

    return $block_content;
}


function custom_my_account_orders_content()
{
    return locate_template("shortcodes/account-orders-template.php", true, null);
}

remove_action('woocommerce_account_orders_endpoint', 'woocommerce_account_orders', 10);
add_action('woocommerce_account_orders_endpoint', 'custom_my_account_orders_content');


function custom_orders_account()
{
    ob_start();
    locate_template("shortcodes/account-orders-list-template.php", true, null);
    return ob_get_clean();
}

add_shortcode('account_orders_list', 'custom_orders_account');

// Добавь этот код в functions.php или плагин-сниппет
add_shortcode('account_side_nav', function () {
    ob_start();
    locate_template("shortcodes/account-nav-template.php", true, null);
    return ob_get_clean();
});


// Добавь этот код в functions.php или плагин-сниппет
add_shortcode('account_bonus_prg', function () {
    ob_start();
    locate_template("shortcodes/account-bonus-prg.php", true, null);
    return ob_get_clean();
});


add_shortcode('account_mob_nav', function () {
    ob_start();
    locate_template("shortcodes/account-navmob-template.php", true, null);
    return ob_get_clean();
});

add_shortcode('fe_mob_full_filter', function () {
    ob_start();
    locate_template("inc/mobile-full-filter.php", true, null);
    return ob_get_clean();
});


// Отключить форму входа на странице корзины
function remove_checkout_login_form()
{
    if (is_checkout()) {
        remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_login_form');
    }
}

add_action('wp', 'remove_checkout_login_form');


//
add_filter('widget_title', 'make_product_category_title_clickable', 10, 3);
function make_product_category_title_clickable($title, $instance, $id_base)
{
    if ($id_base === 'product_categories' && $title === 'Мужская одежда') {
        $url = get_term_link('мужская-одежда', 'product_cat');
        return '<a href="' . esc_url($url) . '" class="quadmenu-title">' . esc_html($title) . '</a>';
    }
    return $title;
}

// Подключаем хелперы
require_once(__DIR__ . "/inc/helpers/str-to-bool.php");


require_once(__DIR__ . "/inc/favorites-shc.php");
require_once(__DIR__ . "/inc/add-to-cart.php");
// require_once(__DIR__ . "/inc/checkout-ipc.php");
// HOOK Account Edit
require_once(__DIR__ . "/inc/account-edit-hk.php");
// HOOK Search
require_once(__DIR__ . "/inc/search-hk.php");
require_once(__DIR__ . "/inc/apply_coupon.php");
require_once(__DIR__ . "/inc/idea-product.php");
require_once(__DIR__ . "/inc/ctg-filter.php");
require_once(__DIR__ . "/inc/product-cat-menu-widget.php");
require_once(__DIR__ . "/inc/product-coll-menu-widget.php");
require_once(__DIR__ . "/inc/banner-menu-widget.php");
require_once(__DIR__ . "/inc/account-auth.php");
require_once(__DIR__ . "/inc/ajax-prd-info.php");
require_once(__DIR__ . "/inc/ajax-update-prd-cart.php");
require_once(__DIR__ . "/inc/faq-helper.php");
require_once(__DIR__ . "/inc/checkout/remove-actions.php");
require_once(__DIR__ . "/inc/checkout/remove-field.php");
require_once(__DIR__ . "/inc/checkout/fragments.php");
require_once(__DIR__ . "/inc/checkout/needs-shipping.php");
require_once(__DIR__ . "/inc/checkout/notice-filter.php");
require_once(__DIR__ . "/inc/checkout/add-color-to-order.php");
require_once(__DIR__ . "/inc/checkout/add-shipping-raw-to-order.php");



// Добавляем стили на страницу оплаты 
add_action('woocommerce_api_wc_pk_gateway', function () {
    // (если нужно) получаем заказ
    $order_id = isset($_GET['order']) ? absint($_GET['order']) : 0;
    error_log("📦 [paykeeper_api] Загрузка формы оплаты для заказа #$order_id");

    // твой HTML/форма (например — оригинальный метод или свой)
    echo '<style>
        body {
            background: #222222;
            font-family: "Montserrat", sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            gap: 20px;
            min-height: 100vh;
            color: white
        }
        h3 {
            font-size: 30px;
            margin: 0;
            max-width: 470px;
            text-align: center;
        }
        .btn.btn-default {
            background: #F0C224;
            padding: 16px;
            display: block;
            border-radius: 25px;
            text-align: center;
            text-decoration: none;
            color: #252525;
            margin-bottom: 16px;
            border: none;
            min-width: 300px;
        }
    </style>';
});
/**
 * Форматирует значение купона в зависимости от его типа.
 *
 * @param string|float $amount  Сама величина скидки (из WC_Coupon::get_amount()).
 * @param string       $type    Тип скидки: 'percent', 'fixed_cart', 'fixed_product' и т. п.
 * @return string    Отформатированная строка, например "10%" или "₽500.00"
 */

function format_coupon_amount($amount, $type)
{
    // Приведём amount к строке (может быть передан и как число и как строка)
    $amount_str = (string) $amount;

    switch ($type) {
        case 'percent':
            // Для процентных купонов добавляем знак %
            // Приводим к целому или оставляем дробную часть как есть
            return esc_html($amount_str . '%');

        case 'fixed_cart':
        case 'fixed_product':
            // Для фиксированных скидок используем wc_price()
            // wc_price сам подставит валютный символ и отформатирует число
            return wc_price($amount_str);

        default:
            // Про запас: просто выведем число без форматирования
            return esc_html($amount_str);
    }
}

add_action('woocommerce_checkout_update_order_review', 'toggle_entity');
function toggle_entity()
{
    if (empty($_POST['post_data'])) {
        error_log('⚠️ [toggle_entity] post_data пустой, выходим.');
        return;
    }

    // Парсим post_data
    parse_str(wp_unslash($_POST['post_data']), $post_data);
    $legal_entity = (!empty($post_data['legal_entity']) && $post_data['legal_entity'] === 'on');

    // Логируем результат разбора
    error_log("🔍 [toggle_entity] legal_entity = " . ($legal_entity ? 'on' : 'off'));
    error_log('📦 [toggle_entity] post_data: ' . print_r($post_data, true));

    // Регистрируем фильтр, захватывая $legal_entity
    add_filter('woocommerce_checkout_fields', function ($fields) use ($legal_entity) {
        // Логируем вход в колбэк
        error_log("🛠️ [toggle_entity callback] запустился, legal_entity = " . ($legal_entity ? 'on' : 'off'));

        if (!$legal_entity) {
            error_log('✅ [toggle_entity callback] legal_entity off — возвращаем стандартные поля.');
            return $fields;
        }

        error_log('🔄 [toggle_entity callback] добавляем кастомные поля биллинга для юр. лица.');

        // Название компании *
        $fields['billing']['billing_company_name'] = array(
            'type' => 'text',
            'label' => 'Название компании',
            'placeholder' => 'Введите название компании',
            'required' => true,
            'priority' => 10,
        );

        // Контактное лицо *
        $fields['billing']['billing_contact_person'] = array(
            'type' => 'text',
            'label' => 'Контактное лицо',
            'placeholder' => 'ФИО контактного лица',
            'required' => true,
            'priority' => 20,
        );

        // Юридический адрес
        $fields['billing']['billing_legal_address'] = array(
            'type' => 'text',
            'label' => 'Юридический адрес',
            'placeholder' => 'Введите юридический адрес',
            'required' => false,
            'priority' => 30,
        );

        // ИНН
        $fields['billing']['billing_inn'] = array(
            'type' => 'text',
            'label' => 'ИНН',
            'placeholder' => 'Введите ИНН',
            'required' => false,
            'priority' => 40,
        );

        // КПП
        $fields['billing']['billing_kpp'] = array(
            'type' => 'text',
            'label' => 'КПП',
            'placeholder' => 'Введите КПП',
            'required' => false,
            'priority' => 50,
        );



        // Факс
        $fields['billing']['billing_fax'] = array(
            'type' => 'text',
            'label' => 'Факс',
            'placeholder' => 'Введите номер факса',
            'required' => false,
            'priority' => 80,
        );

        error_log('✅ [toggle_entity callback] кастомные поля добавлены.');

        // Убираем лишние поля
        unset($fields['billing']['billing_last_name']);
        unset($fields['billing']['billing_first_name']);
        unset($fields['billing']['billing_middle_name']);

        return $fields;
    }, 2100);
}
