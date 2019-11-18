<?php

declare(strict_types=1);

namespace spec\JobsBulletin\Domain\Service;

use JobsBulletin\Domain\Model\Offer;
use JobsBulletin\Domain\Model\Requirements\Requirements;
use JobsBulletin\Domain\Repository\OfferRepository;
use JobsBulletin\Domain\Service\FindOffers\OfferSelectionStrategy;
use PhpSpec\ObjectBehavior;

class FindOffersServiceSpec extends ObjectBehavior
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

    public function it_does_not_return_offers_when_no_offer_has_been_found(
        OfferRepository $offerRepository
    ) {
        //Given
        $abilities = ['some abilities'];

        $offerRepository->getOffers(self::OFFERS_LIMIT, 0)->willReturn([]);

        //When
        $result = $this->calculate($abilities);

        //Then
        $result->shouldBe([]);
    }

    public function it_does_not_return_offers_when_any_offer_does_not_meet_given_conditions(
        OfferRepository $offerRepository,
        OfferSelectionStrategy $selectionStrategy,
        Requirements $requirementsA,
        Requirements $requirementsB
    ) {
        //Given
        $abilities = ['some abilities'];

        $offerA = $this->createOffer('Company A', $requirementsA);
        $offerB = $this->createOffer('Company B', $requirementsB);

        $offerRepository->getOffers(self::OFFERS_LIMIT, 0)->willReturn([$offerA, $offerB]);
        $offerRepository->getOffers(self::OFFERS_LIMIT, 2)->willReturn([]);

        $selectionStrategy->isSelected($offerA, $abilities)->willReturn(false);
        $selectionStrategy->isSelected($offerB, $abilities)->willReturn(false);

        //When
        $result = $this->calculate($abilities);

        //Then
        $result->shouldBe([]);
    }

    public function it_returns_offers_which_meet_given_conditions(
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

        $selectionStrategy->isSelected($offerA, $abilities)->willReturn(true);
        $selectionStrategy->isSelected($offerB, $abilities)->willReturn(false);
        $selectionStrategy->isSelected($offerC, $abilities)->willReturn(true);

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
