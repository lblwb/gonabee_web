<?php

namespace YandexTaxi\Delivery\Entities\Journal;

defined('YGO_CALLED_FROM_PLUGIN') || exit;

use DateTime;
use YandexTaxi\Delivery\Entities\Claim\Status;

/**
 * Class Event
 *
 * @package YandexTaxi\Delivery\Dto\Journal
 */
class Event
{
    private const STATUS_CHANGED = 'status_changed';
    private const PRICE_CHANGED = 'price_changed';

    /** @var string */
    private $claimId;

    /** @var string */
    private $changeType;

    /** @var Status|null */
    private $newStatus;

    /** @var float|null */
    private $newPrice;

    /** @var DateTime */
    private $at;

    public function __construct(string $claimId, string $changeType, DateTime $at, ?Status $newStatus, ?float $newPrice)
    {
        $this->claimId = $claimId;
        $this->changeType = $changeType;
        $this->newStatus = $newStatus;
        $this->newPrice = $newPrice;
        $this->at = $at;
    }

    public function getClaimId(): string
    {
        return $this->claimId;
    }

    public function statusWasChanged(): string
    {
        return $this->changeType === self::STATUS_CHANGED;
    }

    public function priceWasChanged(): string
    {
        return $this->changeType === self::PRICE_CHANGED;
    }

    public function getNewStatus(): ?Status
    {
        return $this->newStatus;
    }

    public function getNewPrice(): float
    {
        return $this->newPrice;
    }

    public function getAt(): DateTime
    {
        return $this->at;
    }
}
