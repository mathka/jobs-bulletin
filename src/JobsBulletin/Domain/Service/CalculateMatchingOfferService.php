<?php

declare(strict_types=1);

namespace JobsBulletin\Domain\Service;

use JobsBulletin\Domain\Repository\OfferRepository;

class CalculateMatchingOfferService
{
    /**
     * @var OfferRepository
     */
    private $offerRepository;

    /**
     * @var int
     */
    private $offerLimit;

    public function __construct(OfferRepository $offerRepository, int $offerLimit)
    {
        $this->offerRepository = $offerRepository;
        $this->offerLimit = $offerLimit;
    }

    public function calculate(array $abilities): array
    {
        $offers = [];

        foreach ($this->getOffers() as $offer) {
            $requirements = $offer->getRequirements();
            if ($requirements->areMet($abilities)) {
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
