<?php

namespace WCYandexTaxiDeliveryPlugin\Wrappers;

defined('ABSPATH') || exit;

use Automattic\WooCommerce\Admin\Overrides\Order;
use libphonenumber\NumberParseException;
use WCYandexTaxiDeliveryPlugin\Helpers\CountryRelatedDataHelper;
use YandexTaxi\Delivery\PhoneNumber\Formatter as PhoneFormatter;

/**
 * Class OrderWrapper
 *
 * @package WCYandexTaxiDeliveryPlugin\Wrappers
 */
class OrderWrapper
{
    /** @var \WC_Order */
    private $entity;

    public function __construct(\WC_Order $entity)
    {
        $this->entity = $entity;
    }

    public function getId(): int
    {
        return $this->entity->get_id();
    }

    public function getFullName(): string
    {
        return $this->entity->get_formatted_shipping_full_name() ?? $this->entity->get_formatted_billing_full_name();
    }

    public function getAddress(): string
    {
        return $address = WC()->countries->get_formatted_address([
            'city' => $this->entity->get_shipping_city() ?? $this->entity->get_billing_city(),
            'address_1' => $this->entity->get_shipping_address_1() ?? $this->entity->get_billing_address_1(),
            //'address_2' => $this->entity->get_shipping_address_2() ?? $this->entity->get_billing_address_2(),
        ], ' ');
    }

    public function getFlat(): string
    {
        return $this->entity->get_shipping_address_2() ?? $this->entity->get_billing_address_2();
    }

    public function getPhone(): string
    {
        $phone = $this->entity->get_billing_phone();

        try {
            return PhoneFormatter::format($phone, CountryRelatedDataHelper::getUpperPhoneCountry());
        } catch (NumberParseException $exception) {
            return $phone;
        }
    }

    public function getEmail(): string
    {
	    return $this->entity->get_billing_email();
    }

    public function getCommentPlaceHolder(): string
    {
        $placeHolder = __('Домофон: ', 'yandex-go-delivery');

        $comment = trim($this->entity->get_customer_note());

        if (empty($comment)) {
            return $placeHolder;

        }

        $placeHolder .= PHP_EOL . __('Комментарий к заказу: ', 'yandex-go-delivery') . $comment;
        return $placeHolder;
    }

    public function getEditUrl(): string
    {
        return admin_url("post.php?post={$this->entity->get_id()}&action=edit");
    }
}
