<?php

declare(strict_types=1);

namespace JobsBulletin\Domain\Repository;

interface OfferRepository
{
    public function getOffersRequirements(int $limit, int $offset): array;
}
