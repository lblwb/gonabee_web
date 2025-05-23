<?php

defined('ABSPATH') || exit;

class WC_Yandex_Taxi_Delivery_Admin_Error
{
    /** @var string */
    private $_message;

    /**
     * WC_Yandex_Taxi_Delivery_Admin_Error constructor.
     *
     * @param string $message
     */
    function __construct(string $message)
    {
        $this->_message = $message;

        add_action('admin_notices', [$this, 'render']);
    }

    function render()
    {
        printf('<div class="error">%s</div>', $this->_message);
    }
}
