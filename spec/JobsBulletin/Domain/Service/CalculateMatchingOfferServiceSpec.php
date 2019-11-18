<?php

declare(strict_types=1);

namespace spec\JobsBulletin\Domain\Service;

use JobsBulletin\Domain\Model\Offer;
use JobsBulletin\Domain\Model\Requirements\Requirements;
use JobsBulletin\Domain\Repository\OfferRepository;
use JobsBulletin\Domain\Service\CalculateMatching\OfferSelectionStrategy;
use PhpSpec\ObjectBehavior;

class CalculateMatchingOfferServiceSpec extends ObjectBehavior
{
    private const OFFERS_LIMIT = 2;

    public function let(
        OfferRepository $offerRepository,
        OfferSelectionStrategy $selectionStrategy
    ) {
        $this->beConstructedWith(
            $offerRepository,
            $selectionStrategy,
            self::OFFERS_LIMIT
        );
    }

    public function it_returns_matched_offers(
        OfferRepository $offerRepository,
        OfferSelectionStrategy $selectionStrategy,
        Requirements $requirementsA,
        Requirements $requirementsB,
        Requirements $requirementsC
    ) {
        //Given
        $abilities = ['some abilities'];

        $offerA = $this->createOffer('Company A', $requirementsA);
        $offerB = $this->createOffer('Company B', $requirementsB);
        $offerC = $this->createOffer('Company C', $requirementsC);

        $offerRepository->getOffers(self::OFFERS_LIMIT, 0)->willReturn([$offerA, $offerB]);
        $offerRepository->getOffers(self::OFFERS_LIMIT, 2)->willReturn([$offerC]);
        $offerRepository->getOffers(self::OFFERS_LIMIT, 4)->willReturn([]);

        $selectionStrategy->isSelected($requirementsA, $abilities)->willReturn(true);
        $selectionStrategy->isSelected($requirementsB, $abilities)->willReturn(false);
        $selectionStrategy->isSelected($requirementsC, $abilities)->willReturn(true);

        //When
        $result = $this->calculate($abilities);

        //Then
        $result->shouldBe([$offerA, $offerC]);
    }

    private function createOffer(string $companyName, Requirements $requirements): Offer
    {
        return new Offer($companyName, $requirements->getWrappedObject());
    }
}
