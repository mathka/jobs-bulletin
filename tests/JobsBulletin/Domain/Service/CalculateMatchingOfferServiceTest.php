<?php

declare(strict_types=1);

namespace tests\JobsBulletin\Domain\Service;

use JobsBulletin\Domain\Model\Offer;
use JobsBulletin\Domain\Repository\OfferRepository;
use JobsBulletin\Domain\Service\CalculateMatchingOfferService;
use JobsBulletin\Domain\Service\RequirementsMatcher;
use PHPUnit\Framework\TestCase;

/**
 * @group jobs-bulletin
 */
class CalculateMatchingOfferServiceTest extends TestCase
{
    private const OFFERS_LIMIT = 10;
    private const ABILITY_BIKE = 'a bike';
    private const ABILITY_DRIVING_LICENSE = 'a driving license';

    /**
     * @var CalculateMatchingOfferService
     */
    private $service;

    /**
     * @var OfferRepository
     */
    private $offerRepository;

    /**
     * @var RequirementsMatcher
     */
    private $requirementsMatcher;

    public function setUp()
    {
        $this->offerRepository = $this->createOfferRepository();
        $this->requirementsMatcher = $this->createMock(RequirementsMatcher::class);
        $this->service = new CalculateMatchingOfferService(
            $this->offerRepository,
            $this->requirementsMatcher,
            self::OFFERS_LIMIT);
    }

    public function testCalculateMatchingOffers()
    {
        //Given
        $ability = [
            self::ABILITY_BIKE,
            self::ABILITY_DRIVING_LICENSE,
        ];

        $this->requirementsMatcher->method('isMatched')->willReturn(true);

        //When
        $result = $this->service->calculate($ability);

        //Then
        $this->assertCount(2, $result);
        $this->assertEquals('Offer F', $result[0]->getCompanyName());
        $this->assertEquals('Offer J', $result[1]->getCompanyName());
    }

    private function createOfferRepository(): OfferRepository
    {
        $clientRepository = $this->createMock(OfferRepository::class);
        $clientRepository->expects($this->at(0))
            ->method('getOffersRequirements')
            ->with(self::OFFERS_LIMIT, 0)
            ->willReturn($this->getSampleCompaniesRequirements());
        $clientRepository->expects($this->at(1))
            ->method('getOffersRequirements')
            ->with(self::OFFERS_LIMIT, self::OFFERS_LIMIT)
            ->willReturn([]);

        return $clientRepository;
    }

    private function getSampleCompaniesRequirements(): array
    {
        return [
            new Offer('Company A', [
                'and' => [
                    [
                        'or' => [
                            'an apartment',
                            'house',
                        ],
                    ],
                    'property insurance',
                ],
            ]),
            new Offer('Company B', [
                'and' => [
                    [
                        'or' => [
                            '5 door car',
                            '4 door car',
                        ],
                    ],
                    self::ABILITY_DRIVING_LICENSE,
                    'car insurance',
                ],
            ]),
            new Offer('Company C', [
                'and' => [
                    'a social security number',
                    'a work permit',
                ],
            ]),
            new Offer('Company D', [
                'or' => [
                    'an apartment',
                    'a flat',
                    'a house',
                ],
            ]),
            new Offer('Company E', [
                'and' => [
                    self::ABILITY_DRIVING_LICENSE,
                    [
                        'or' => [
                            '2 door car',
                            '3 door car',
                            '4 door car',
                            '5 door car',
                        ],
                    ],
                ],
            ]),
            new Offer('Company F', [
                'or' => [
                    'a scooter',
                    self::ABILITY_BIKE,
                    [
                        'and' => [
                            'a motorcycle',
                            self::ABILITY_DRIVING_LICENSE,
                            'motorcycle insurance',
                        ],
                    ],
                ],
            ]),
            new Offer('Company G', [
                'and' => [
                    'a massage qualification certificate',
                    'a liability insurance',
                ],
            ]),
            new Offer('Company H', [
                'or' => [
                    'a storage place',
                    'a garage',
                ],
            ]),
            new Offer('Company J', []),
            new Offer('Company K', [
                'a PayPal account',
            ]),
        ];
    }
}
