<?php
// Функция для выборки избранных товаров
function get_favorite_products()
{
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => '_is_favorite',
                'value' => 'yes',
                'compare' => '='
            )
        )
    );
    $query = new WP_Query($args);
    $favorites = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $favorites[] = array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'link' => get_permalink(),
            );
        }
        wp_reset_postdata();
    }
    return $favorites;
}

// Функция для выборки категорий избранных товаров
function get_favorite_categories()
{
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => '_is_favorite',
                'value' => 'yes',
                'compare' => '='
            )
        )
    );
    $query = new WP_Query($args);
    $categories = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $terms = get_the_terms(get_the_ID(), 'product_cat');
            if ($terms && !is_wp_error($terms)) {
                foreach ($terms as $term) {
                    $categories[$term->slug] = $term;
                }
            }
        }
        wp_reset_postdata();
    }
    return $categories;
}


// Шорткод для вывода категорий избранных товаров [favorites_categories]
function display_favorites_categories_shortcode()
{
    $terms = get_favorite_categories();

    $output = '';

    if (!empty($terms)) {
        $output .= '<h3>Категории избранных:</h3>';
        $output .= '<ul>';
        foreach ($terms as $term) {
            $term_link = get_term_link($term);
            $output .= '<li><a href="' . esc_url($term_link) . '">' . esc_html($term->name) . '</a></li>';
        }
        $output .= '</ul>';
    } else {
        $output .= '<p>Нет избранных категорий.</p>';
    }
    return $output;
}

add_shortcode('favorites_categories', 'display_favorites_categories_shortcode');


function get_user_favorites()
{
    $favorites = array();
    $is_logged_in = is_user_logged_in();

    if ($is_logged_in) {
        // Для авторизованных пользователей: получаем из user_meta
        $user_id = get_current_user_id();
        $favorites = get_user_meta($user_id, 'favorite_products', true);
        if (!is_array($favorites)) {
            $favorites = array();
        }

        // Синхронизация с cookie, если пользователь только что авторизовался
        if (isset($_COOKIE['favorite_products'])) {
            $cookie_favorites = json_decode(stripslashes($_COOKIE['favorite_products']), true);
            if (is_array($cookie_favorites)) {
                $favorites = array_unique(array_merge($favorites, $cookie_favorites));
                update_user_meta($user_id, 'favorite_products', $favorites);
                // Очищаем cookie
                setcookie('favorite_products', '', time() - 3600, '/');
            }
        }
    } else {
        // Для неавторизованных: получаем из cookie
        $favorites = isset($_COOKIE['favorite_products']) ? json_decode(stripslashes($_COOKIE['favorite_products']), true) : array();
        if (!is_array($favorites)) {
            $favorites = array();
        }
    }

    // Приводим к числовым ID и удаляем дубликаты
    $favorites = array_map('absint', array_unique($favorites));

    return $favorites;
}


// Шорткод для вывода списка избранных товаров [favorites_items]
function display_favorites_items_shortcode()
{
    // $user_id = get_current_user_id(); // Получаем ID текущего пользователя
    // $favorites = get_user_meta($user_id, 'favorite_products', true); // Получаем массив ID избранных товаров
    $favorites = get_user_favorites();

    $output = '';

    if (!empty($favorites) && is_array($favorites)) {
        $output .= '<div class="favorites-list" style="display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 36px;">';

        foreach ($favorites as $product_id) {
            $product = wc_get_product($product_id);
            if ($product) {
                ob_start();
                $output .= get_template_part(
                    'shortcodes/product-card-template',
                    null,
                    array('post_id' => $product_id, 'add_cart_btn' => true)
                );
                $output .= ob_get_clean();
            }
        }

        $output .= '</div>';
    } else {
        $output .= '<div class="emptyCart">
                <div class="emptyCartWrap">
                    <div class="emptyCartHead">
                        <div class="emptyCartHeadImg">
                            <svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M32 0L45.92 7.88985L59.7128 16L59.84 32L59.7128 48L45.92 56.1101L32 64L18.08 56.1101L4.28719 48L4.16 32L4.28719 16L18.08 7.88985L32 0Z" fill="#F0C224"/>
                                <path d="M27.0093 27.9996V25.9996C27.0093 23.2382 29.2479 20.9996 32.0093 20.9996C34.7707 20.9996 37.0093 23.2382 37.0093 25.9996V27.9996H40.0093C40.5616 27.9996 41.0093 28.4473 41.0093 28.9996V40.9997C41.0093 41.5519 40.5616 41.9997 40.0093 41.9997H24.0093C23.457 41.9997 23.0093 41.5519 23.0093 40.9997V28.9996C23.0093 28.4473 23.457 27.9996 24.0093 27.9996H27.0093ZM27.0093 29.9996H25.0093V39.9997H39.0093V29.9996H37.0093V31.9997H35.0093V29.9996H29.0093V31.9997H27.0093V29.9996ZM29.0093 27.9996H35.0093V25.9996C35.0093 24.3428 33.6661 22.9996 32.0093 22.9996C30.3524 22.9996 29.0093 24.3428 29.0093 25.9996V27.9996Z" fill="#252525"/>
                            </svg>
                        </div>
                    </div>
                    <div class="emptyCartHeading">
                        <div class="emptyCartHeadingTitle">В избранном пока нет товаров</div>
                        <div class="emptyCartHeadingDesc">Здесь появятся товары, которые вы отложите в избранное</div>
                    </div>
                </div>
            </div>';
    }

    return $output;
}

add_shortcode('favorites_items', 'display_favorites_items_shortcode');

// Add AJAX actions for logged in users
//add_action('wp_ajax_toggle_favorite', 'handle_toggle_favorite');
//
//function handle_toggle_favorite()
//{
//    // Check if user is logged in
//    if (!is_user_logged_in()) {
//        wp_send_json_error(array('message' => __('You need to be logged in.', 'your-textdomain')));
//    }
//
//    // Verify nonce for security
////    $nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
////    if ( ! wp_verify_nonce( $nonce, 'toggle_favorite_nonce' ) ) {
////        wp_send_json_error( array( 'message' => __( 'Nonce verification failed.', 'your-textdomain' ) ) );
////    }
//
//    // Get current user ID and product ID from POST
//    $user_id = get_current_user_id();
//    $product_id = isset($_GET['product_id']) ? absint($_GET['product_id']) : 0;
//
//    if (!$product_id) {
//        wp_send_json_error(array('message' => __('Invalid product ID.', 'your-textdomain')));
//    }
//
//    // Get user's saved favorite products
//    $favorites = get_user_meta($user_id, 'favorite_products', true);
//    if (!is_array($favorites)) {
//        $favorites = array();
//    }
//
//    // Toggle logic: if the product exists then remove it; otherwise, add it.
//    if (in_array($product_id, $favorites)) {
//        // Remove favorite
//        $favorites = array_diff($favorites, array($product_id));
//        $action = 'removed';
//    } else {
//        // Add favorite
//        $favorites[] = $product_id;
//        $action = 'added';
//    }
//
//    // Update user meta
//    update_user_meta($user_id, 'favorite_products', $favorites);
//
//    // Return success response
//    wp_send_json_success(array('action' => $action, 'favorites' => $favorites));
//}


// Add AJAX actions for both logged in and non-logged in users
add_action('wp_ajax_toggle_favorite', 'handle_toggle_favorite');
add_action('wp_ajax_nopriv_toggle_favorite', 'handle_toggle_favorite');

function handle_toggle_favorite()
{
    // Get product ID from GET
    $product_id = isset($_GET['product_id']) ? absint($_GET['product_id']) : 0;

    if (!$product_id) {
        wp_send_json_error(array('message' => __('Invalid product ID.', 'your-textdomain')));
    }

    $favorites = array();
    $is_logged_in = is_user_logged_in();

    if ($is_logged_in) {
        // For logged-in users: use user meta
        $user_id = get_current_user_id();
        $favorites = get_user_meta($user_id, 'favorite_products', true);
        if (!is_array($favorites)) {
            $favorites = array();
        }

        // Merge with cookie favorites if they exist (for users who just logged in)
        if (isset($_COOKIE['favorite_products'])) {
            $cookie_favorites = json_decode(stripslashes($_COOKIE['favorite_products']), true);
            if (is_array($cookie_favorites)) {
                $favorites = array_unique(array_merge($favorites, $cookie_favorites));
                // Update user meta with merged favorites
                update_user_meta($user_id, 'favorite_products', $favorites);
                // Clear cookie
                setcookie('favorite_products', '', time() - 3600, '/');
            }
        }
    } else {
        // For non-logged-in users: use cookie
        $favorites = isset($_COOKIE['favorite_products']) ? json_decode(stripslashes($_COOKIE['favorite_products']), true) : array();
        if (!is_array($favorites)) {
            $favorites = array();
        }
    }

    // Toggle logic
    if (in_array($product_id, $favorites)) {
        // Remove favorite
        $favorites = array_diff($favorites, array($product_id));
        $action = 'removed';
    } else {
        // Add favorite (limit to prevent cookie overflow, e.g., 50 items)
        if (count($favorites) < 50) {
            $favorites[] = $product_id;
            $action = 'added';
        } else {
            wp_send_json_error(array('message' => __('Maximum favorites limit reached.', 'your-textdomain')));
        }
    }

    // Store favorites
    if ($is_logged_in) {
        // Update user meta for logged-in users
        update_user_meta($user_id, 'favorite_products', $favorites);
    } else {
        // Update cookie for non-logged-in users (expires in 30 days)
        setcookie('favorite_products', json_encode($favorites), time() + (30 * DAY_IN_SECONDS), '/');
    }

    // Return success response
    wp_send_json_success(array('action' => $action, 'favorites' => $favorites));
}


add_action("wp_ajax_favorites_info", 'handle_favorites_info');
add_action("wp_ajax_nopriv_favorites_info", 'handle_favorites_info');
function handle_favorites_info()
{
    $favorites = array();
    $is_logged_in = is_user_logged_in();

    if ($is_logged_in) {
        // Получаем избранное из user_meta
        $user_id = get_current_user_id();
        $favorites = get_user_meta($user_id, 'favorite_products', true);

        if (!is_array($favorites)) {
            $favorites = array();
        }

        // Слияние с куками, если они есть
        if (isset($_COOKIE['favorite_products'])) {
            $cookie_favorites = json_decode(stripslashes($_COOKIE['favorite_products']), true);
            if (is_array($cookie_favorites)) {
                // Очистим и объединим
                $cookie_favorites = array_filter($cookie_favorites, fn($id) => is_numeric($id) && intval($id) > 0);
                $favorites = array_unique(array_merge($favorites, $cookie_favorites));
                update_user_meta($user_id, 'favorite_products', $favorites);

                // Удалим куку
                setcookie('favorite_products', '', time() - 3600, '/');
            }
        }
    } else {
        // Гость: берём из куки
        if (isset($_COOKIE['favorite_products'])) {
            $favorites = json_decode(stripslashes($_COOKIE['favorite_products']), true);
            if (!is_array($favorites)) {
                $favorites = array();
            }
            $favorites = array_filter($favorites, fn($id) => is_numeric($id) && intval($id) > 0);
        }
    }

    // Очистим от дублей и приведём к целым числам
    $favorites = array_unique(array_map('intval', $favorites));
    sort($favorites);

    // Отправляем JSON-ответ
    wp_send_json_success([
        'favorites' => $favorites,
        'favorites_count' => count($favorites),
    ]);
}
