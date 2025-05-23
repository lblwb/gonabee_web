<?php

function handle_add_to_cart_custom()
{
    // В этом примере для добавления в корзину можно разрешить и гостям, поэтому не требуем авторизации.
//    $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
//    if (!wp_verify_nonce($nonce, 'add_to_cart_nonce')) {
//        wp_send_json_error(array('message' => __('Ошибка проверки безопасности.', 'your-textdomain')));
//    }

//    wp_send_json_success($_GET);

    $product_id = isset($_GET['product_id']) ? absint($_GET['product_id']) : 0;
    if (!$product_id) {
        wp_send_json_error(array('message' => __('Некорректный ID товара.', 'your-textdomain')));
    }

    // Для простоты используем количество = 1 и стандартные настройки
    $quantity = 1;

    // Попытка добавить товар в корзину через WooCommerce API
    $added = WC()->cart->add_to_cart($product_id, $quantity);

    if ($added) {
        // Можно вернуть обновлённое количество товаров в корзине
        $cart_count = WC()->cart->get_cart_contents_count();
        wp_send_json_success(array(
            'message' => __('Товар добавлен в корзину.', 'add_product_cart'),
            'cart_count' => $cart_count,
            'action' => 'added',
        ));
    } else {
        wp_send_json_error(array('message' => __('Не удалось добавить товар в корзину.', 'not_product_cart')));
    }
}

// AJAX-обработчик для добавления товара в корзину
add_action('wp_ajax_nopriv_add_to_cart_mb', 'handle_add_to_cart_custom');
add_action('wp_ajax_add_to_cart_mb', 'handle_add_to_cart_custom');


function handle_cart_info()
{
    try {
        // Можно вернуть обновлённое количество товаров в корзине
        $cart_items = WC()->cart->get_cart();
        wp_send_json_success(array(
            'cart_count' => count($cart_items),
        ));
    } catch (e) {
        wp_send_json_error(array('success' => false, 'message' => __('Не удалось загрузить корзину.', 'not_product_cart')));
    }
}

// AJAX-обработчик для добавления товара в корзину
add_action('wp_ajax_nopriv_cart_info', 'handle_cart_info');
add_action('wp_ajax_cart_info', 'handle_cart_info');

// AJAX-обработчик обновление количества
function update_cart_item_qty()
{
    if (!isset($_GET['cart_item_key'], $_GET['qty'])) {
        wp_send_json_error(['message' => 'Missing parameters']);
    }

    $cart_item_key = sanitize_text_field($_GET['cart_item_key']);
    $qty = intval($_GET['qty']);

    if ($qty < 1) {
        wp_send_json_error(['message' => 'Invalid quantity']);
    }

    WC()->cart->set_quantity($cart_item_key, $qty, true);

    wc_clear_notices();
//    wc_add_notice('Количество товара обновлено', 'success');

    $cart_items = WC()->cart->get_cart();

    wp_send_json_success([
        'cart' => WC()->cart->get_cart(),
        'cart_count' => count($cart_items),
        'cart_count_total' => WC()->cart->get_cart_contents_count(),
        'cart_total_edit' => wc_price(WC()->cart->get_total('edit')),
    ]);
}

add_action('wp_ajax_update_cart_item_qty', 'update_cart_item_qty');
add_action('wp_ajax_nopriv_update_cart_item_qty', 'update_cart_item_qty');

// AJAX-обработчик: Удаление товара из корзины
function cart_item_remove()
{
    try {
        // Используем POST (лучше для AJAX)
        $cart_item_key = $_GET['cart_item_key'] ?? '';

        if (empty($cart_item_key)) {
            wp_send_json_error(['message' => 'Missing cart_item_key']);
        }
        $cart_item_key = sanitize_text_field($cart_item_key);

        // Проверим, существует ли товар в корзине
        if (!WC()->cart->get_cart_item($cart_item_key)) {
            wp_send_json_error(['message' => 'Item not found in cart']);
        }

        WC()->cart->remove_cart_item($cart_item_key);
        wp_send_json_success([
            'action' => 'removed',
            'cart_item_key' => $cart_item_key,
            'cart' => WC()->cart->get_cart(),
            'cart_total' => WC()->cart->get_cart_total(),
        ]);
    } catch (Exception $e) {
        wp_send_json_error([
            'action' => 'error',
            'message' => $e->getMessage(),
        ]);
    }
}

add_action('wp_ajax_cart_item_remove', 'cart_item_remove');
add_action('wp_ajax_nopriv_cart_item_remove', 'cart_item_remove');
