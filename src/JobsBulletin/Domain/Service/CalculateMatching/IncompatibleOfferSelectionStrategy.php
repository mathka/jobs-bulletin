<?php

declare(strict_types=1);

namespace JobsBulletin\Domain\Service\CalculateMatching;

use JobsBulletin\Domain\Model\Requirements\Requirements;

class IncompatibleOfferSelectionStrategy implements OfferSelectionStrategy
{
    public function isSelected(Requirements $requirements, array $abilities): bool
    {
        return !$requirements->areMet($abilities);
    }
}
