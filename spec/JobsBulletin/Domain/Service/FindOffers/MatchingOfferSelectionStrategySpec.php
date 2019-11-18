<?php

namespace spec\JobsBulletin\Domain\Service\FindOffers;

use JobsBulletin\Domain\Model\Offer;
use JobsBulletin\Domain\Model\Requirements\Requirements;
use PhpSpec\ObjectBehavior;

class MatchingOfferSelectionStrategySpec extends ObjectBehavior
{
    public function it_returns_true_when_offer_is_matching(
        Offer $offer,
        Requirements $requirements
    ) {
        //Given
        $abilities = ['some abilities'];

        $offer->getRequirements()->willReturn($requirements);
        $requirements->areMet($abilities)->willReturn(true);

        //When
        $this->isSelected($offer, $abilities)
        //Then
            ->shouldReturn(true);
    }

    public function it_returns_false_when_offer_is_not_matching(
        Offer $offer,
        Requirements $requirements
    ) {
        //Given
        $abilities = ['some abilities'];

        $offer->getRequirements()->willReturn($requirements);
        $requirements->areMet($abilities)->willReturn(false);

        //When
        $this->isSelected($offer, $abilities)
        //Then
            ->shouldReturn(false);
    }
}
