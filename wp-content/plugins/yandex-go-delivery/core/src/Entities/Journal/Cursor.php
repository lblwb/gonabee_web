<?php

namespace YandexTaxi\Delivery\Entities\Journal;

defined('YGO_CALLED_FROM_PLUGIN') || exit;

/**
 * Class Cursor
 *
 * @package YandexTaxi\Delivery\Dto\Journal
 */
class Cursor
{
    /** @var string */
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
