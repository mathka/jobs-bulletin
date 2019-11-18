<?php

declare(strict_types=1);

namespace tests\JobsBulletin\Domain\Service;

use JobsBulletin\Domain\Model\Offer;
use JobsBulletin\Domain\Model\Requirements\ConjunctionRequirements;
use JobsBulletin\Domain\Model\Requirements\DisjunctionRequirements;
use JobsBulletin\Domain\Model\Requirements\NoRequirements;
use JobsBulletin\Domain\Model\Requirements\SimpleRequirement;
use JobsBulletin\Domain\Repository\OfferRepository;
use JobsBulletin\Domain\Service\CalculateMatching\IncompatibleOfferSelectionStrategy;
use JobsBulletin\Domain\Service\CalculateMatching\MatchingOfferSelectionStrategy;
use JobsBulletin\Domain\Service\CalculateMatchingOfferService;
use PHPUnit\Framework\TestCase;

/**
 * @group jobs-bulletin
 */
class CalculateMatchingOfferServiceTest extends TestCase
{
    private const OFFERS_LIMIT = 10;

    private const ABILITY_BIKE = 'a bike';
    private const ABILITY_DRIVING_LICENSE = 'a driving license';

    private const ABILITY = [
        self::ABILITY_BIKE,
        self::ABILITY_DRIVING_LICENSE,
    ];

    /**
     * @var CalculateMatchingOfferService
     */
    private $service;

    /**
     * @var OfferRepository
     */
    private $offerRepository;

    public function setUp()
    {
        $this->offerRepository = $this->createOfferRepository();
    }

    public function testGetMatchingOffers()
    {
        //Given
        $service = new CalculateMatchingOfferService(
            $this->offerRepository,
            new MatchingOfferSelectionStrategy(),
            self::OFFERS_LIMIT
        );

        //When
        $result = $service->calculate(self::ABILITY);

        //Then
        $this->assertCount(2, $result);
        $this->assertEquals('Company F', $result[0]->getCompanyName());
        $this->assertEquals('Company J', $result[1]->getCompanyName());
    }

    public function testGetDoesNotMatchingOffers()
    {
        //Given
        $service = new CalculateMatchingOfferService(
            $this->offerRepository,
            new IncompatibleOfferSelectionStrategy(),
            self::OFFERS_LIMIT
        );

        //When
        $result = $service->calculate(self::ABILITY);

        //Then
        $this->assertCount(8, $result);
        $this->assertEquals('Company A', $result[0]->getCompanyName());
        $this->assertEquals('Company B', $result[1]->getCompanyName());
        $this->assertEquals('Company C', $result[2]->getCompanyName());
        $this->assertEquals('Company D', $result[3]->getCompanyName());
        $this->assertEquals('Company E', $result[4]->getCompanyName());
        $this->assertEquals('Company G', $result[5]->getCompanyName());
        $this->assertEquals('Company H', $result[6]->getCompanyName());
        $this->assertEquals('Company K', $result[7]->getCompanyName());
    }

    private function createOfferRepository(): OfferRepository
    {
        $offerRepository = $this->createMock(OfferRepository::class);
        $offerRepository->expects($this->at(0))
            ->method('getOffers')
            ->with(self::OFFERS_LIMIT, 0)
            ->willReturn($this->getSampleOffersRequirements());
        $offerRepository->expects($this->at(1))
            ->method('getOffers')
            ->with(self::OFFERS_LIMIT, self::OFFERS_LIMIT)
            ->willReturn([]);

        return $offerRepository;
    }

    private function getSampleOffersRequirements(): array
    {
        return [
            new Offer(
                'Company A',
                new ConjunctionRequirements([
                    new DisjunctionRequirements([
                        new SimpleRequirement('an apartment'),
                        new SimpleRequirement('house'),
                    ]),
                    new SimpleRequirement('house'),
                ])
            ),
            new Offer(
                'Company B',
                new ConjunctionRequirements([
                    new DisjunctionRequirements([
                        new SimpleRequirement('5 door car'),
                        new SimpleRequirement('4 door car'),
                    ]),
                    new SimpleRequirement(self::ABILITY_DRIVING_LICENSE),
                    new SimpleRequirement('car insurance'),
                ])
            ),
            new Offer(
                'Company C',
                new ConjunctionRequirements([
                    new SimpleRequirement('a social security number'),
                    new SimpleRequirement('a work permit'),
                ])
            ),
            new Offer(
                'Company D',
                new DisjunctionRequirements([
                    new SimpleRequirement('an apartment'),
                    new SimpleRequirement('a flat'),
                    new SimpleRequirement('a house'),
                ])
            ),
            new Offer(
                'Company E',
                new ConjunctionRequirements([
                    new SimpleRequirement(self::ABILITY_DRIVING_LICENSE),
                    new DisjunctionRequirements([
                        new SimpleRequirement('2 door car'),
                        new SimpleRequirement('3 door car'),
                        new SimpleRequirement('4 door car'),
                        new SimpleRequirement('5 door car'),
                    ]),
                ])
            ),
            new Offer(
                'Company F',
                new DisjunctionRequirements([
                    new SimpleRequirement('a scooter'),
                    new SimpleRequirement(self::ABILITY_BIKE),
                    new ConjunctionRequirements([
                        new SimpleRequirement('a motorcycle'),
                        new SimpleRequirement(self::ABILITY_DRIVING_LICENSE),
                        new SimpleRequirement('motorcycle insurance'),
                    ]),
                ])
            ),
            new Offer(
                'Company G',
                new ConjunctionRequirements([
                    new SimpleRequirement('a massage qualification certificate'),
                    new SimpleRequirement('a liability insurance'),
                ])
            ),
            new Offer(
                'Company H',
                new DisjunctionRequirements([
                    new SimpleRequirement('a storage place'),
                    new SimpleRequirement('a garage'),
                ])
            ),
            new Offer(
                'Company J',
                new NoRequirements()
            ),
            new Offer(
                'Company K',
                new SimpleRequirement('a PayPal account')
            ),
        ];
    }
}
