<?php

declare(strict_types=1);

namespace spec\JobsBulletin\Domain\Service;

use JobsBulletin\Domain\Model\Offer;
use JobsBulletin\Domain\Model\Requirements\Requirements;
use JobsBulletin\Domain\Repository\OfferRepository;
use PhpSpec\ObjectBehavior;

class CalculateMatchingOfferServiceSpec extends ObjectBehavior
{
    private const OFFERS_LIMIT = 1;
    private const ABILITIES = [];

    public function let(OfferRepository $offerRepository)
    {
        $this->beConstructedWith(
            $offerRepository,
            self::OFFERS_LIMIT
        );
    }

    public function it_returns_matched_jobs_offers(
        OfferRepository $offerRepository,
        Requirements $requirements
    ) {
        //Given
        $offer = $this->createOffer('Company A', $requirements);

        $offerRepository->getOffers(1, 0)->willReturn([$offer]);
        $offerRepository->getOffers(1, 1)->willReturn([]);

        $requirements->areMet(self::ABILITIES)->willReturn(true);

        //When
        $result = $this->calculate(self::ABILITIES);

        //Then
        $result->shouldBe([$offer]);
    }

    public function it_does_not_return_jobs_offers_that_do_not_match(
        OfferRepository $offerRepository,
        Requirements $requirementsA,
        Requirements $requirementsB,
        Requirements $requirementsC
    ) {
        //Given
        $offerA = $this->createOffer('Company A', $requirementsA);
        $offerB = $this->createOffer('Company B', $requirementsB);
        $offerC = $this->createOffer('Company C', $requirementsC);

        $offerRepository->getOffers(self::OFFERS_LIMIT, 0)->willReturn([$offerA]);
        $offerRepository->getOffers(self::OFFERS_LIMIT, 1)->willReturn([$offerB]);
        $offerRepository->getOffers(self::OFFERS_LIMIT, 2)->willReturn([$offerC]);
        $offerRepository->getOffers(self::OFFERS_LIMIT, 3)->willReturn([]);

        $requirementsA->areMet(self::ABILITIES)->willReturn(true);
        $requirementsB->areMet(self::ABILITIES)->willReturn(false);
        $requirementsC->areMet(self::ABILITIES)->willReturn(true);

        //When
        $result = $this->calculate(self::ABILITIES);

        //Then
        $result->shouldBe([$offerA, $offerC]);
    }

    private function createOffer(string $companyName, Requirements $requirements): Offer
    {
        return new Offer($companyName, $requirements->getWrappedObject());
    }
}
