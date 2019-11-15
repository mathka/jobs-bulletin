<?php

declare(strict_types=1);

namespace tests\JobsBulletin\Domain\Service;

use JobsBulletin\Domain\Service\CalculateMatchingOfferService;
use PHPUnit\Framework\TestCase;

/**
 * @group jobs-bulletin
 */
class CalculateMatchingOfferServiceTest extends TestCase
{
    private const ABILITY_BIKE = 'a bike';
    private const ABILITY_DRIVING_LICENSE = 'a driving license';

    /**
     * @var CalculateMatchingOfferService
     */
    private $service;

    public function setUp()
    {
        $this->service = new CalculateMatchingOfferService();
    }

    public function testCalculateMatchingOffers()
    {
        $ability = [
            self::ABILITY_BIKE,
            self::ABILITY_DRIVING_LICENSE,
        ];

        $result = $this->service->calculate(
            $this->getSampleCompaniesRequirements(),
            $ability
        );

        $this->assertEquals(['Company F', 'Company J'], $result);
    }

    private function getSampleCompaniesRequirements(): array
    {
        return [
            'Company A' => [
                'and' => [
                    [
                        'or' => [
                            'an apartment',
                            'house',
                        ],
                    ],
                    'property insurance',
                ],
            ],
            'Company B' => [
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
            ],
            'Company C' => [
                'and' => [
                    'a social security number',
                    'a work permit',
                ],
            ],
            'Company D' => [
                'or' => [
                    'an apartment',
                    'a flat',
                    'a house',
                ],
            ],
            'Company E' => [
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
            ],
            'Company F' => [
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
            ],
            'Company G' => [
                'and' => [
                    'a massage qualification certificate',
                    'a liability insurance',
                ],
            ],
            'Company H' => [
                'or' => [
                    'a storage place',
                    'a garage',
                ],
            ],
            'Company J' => [],
            'Company K' => [
                'a PayPal account',
            ],
        ];
    }
}
