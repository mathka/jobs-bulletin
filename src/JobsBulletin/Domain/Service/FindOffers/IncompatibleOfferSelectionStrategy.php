<?php

declare(strict_types=1);

namespace JobsBulletin\Domain\Service\FindOffers;

use JobsBulletin\Domain\Model\Offer;

class IncompatibleOfferSelectionStrategy implements OfferSelectionStrategy
{
    public function isSelected(Offer $offer, array $abilities): bool
    {
        $requirements = $offer->getRequirements();

        return !$requirements->areMet($abilities);
    }
}
