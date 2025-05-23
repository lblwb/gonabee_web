<?php


// AJAX-обработчик для авторизованных пользователей
add_action('wp_ajax_toggle_favorite', 'handle_toggle_favorite');

function handle_toggle_favorite()
{
    // Проверяем, что пользователь авторизован
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => __('Требуется авторизация.', 'your-textdomain')));
    }

    // Проверка nonce для безопасности
    $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
    if (!wp_verify_nonce($nonce, 'toggle_favorite_nonce')) {
        wp_send_json_error(array('message' => __('Ошибка проверки безопасности.', 'your-textdomain')));
    }

    // Получаем ID пользователя и ID товара
    $user_id = get_current_user_id();
    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;

    if (!$product_id) {
        wp_send_json_error(array('message' => __('Некорректный ID товара.', 'your-textdomain')));
    }

    // Получаем избранные товары пользователя из мета-поля
    $favorites = get_user_meta($user_id, 'favorite_products', true);
    if (!is_array($favorites)) {
        $favorites = array();
    }

    // Если товар уже в избранном — удаляем, иначе добавляем
    if (in_array($product_id, $favorites)) {
        $favorites = array_diff($favorites, array($product_id));
        $action = 'removed';
    } else {
        $favorites[] = $product_id;
        $action = 'added';
    }

    update_user_meta($user_id, 'favorite_products', $favorites);

    wp_send_json_success(array('action' => $action, 'favorites' => $favorites));
}

function favorite_button_vue_shortcode()
{
    // Для гостей выводим сообщение о необходимости авторизации
    if (!is_user_logged_in()) {
        return '<p>' . __('Пожалуйста, авторизуйтесь для добавления товаров в избранное.', 'your-textdomain') . '</p>';
    }

    global $product;
    if (!$product instanceof WC_Product) {
        return '';
    }

    $product_id = $product->get_id();
    $nonce = wp_create_nonce('toggle_favorite_nonce');

    // Контейнер для Vue приложения
    ob_start();
    ?>
    <div id="favorite-app"
         data-product-id="<?php echo esc_attr($product_id); ?>"
         data-nonce="<?php echo esc_attr($nonce); ?>"
         data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
        <!-- Vue переменные будут подставляться сюда -->
        <button @click="toggleFavorite">
            {{ buttonText }}
        </button>
        <p v-if="message">{{ message }}</p>
    </div>
    <?php
    return ob_get_clean();
}

add_shortcode('favorite_button_vue', 'favorite_button_vue_shortcode');
