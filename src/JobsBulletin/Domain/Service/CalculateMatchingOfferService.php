<?php

declare(strict_types=1);

namespace JobsBulletin\Domain\Service;

use JobsBulletin\Domain\Repository\OfferRepository;
use JobsBulletin\Domain\Service\CalculateMatching\OfferSelectionStrategy;

class CalculateMatchingOfferService
{
    /**
     * @var OfferRepository
     */
    private $offerRepository;

    /**
     * @var OfferSelectionStrategy
     */
    private $selectionStrategy;

    /**
     * @var int
     */
    private $offerLimit;

    public function __construct(
        OfferRepository $offerRepository,
        OfferSelectionStrategy $selectionStrategy,
        int $offerLimit
    ) {
        $this->offerRepository = $offerRepository;
        $this->selectionStrategy = $selectionStrategy;
        $this->offerLimit = $offerLimit;
    }

    public function calculate(array $abilities): array
    {
        $offers = [];

        foreach ($this->getOffers() as $offer) {
            $requirements = $offer->getRequirements();

            if ($this->selectionStrategy->isSelected($requirements, $abilities)) {
                $offers[] = $offer;
            }
        }

        return $offers;
    }

    /**
     * @yield Offer
     *
     * @return \Generator
     */
    private function getOffers(): \Generator
    {
        $offset = 0;

        while ($offers = $this->offerRepository->getOffers($this->offerLimit, $offset)) {
            foreach ($offers as $offer) {
                yield $offer;
            }
            $offset += $this->offerLimit;
        }
    }
}
