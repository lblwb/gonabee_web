<?php

add_action('wp_ajax_nopriv_check_email_exists', function () {
    $email = sanitize_email($_POST['email']);
//    var_dump($email);
    wp_send_json(['exists' => (bool)email_exists($email)]);
});

add_action('wp_ajax_nopriv_ajax_login', 'custom_ajax_login');
//add_action('wp_ajax_ajax_login', 'custom_ajax_login');

function custom_ajax_login() {
    $email    = isset($_POST['email'])    ? sanitize_email($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Проверка наличия
    if ( empty($email) || empty($password) ) {
        wp_send_json_error(['message' => 'Необходимо ввести email и пароль']);
    }

    // Получаем пользователя по email
    $user = get_user_by('email', $email);
    if ( !$user ) {
        wp_send_json_error(['message' => 'Пользователь с таким email не найден']);
    }

//    var_dump($password);

    // Аутентификация по username
    $auth_user = wp_authenticate($user->user_login, $password);
    if ( is_wp_error($auth_user) ) {
        wp_send_json_error(['message' => 'Неверный email или пароль']);
    }

    // Если уже авторизован другой пользователь — выходим
    if ( is_user_logged_in() ) {
        $current_user = wp_get_current_user();
        if ( $current_user->ID !== $auth_user->ID ) {
            wp_logout();
        }
    }

    // Авторизуем
    wp_set_current_user($auth_user->ID);
    wp_set_auth_cookie($auth_user->ID, true); // true — запомнить

    // Ответ
    wp_send_json_success([
        'user' => [
            'id'     => $auth_user->ID,
            'name'   => $auth_user->display_name,
            'email'  => $auth_user->user_email,
            'avatar' => get_avatar_url($auth_user->ID),
        ]
    ]);
}


add_action('wp_ajax_nopriv_ajax_register', function () {
    $email = sanitize_email($_POST['email']);
    $password = $_POST['password'];
    if (email_exists($email)) {
        wp_send_json_error(['message' => 'Email уже зарегистрирован']);
    }
    $user_id = wp_create_user($email, $password, $email);
    if (is_wp_error($user_id)) {
        wp_send_json_error(['message' => 'Ошибка регистрации']);
    }
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);
    $user = get_user_by('id', $user_id);
    wp_send_json_success([
        'user' => [
            'name' => $user->display_name,
            'email' => $user->user_email
        ]
    ]);
});

add_action('wp_ajax_nopriv_ajax_logout', function () {
    wp_logout();
    wp_send_json_success();
});
