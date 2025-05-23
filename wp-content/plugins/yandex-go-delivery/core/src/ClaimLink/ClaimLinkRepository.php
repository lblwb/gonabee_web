<?php

namespace YandexTaxi\Delivery\ClaimLink;

defined('YGO_CALLED_FROM_PLUGIN') || exit;

/**
 * Class ClaimLinkRepository
 *
 * @package YandexTaxi\Delivery\ClaimLink
 */
interface ClaimLinkRepository
{
    public function get(string $id): ?ClaimLink;

    public function store(ClaimLink $link): void;

    public function delete(string $id): void;
}
