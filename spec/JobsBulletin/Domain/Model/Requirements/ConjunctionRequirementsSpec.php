<?php

namespace spec\JobsBulletin\Domain\Model\Requirements;

use JobsBulletin\Domain\Model\Requirements\Requirements;
use PhpSpec\ObjectBehavior;

class ConjunctionRequirementsSpec extends ObjectBehavior
{
    private const ABILITIES = ['some abilities'];

    public function it_returns_true_when_all_requirements_are_met(
        Requirements $firstRequirement,
        Requirements $secondRequirement,
        Requirements $thirdRequirement
    ) {
        //Given
        $this->beConstructedWith([
            $firstRequirement,
            $secondRequirement,
            $thirdRequirement,
        ]);

        $firstRequirement->areMet(self::ABILITIES)->willReturn(true);
        $secondRequirement->areMet(self::ABILITIES)->willReturn(true);
        $thirdRequirement->areMet(self::ABILITIES)->willReturn(true);

        //When
        $this->areMet(self::ABILITIES)
        //Then
            ->shouldReturn(true);
    }

    public function it_returns_false_when_any_requirements_are_not_met(
        Requirements $firstRequirement,
        Requirements $secondRequirement,
        Requirements $thirdRequirement
    ) {
        //Given
        $this->beConstructedWith([
            $firstRequirement,
            $secondRequirement,
            $thirdRequirement,
        ]);

        $firstRequirement->areMet(self::ABILITIES)->willReturn(true);
        $secondRequirement->areMet(self::ABILITIES)->willReturn(false);
        $thirdRequirement->areMet(self::ABILITIES)->willReturn(true);

        //When
        $this->areMet(self::ABILITIES)
        //Then
            ->shouldReturn(false);
    }
}
