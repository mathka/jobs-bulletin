<?php

namespace spec\JobsBulletin\Domain\Model\Requirements;

use PhpSpec\ObjectBehavior;

class NoRequirementsSpec extends ObjectBehavior
{
    public function it_always_returns_true_because_there_are_no_conditions()
    {
        //When
        $this->areMet([])
        //Then
            ->shouldReturn(true);
    }
}
