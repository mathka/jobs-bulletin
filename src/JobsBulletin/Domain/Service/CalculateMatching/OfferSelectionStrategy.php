<?php

declare(strict_types=1);

namespace JobsBulletin\Domain\Service\CalculateMatching;

use JobsBulletin\Domain\Model\Requirements\Requirements;

interface OfferSelectionStrategy
{
    public function isSelected(Requirements $requirements, array $abilities): bool;
}
