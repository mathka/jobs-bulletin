<?php

declare(strict_types=1);

namespace spec\JobsBulletin\Domain\Service;

use JobsBulletin\Domain\Model\Offer;
use JobsBulletin\Domain\Model\Requirements\Requirements;
use JobsBulletin\Domain\Repository\OfferRepository;
use PhpSpec\ObjectBehavior;

class CalculateMatchingOfferServiceSpec extends ObjectBehavior
{
    private const OFFERS_LIMIT = 2;
    private const ABILITIES = [];

    /**
     * @var Offer
     */
    private $offerA;

    /**
     * @var Offer
     */
    private $offerB;

    /**
     * @var Offer
     */
    private $offerC;

    public function let(
        OfferRepository $offerRepository,
        Requirements $requirementsA,
        Requirements $requirementsB,
        Requirements $requirementsC
    ) {
        $this->beConstructedWith(
            $offerRepository,
            self::OFFERS_LIMIT
        );

        $this->offerA = $this->createOffer('Company A', $requirementsA);
        $this->offerB = $this->createOffer('Company B', $requirementsB);
        $this->offerC = $this->createOffer('Company C', $requirementsC);

        $offerRepository->getOffers(self::OFFERS_LIMIT, 0)->willReturn([$this->offerA, $this->offerB]);
        $offerRepository->getOffers(self::OFFERS_LIMIT, 2)->willReturn([$this->offerC]);
        $offerRepository->getOffers(self::OFFERS_LIMIT, 4)->willReturn([]);

        $requirementsA->areMet(self::ABILITIES)->willReturn(true);
        $requirementsB->areMet(self::ABILITIES)->willReturn(false);
        $requirementsC->areMet(self::ABILITIES)->willReturn(true);
    }

    public function it_returns_only_matching_offers()
    {
        //Given
        $shouldMatching = true;

        //When
        $result = $this->calculate(self::ABILITIES, $shouldMatching);

        //Then
        $result->shouldBe([$this->offerA, $this->offerC]);
    }

    public function it_returns_only_not_matching_offers()
    {
        //Given
        $shouldMatching = false;

        //When
        $result = $this->calculate(self::ABILITIES, $shouldMatching);

        //Then
        $result->shouldBe([$this->offerB]);
    }

    private function createOffer(string $companyName, Requirements $requirements): Offer
    {
        return new Offer($companyName, $requirements->getWrappedObject());
    }
}
