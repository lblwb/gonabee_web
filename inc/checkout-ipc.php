<?php

// functions.php

function get_pickup_location_address($ship_method): string
{
    try {
        if (!is_object($ship_method)) {
            throw new Exception(__('Метод доставки недоступен.', 'your-textdomain'));
        }

        $reflection = new ReflectionClass($ship_method);

        if (!$reflection->hasProperty('pickup_locations')) {
            throw new Exception(__('Поле pickup_locations не найдено.', 'your-textdomain'));
        }

        $property = $reflection->getProperty('pickup_locations');
        $property->setAccessible(true);

        $pickup_locations = $property->getValue($ship_method);

        if (!is_array($pickup_locations) || empty($pickup_locations)) {
            throw new Exception(__('Нет доступных адресов самовывоза.', 'your-textdomain'));
        }

        $location = $pickup_locations[0]; // Первая точка
        $address = isset($location['address']) ? $location['address'] : [];

        $address_1 = trim($address['address_1'] ?? '');
        $details = trim($location['details'] ?? '');

        if (empty($address_1)) {
            throw new Exception(__('Адрес не указан.', 'your-textdomain'));
        }

        // Приведение адреса к аккуратному виду
        $address_1 = preg_replace('/\s+/', ' ', $address_1);

        if (preg_match('/^(.*?),\s*(офис\s*\d+\.?)/iu', $address_1, $matches)) {
            $street = trim($matches[1]);
            $office = trim($matches[2]);
            $full = sprintf(
                __('По адресу: %1$s, %2$s %3$s', 'your-textdomain'),
                $street,
                $office,
                $details
            );
        } else {
            $full = sprintf(
                __('По адресу: %1$s %2$s', 'your-textdomain'),
                $address_1,
                $details
            );
        }

        $html = '<div class="pickup-location">';
        $html .= esc_html($full);
        $html .= '<div style="margin-top: 6px; color: #1F1F1F;">' . esc_html__('Сегодня до 20:00, бесплатно', 'your-textdomain') . '</div>';
        $html .= '</div>';

        return wp_kses_post($html);

    } catch (Exception $e) {
        return ""; // Или логируй ошибку для отладки
    }
}
