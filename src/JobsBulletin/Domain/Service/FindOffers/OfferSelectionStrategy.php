<?php

declare(strict_types=1);

namespace JobsBulletin\Domain\Service\FindOffers;

use JobsBulletin\Domain\Model\Offer;

interface OfferSelectionStrategy
{
    public function isSelected(Offer $offer, array $abilities): bool;
}
