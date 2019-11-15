<?php

namespace spec\JobsBulletin\Domain\Model;

use PhpSpec\ObjectBehavior;

class SimpleRequirementSpec extends ObjectBehavior
{
    private const CONDITION = 'some condition';

    public function let()
    {
        $this->beConstructedWith(self::CONDITION);
    }

    public function it_returns_true_when_any_ability_meets_condition()
    {
        //When
        $this->areMet([self::CONDITION])
        //Then
            ->shouldReturn(true);
    }

    public function it_returns_false_when_any_ability_does_not_meet_condition()
    {
        //When
        $this->areMet(['other_ability'])
        //Then
            ->shouldReturn(false);
    }
}
