<?php

// Регистрация шорткода для редактирования аккаунта с кастомными полями
function custom_edit_account_shortcode()
{
    if (is_user_logged_in()) {
        ob_start(); // Начинаем захват вывода

        // Получаем текущего пользователя
        $user = wp_get_current_user();
        $gender = get_user_meta($user->ID, 'gender', true);

        ?>
        <form class="woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >
            <?php do_action( 'woocommerce_edit_account_form_start' ); ?>
            <p class="form-row form-row-first">
                <label for="first_name"><?php _e('Имя', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
                <input type="text" class="input-text" name="first_name" id="first_name" placeholder="Введите имя"
                       value="<?php echo esc_attr($user->first_name); ?>"/>
            </p>

            <p class="form-row form-row-last">
                <label for="last_name"><?php _e('Фамилия', 'woocommerce'); ?>&nbsp;<span
                            class="required">*</span></label>
                <input type="text" class="input-text" name="last_name" id="last_name" placeholder="Введите фамилию"
                       value="<?php echo esc_attr($user->last_name); ?>"/>
            </p>

            <p class="form-row form-row-wide">
                <label for="middle_name"><?php _e('Отчество', 'woocommerce'); ?></label>
                <input type="text" class="input-text" name="middle_name" id="middle_name" placeholder="Введите отчество"
                       value="<?php echo esc_attr(get_user_meta($user->ID, 'middle_name', true)); ?>"/>
            </p>

            <p class="form-row form-row-wide">
                <label for="birth_date"><?php _e('Дата рождения', 'woocommerce'); ?></label>
                <input type="date" class="input-text" name="birth_date" id="birth_date" placeholder="17.01.2001"
                       value="<?php echo esc_attr(get_user_meta($user->ID, 'birth_date', true)); ?>"/>
            </p>

            <p class="form-row form-row-wide">
                <label><?php _e('Пол', 'woocommerce'); ?></label>
                <span class="gender-option" data-gender="male">
            <input type="radio" name="gender" id="gender_male" value="male" <?php checked($gender, 'male'); ?> style="display: none;">
            <label for="gender_male">
                <?php if ($gender === 'male'): ?>
                    <label for="gender">Мужской</label>
                    <!-- Активный SVG для мужского пола -->
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="0.5" y="0.5" width="19" height="19" rx="9.5" stroke="#DCDCDC"/>
                        <circle cx="10" cy="10" r="4" fill="#E2B53C"/>
                    </svg>
                <?php else: ?>
                    <label for="gender">Мужской</label>
                    <!-- Неактивный SVG для мужского пола -->
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="0.5" y="0.5" width="19" height="19" rx="9.5" stroke="#DCDCDC"/>
                    </svg>
                <?php endif; ?>
            </label>
        </span>
                <span class="gender-option" data-gender="female">
            <input type="radio" name="gender" id="gender_female" value="female" <?php checked($gender, 'female'); ?> style="display: none;">
            <label for="gender_female">
                <?php if ($gender === 'female'): ?>
                    <label for="gender">Женский</label>
                    <!-- Активный SVG для женского пола -->
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="0.5" y="0.5" width="19" height="19" rx="9.5" stroke="#DCDCDC"/>
                        <circle cx="10" cy="10" r="4" fill="#E2B53C"/>
                    </svg>
                <?php else: ?>
                    <label for="gender">Женский</label>
                    <!-- Неактивный SVG для женского пола -->
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="0.5" y="0.5" width="19" height="19" rx="9.5" stroke="#DCDCDC"/>
                    </svg>
                <?php endif; ?>
            </label>
        </span>
            </p>

            <p class="form-row form-row-wide">
                <label for="phone"><?php _e('Номер телефона', 'woocommerce'); ?></label>
                <input type="tel" class="input-text" name="phone" id="phone" placeholder="+7 (999) 999-99-99"
                       value="<?php echo esc_attr(get_user_meta($user->ID, 'phone', true)); ?>"/>
            </p>

            <p class="form-row form-row-wide">
                <label for="email"><?php _e('Адрес электронной почты', 'woocommerce'); ?>&nbsp;<span
                            class="required">*</span></label>
                <input type="email" class="input-text" name="email" id="email" placeholder="Ivanov@gmail.com"
                       value="<?php echo esc_attr($user->user_email); ?>"/>
            </p>

            <p class="form-row">
                <button type="submit" name="save_account_details"
                        class="button"><?php _e('Сохранить изменения', 'woocommerce'); ?></button>
            </p>

            <?php wp_nonce_field('woocommerce-edit_account'); ?>

            <?php do_action( 'woocommerce_edit_account_form_end' ); ?>
        </form>
        <?php

        return ob_get_clean(); // Возвращаем захваченный вывод
    } else {
        // Если пользователь не авторизован
        return '<p>' . __('Пожалуйста, войдите, чтобы редактировать свой аккаунт.', 'woocommerce') . '</p>';
    }
}

// Регистрация шорткода
add_shortcode('custom_edit_account', 'custom_edit_account_shortcode');

function handle_custom_edit_account_form()
{
    // Проверим, была ли отправка формы и пользователь авторизован
    if (!is_user_logged_in() || !isset($_POST['save_account_details'])) {
        return;
    }

    // Проверка nonce
    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'woocommerce-edit_account')) {
        wc_add_notice(__('Ошибка безопасности. Попробуйте снова.', 'woocommerce'), 'error');
        return;
    }

    $user_id = get_current_user_id();

    // Очистка и подготовка данных
    $first_name = sanitize_text_field($_POST['first_name'] ?? '');
    $last_name = sanitize_text_field($_POST['last_name'] ?? '');
    $middle_name = sanitize_text_field($_POST['middle_name'] ?? '');
    $birth_date = sanitize_text_field($_POST['birth_date'] ?? '');
    $gender = sanitize_text_field($_POST['gender'] ?? '');
    $phone = sanitize_text_field($_POST['phone'] ?? '');
    $email = sanitize_email($_POST['email'] ?? '');

    // Валидация обязательных полей
    if (empty($first_name) || empty($last_name) || empty($email)) {
        wc_add_notice(__('Пожалуйста, заполните обязательные поля.', 'woocommerce'), 'error');
        return;
    }

    if (!is_email($email)) {
        wc_add_notice(__('Неверный формат электронной почты.', 'woocommerce'), 'error');
        return;
    }

    // Проверка, не занят ли email другим пользователем
    $user_with_email = get_user_by('email', $email);
    if ($user_with_email && $user_with_email->ID != $user_id) {
        wc_add_notice(__('Этот адрес электронной почты уже используется другим пользователем.', 'woocommerce'), 'error');
        return;
    }

    // Обновляем данные пользователя
    wp_update_user([
        'ID'         => $user_id,
        'first_name' => $first_name,
        'last_name'  => $last_name,
        'user_email' => $email,
    ]);

    // Обновляем мета-поля
    update_user_meta($user_id, 'middle_name', $middle_name);
    update_user_meta($user_id, 'birth_date', $birth_date);
    update_user_meta($user_id, 'gender', $gender);
    update_user_meta($user_id, 'phone', $phone);

    // Выводим сообщение об успехе
    wc_add_notice(__('Профиль успешно обновлён.', 'woocommerce'), 'success');
}
add_action('template_redirect', 'handle_custom_edit_account_form');



// Добавление кастомных полей в форму редактирования аккаунта
add_action('woocommerce_edit_account_form', 'add_custom_account_fields');
function add_custom_account_fields()
{
    $user = wp_get_current_user(); // Получаем текущего пользователя
    ?>
    <p class="form-row form-row-first">
        <label for="first_name"><?php _e('Имя', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
        <input type="text" class="input-text" name="first_name" id="first_name"
               value="<?php echo esc_attr($user->first_name); ?>"/>
    </p>

    <p class="form-row form-row-last">
        <label for="last_name"><?php _e('Фамилия', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
        <input type="text" class="input-text" name="last_name" id="last_name"
               value="<?php echo esc_attr($user->last_name); ?>"/>
    </p>

    <p class="form-row form-row-wide">
        <label for="middle_name"><?php _e('Отчество', 'woocommerce'); ?></label>
        <input type="text" class="input-text" name="middle_name" id="middle_name"
               value="<?php echo esc_attr(get_user_meta($user->ID, 'middle_name', true)); ?>"/>
    </p>

    <p class="form-row form-row-wide">
        <label for="birth_date"><?php _e('Дата рождения', 'woocommerce'); ?></label>
        <input type="date" class="input-text" name="birth_date" id="birth_date"
               value="<?php echo esc_attr(get_user_meta($user->ID, 'birth_date', true)); ?>"/>
    </p>

    <p class="form-row form-row-wide">
        <label><?php _e('Пол', 'woocommerce'); ?></label>
        <label for="gender_male">
            <input type="radio" name="gender" id="gender_male"
                   value="male" <?php checked(get_user_meta($user->ID, 'gender', true), 'male'); ?> /> <?php _e('Мужской', 'woocommerce'); ?>
        </label>
        <label for="gender_female">
            <input type="radio" name="gender" id="gender_female"
                   value="female" <?php checked(get_user_meta($user->ID, 'gender', true), 'female'); ?> /> <?php _e('Женский', 'woocommerce'); ?>
        </label>
    </p>

    <p class="form-row form-row-wide">
        <label for="phone"><?php _e('Номер телефона', 'woocommerce'); ?></label>
        <input type="tel" class="input-text" name="phone" id="phone"
               value="<?php echo esc_attr(get_user_meta($user->ID, 'phone', true)); ?>"/>
    </p>

    <p class="form-row form-row-wide">
        <label for="email"><?php _e('Адрес электронной почты', 'woocommerce'); ?>&nbsp;<span
                    class="required">*</span></label>
        <input type="email" class="input-text" name="email" id="email"
               value="<?php echo esc_attr($user->user_email); ?>"/>
    </p>
    <?php
}

// Сохранение кастомных полей при обновлении аккаунта
add_action('woocommerce_save_account_details', 'save_custom_account_fields', 10, 1);
function save_custom_account_fields($user_id)
{
    if (isset($_POST['middle_name'])) {
        update_user_meta($user_id, 'middle_name', sanitize_text_field($_POST['middle_name']));
    }
    if (isset($_POST['birth_date'])) {
        update_user_meta($user_id, 'birth_date', sanitize_text_field($_POST['birth_date']));
    }
    if (isset($_POST['gender'])) {
        update_user_meta($user_id, 'gender', sanitize_text_field($_POST['gender']));
    }
    if (isset($_POST['phone'])) {
        update_user_meta($user_id, 'phone', sanitize_text_field($_POST['phone']));
    }
    if (isset($_POST['email'])) {
        wp_update_user(array(
            'ID' => $user_id,
            'user_email' => sanitize_email($_POST['email']),
        ));
    }
}
