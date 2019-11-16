<?php

declare(strict_types=1);

namespace JobsBulletin\Domain\Service;

use JobsBulletin\Domain\Model\Offer;
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

    public function calculate(array $abilities, bool $shouldMatching): array
    {
        $offers = [];

        foreach ($this->getOffers() as $offer) {
            if ($this->shouldReturnOffer($offer, $abilities, $shouldMatching)) {
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

    private function shouldReturnOffer(Offer $offer, array $abilities, bool $shouldMatching)
    {
        $requirements = $offer->getRequirements();
        $requirementAreMet = $requirements->areMet($abilities);

        return $requirementAreMet == $shouldMatching;
    }
}
