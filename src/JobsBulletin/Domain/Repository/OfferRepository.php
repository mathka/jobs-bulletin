<?php

declare(strict_types=1);

namespace JobsBulletin\Domain\Repository;

interface OfferRepository
{
    public function getOffers(int $limit, int $offset): array;
}
