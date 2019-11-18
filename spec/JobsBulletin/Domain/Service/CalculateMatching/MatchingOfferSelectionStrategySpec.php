<?php

namespace spec\JobsBulletin\Domain\Service\CalculateMatching;

use JobsBulletin\Domain\Model\Requirements\Requirements;
use PhpSpec\ObjectBehavior;

class MatchingOfferSelectionStrategySpec extends ObjectBehavior
{
    public function it_returns_true_when_offer_is_matching(
        Requirements $requirements
    ) {
        //Given
        $abilities = ['some abilities'];

        $requirements->areMet($abilities)->willReturn(true);

        //When
        $this->isSelected($requirements, $abilities)
        //Then
            ->shouldReturn(true);
    }

    public function it_returns_false_when_offer_is_not_matching(
        Requirements $requirements
    ) {
        //Given
        $abilities = ['some abilities'];

        $requirements->areMet($abilities)->willReturn(false);

        //When
        $this->isSelected($requirements, $abilities)
        //Then
            ->shouldReturn(false);
    }
}
