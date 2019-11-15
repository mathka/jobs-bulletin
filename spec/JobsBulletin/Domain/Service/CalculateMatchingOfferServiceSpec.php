<?php

declare(strict_types=1);

namespace spec\JobsBulletin\Domain\Service;

use JobsBulletin\Domain\Model\Offer;
use JobsBulletin\Domain\Repository\OfferRepository;
use JobsBulletin\Domain\Service\RequirementsMatcher;
use PhpSpec\ObjectBehavior;

class CalculateMatchingOfferServiceSpec extends ObjectBehavior
{
    private const OFFERS_LIMIT = 1;
    private const ABILITIES = [];

    public function let(
        OfferRepository $offerRepository,
        RequirementsMatcher $requirementsMatcher
    ) {
        $this->beConstructedWith(
            $offerRepository,
            $requirementsMatcher,
            self::OFFERS_LIMIT
        );
    }

    public function it_returns_matched_jobs_offers(
        OfferRepository $offerRepository,
        RequirementsMatcher $requirementsMatcher
    ) {
        //Given
        $requirements = ['some requirements'];
        $expectedOffers = [
            new Offer('Offer A', $requirements),
        ];

        $offerRepository->getOffersRequirements(1, 0)->willReturn($expectedOffers);
        $offerRepository->getOffersRequirements(1, 1)->willReturn([]);

        $requirementsMatcher->isMatched(self::ABILITIES, $requirements)->willReturn(true);

        //When
        $result = $this->calculate(self::ABILITIES);

        //Then
        $result->shouldBe($expectedOffers);
    }

    public function it_does_not_return_jobs_offers_that_do_not_match(
        OfferRepository $offerRepository,
        RequirementsMatcher $requirementsMatcher
    ) {
        //Given
        $requirementsA = ['requirements A'];
        $requirementsB = ['requirements B'];
        $requirementsC = ['requirements C'];

        $offerRepository->getOffersRequirements(self::OFFERS_LIMIT, 0)->willReturn([
            new Offer('Offer A', $requirementsA),
        ]);
        $offerRepository->getOffersRequirements(self::OFFERS_LIMIT, 1)->willReturn([
            new Offer('Offer B', $requirementsB),
        ]);
        $offerRepository->getOffersRequirements(self::OFFERS_LIMIT, 2)->willReturn([
            new Offer('Offer C', $requirementsC),
        ]);
        $offerRepository->getOffersRequirements(self::OFFERS_LIMIT, 3)->willReturn([]);

        $requirementsMatcher->isMatched(self::ABILITIES, $requirementsA)->willReturn(true);
        $requirementsMatcher->isMatched(self::ABILITIES, $requirementsB)->willReturn(false);
        $requirementsMatcher->isMatched(self::ABILITIES, $requirementsC)->willReturn(true);

        //When
        $result = $this->calculate(self::ABILITIES);

        //Then
        $result->shouldBeLike([
            new Offer('Offer A', $requirementsA),
            new Offer('Offer C', $requirementsC),
        ]);
    }
}
