<?php

namespace spec\JobsBulletin\Domain\Service;

use JobsBulletin\Domain\Model\Requirements;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class OfferRequirementsMatcherSpec extends ObjectBehavior
{
    private const ABILITY_BIKE = 'a bike';
    private const ABILITY_DRIVING_LICENSE = 'a driving license';


    public function it_new_function_returns_true_when_requirements_are_met(
        Requirements $requirements
    )
    {
        //Given
        $abilities = [];
        $requirements->areMet($abilities)->willReturn(true);

        //When
        $this->isNewMatched($requirements, $abilities)
        //Then
            ->shouldReturn(true);
    }

    public function it_returns_true_when_any_requirement_is_not_given()
    {
        //Given
        $abilities = [
            self::ABILITY_BIKE,
            self::ABILITY_DRIVING_LICENSE,
        ];
        $requirements = [];

        //When
        $this->isMatched($abilities, $requirements)
        //Then
            ->shouldReturn(true);
    }

    public function it_returns_true_when_any_ability_meets_single_requirement()
    {
        //Given
        $abilities = [
            self::ABILITY_BIKE,
            self::ABILITY_DRIVING_LICENSE,
        ];
        $requirements = [
            self::ABILITY_BIKE,
        ];

        //When
        $this->isMatched($abilities, $requirements)
            //Then
            ->shouldReturn(true);
    }

    public function it_return_false_when_any_ability_does_not_meet_single_requirement()
    {
        //Given
        $abilities = [
            self::ABILITY_BIKE,
            self::ABILITY_DRIVING_LICENSE,
        ];
        $requirements = [
            'other_requirement',
        ];

        //When
        $this->isMatched($abilities, $requirements)
        //Then
            ->shouldReturn(false);
    }

    public function it_returns_true_when_at_least_one_condition_of_multiple_interchangeable_requirements_is_met()
    {
        //Given
        $abilities = [
            self::ABILITY_BIKE,
            self::ABILITY_DRIVING_LICENSE,
        ];
        $requirements = [
            'or' => [
                'other_requirement',
                self::ABILITY_BIKE,
            ],
        ];

        //When
        $this->isMatched($abilities, $requirements)
        //Then
            ->shouldReturn(true);
    }

    public function it_informs_that_abilities_meet_requirements()
    {
        //Given
        $abilities = [
            self::ABILITY_BIKE,
            self::ABILITY_DRIVING_LICENSE,
        ];
        $requirements = [
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
            ]
        ];

        //When
        $this->isMatched($abilities, $requirements)
        //Then
            ->shouldReturn(true);
    }
}
