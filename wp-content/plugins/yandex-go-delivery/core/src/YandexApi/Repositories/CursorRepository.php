<?php

namespace YandexTaxi\Delivery\YandexApi\Repositories;

defined('YGO_CALLED_FROM_PLUGIN') || exit;

use YandexTaxi\Delivery\Entities\Journal\Cursor;

/**
 * Interface CursorRepository
 *
 * @package YandexTaxi\Delivery\YandexApi\Repositories
 */
interface CursorRepository
{
    public function getLatest(): ?Cursor;

    public function deleteOlderThanYesterday(): void;

    public function delete(Cursor $cursor): void;

    public function store(Cursor $cursor): void;
}
