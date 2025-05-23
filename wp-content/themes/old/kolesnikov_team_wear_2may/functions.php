<?php

function favicon_link()
{
    echo '<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" />' . "\n";
}

add_action('wp_head', 'favicon_link');

/**
 * Basic setup style & scripts
 * @return void
 */
function kts_setup()
{
    // Не админ часть
    if (!is_admin()) {

        //        wp_enqueue_script("state", get_stylesheet_directory_uri() . '/assets/js/alpine.js',  array(), null true);
        wp_enqueue_script("spark-vibe", get_stylesheet_directory_uri() . '/assets/js/spark_vibe.js', array(), null, false);
        wp_enqueue_style("swpr-sldr-bnd-sty", get_stylesheet_directory_uri() . '/assets/css/slider/swiper-bundle.min.css');
        wp_enqueue_script("swpr-sldr-bnd-scr", get_stylesheet_directory_uri() . '/assets/js/slider/swiper-bundle.min.js');

        wp_enqueue_style("style-main", get_stylesheet_directory_uri() . '/assets/css/main.css');
//        wp_enqueue_script("script-main", get_stylesheet_directory_uri() . '/assets/js/main.js', '', '', true);
        wp_enqueue_script('script-main', get_template_directory_uri() . '/assets/js/main.js', ['spark-vibe'], '1.0', true);
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


add_action('init', function () {
});

//add_action('wp_footer', function () {
//    echo '<pre>';
//    print_r(wp_scripts());
//    echo '</pre>';
//});


function get_wc_category_title_shortcode()
{
    if (is_product_category()) {
        $term = get_queried_object();
        return esc_html($term->name);
    } else {
        return "Магазин";
    }

    return '';
}

add_shortcode('product_category_title', 'get_wc_category_title_shortcode');


//

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

    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'paged' => intval($atts['paged']),
        'posts_per_page' => 12,
    );

    if ($atts['term_id']) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $atts['term_id'],
            ),
        );
    }

    $products = new WP_Query($args);

    ob_start();

    if ($products->have_posts()) {
        while ($products->have_posts()) {
            $products->the_post();
            get_template_part('shortcodes/product-card-template', null, array('post_id' => get_the_ID()));
        }
    }

    wp_reset_postdata();

    return ob_get_clean();
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

function render_load_more_products_shortcode($atts)
{
//    $atts = shortcode_atts(array(
//        'paged' => 1,
//        'term_id' => null,
//    ), $atts);
//
//    if (!$atts['term_id']) {
//        $term = get_queried_object();
//        $atts['term_id'] = $term->term_id ?? 0;
//    }
//
//    $args = array(
//        'post_type' => 'product',
//        'post_status' => 'publish',
//        'paged' => intval($atts['paged']),
//        'posts_per_page' => 12,
//    );
//
//    if ($atts['term_id']) {
//        $args['tax_query'] = array(
//            array(
//                'taxonomy' => 'product_cat',
//                'field' => 'term_id',
//                'terms' => $atts['term_id'],
//            ),
//        );
//    }
//
//    $products = new WP_Query($args);
//
//    ob_start();

//    if ($products->have_posts()) {
//        while ($products->have_posts()) {
//            $products->the_post();
            get_template_part('shortcodes/load-more-template', null, array());
//        }
//    }

    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode('load_more_products', 'render_load_more_products_shortcode');

function custom_woocommerce_cart_shortcode()
{
    get_template_part('shortcodes/cart-template', null, null);
}

add_shortcode('custom_cart', 'custom_woocommerce_cart_shortcode');

function custom_woocommerce_ideaprod_shortcode()
{
    ob_start();
    get_template_part('shortcodes/idea-product-template', null, null);
    return ob_get_clean();
}

add_shortcode('idea_product', 'custom_woocommerce_ideaprod_shortcode');


function custom_woocommerce_checkout_shortcode()
{
    ob_start();
    get_template_part('shortcodes/checkout-template', null, null);
    return ob_get_clean();
}

add_shortcode('custom_checkout', 'custom_woocommerce_checkout_shortcode');

/***
 * @woocommerce
 */

function remove_country_fields_from_checkout($fields)
{
    // Удаляем поле выбора страны для платежного адреса
    if (isset($fields['billing']['billing_country'])) {
        unset($fields['billing']['billing_country']);
    }
    // Удаляем поле выбора страны для адреса доставки
    if (isset($fields['shipping']['shipping_country'])) {
        unset($fields['shipping']['shipping_country']);
    }
    return $fields;
}

add_filter('woocommerce_checkout_fields', 'remove_country_fields_from_checkout');

function set_default_checkout_country($address)
{
    $address['country'] = 'RU'; // Указываем нужный вам код страны, например, 'RU'
    return $address;
}

add_filter('default_checkout_billing_address', 'set_default_checkout_country');
add_filter('default_checkout_shipping_address', 'set_default_checkout_country');

//remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);

add_filter('woocommerce_checkout_fields', function ($fields) {

    // Убираем ненужные поля
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_address_1']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_city']);
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_postcode']);


    // Переопределяем порядок и placeholders
    $fields['billing']['billing_last_name']['priority'] = 10;
    $fields['billing']['billing_last_name']['placeholder'] = 'Введите фамилию';
    $fields['billing']['billing_first_name']['placeholder'] = 'Введите имя';
    $fields['billing']['billing_first_name']['priority'] = 25;
    $fields['billing']['billing_phone']['label'] = 'Номер телефона';
    $fields['billing']['billing_phone']['required'] = true;
    $fields['billing']['billing_phone']['placeholder'] = '+7 (999) 999-99-99';
    $fields['billing']['billing_email']['placeholder'] = 'ivanov@gmail.com';

    // Добавляем Отчество
    $fields['billing']['billing_middle_name'] = array(
        'type' => 'text',
        'label' => 'Отчество',
        'required' => false,
        'class' => array('form-row-wide'),
        'priority' => 45,
        'placeholder' => 'Введите отчество',
    );

    $fields['billing']['billing_email']['label'] = 'Адрес электронной почты';
//
//    echo var_dump($fields);

    return $fields;
});

remove_action('woocommerce_checkout_order_review', 'woocommerce_order_review', 10);
//remove_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review', 10 );

// Сохраняем отчество
add_action('woocommerce_checkout_update_order_meta', function ($order_id) {
    if (!empty($_POST['billing_middle_name'])) {
        update_post_meta($order_id, '_billing_middle_name', sanitize_text_field($_POST['billing_middle_name']));
    }
});

add_action('woocommerce_before_checkout_form', function () {
    echo '<div class="legal-entity-toggle" style="display: flex; align-items: center; gap: 10px"><div class="icon"><svg width="42" height="26" viewBox="0 0 42 26" fill="none" xmlns="http://www.w3.org/2000/svg">
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
    </div> <br> <hr> <br>';
});

//add_action('init', function () {
//    add_post_type_support('product', 'comments');
//});

remove_filter('the_content', 'wpautop');

add_action('init', function () {
    if (!isset($_GET['decrease_cart_item'])) return;

    $product_id = intval($_GET['decrease_cart_item']);
    $cart = WC()->cart;

    if (!$cart) return;

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
    if (!isset($_GET['increase_cart_item'])) return;

    $product_id = intval($_GET['increase_cart_item']);
    $cart = WC()->cart;

    if (!$cart) return;

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


add_action('wp_ajax_load_more_products', 'load_more_products_ajax');
add_action('wp_ajax_nopriv_load_more_products', 'load_more_products_ajax');

function load_more_products_ajax()
{
    $paged = isset($_POST['page']) ? intval($_POST['page']) + 1 : 2;

    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'paged' => $paged,
        'posts_per_page' => 12, // сколько товаров подгружать за раз
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        ob_start();
        while ($query->have_posts()) : $query->the_post();
            wc_get_template_part('content', 'product');
        endwhile;
        wp_reset_postdata();
        $html = ob_get_clean();
        echo $html;
    else :
        echo 0;
    endif;

    wp_die();
}


function test_case_reviews_products()
{
    if (isset($_GET['generate_random_reviews']) && current_user_can('administrator')) {

        $max_products = 10; // Количество товаров для генерации
        $min_reviews = 2;   // Минимальное количество отзывов для каждого товара
        $max_reviews = 5;   // Максимальное количество отзывов

        // Данные для генерации
        $names = ['Елена', 'Александр', 'Марина', 'Сергей', 'Татьяна', 'Никита', 'Ольга', 'Максим', 'Анна', 'Виктор'];
        $messages = [
            'Отличный товар, рекомендую!',
            'Качество превзошло ожидания.',
            'Доставка быстрая, упаковка надёжная.',
            'Буду заказывать ещё, очень доволен.',
            'Сомневался, но оказался отличный выбор!',
            '5 звёзд, всё на высшем уровне.',
            'Приятно удивлен качеством.',
            'Товар соответствует описанию.',
            'Уже посоветовал друзьям.',
            'Магазин работает чётко, без нареканий.'
        ];

        // Получаем товары случайным образом
        $products = get_posts([
            'post_type' => 'product',
            'post_status' => 'publish',
            'numberposts' => $max_products,
            'orderby' => 'rand',
        ]);

//        var_dump($products);

        foreach ($products as $product) {
            $reviews_count = rand($min_reviews, $max_reviews);
            for ($i = 0; $i < $reviews_count; $i++) {
                $author = $names[array_rand($names)];
                $email = sanitize_email(strtolower($author) . rand(1000, 9999) . '@example.com');
                $rating = rand(1, 5);
                $content = $messages[array_rand($messages)];

                $comment_id = wp_insert_comment([
                    'comment_post_ID' => $product->ID,
                    'comment_author' => $author,
                    'comment_author_email' => $email,
                    'comment_content' => $content,
                    'comment_approved' => 1,
                    'comment_type' => 'review',
                    'user_id' => 0,
                ]);

                if ($comment_id) {
                    add_comment_meta($comment_id, 'rating', $rating);
                }
            }
        }

        echo '<div class="updated"><p>✅ Случайные отзывы успешно сгенерированы для товаров.</p></div>';
    }
}


