<?php

declare(strict_types=1);

namespace Tests\JobsBulletin\Domain\Service;

use JobsBulletin\Domain\Model\Offer;
use JobsBulletin\Domain\Model\Requirements\ConjunctionRequirements;
use JobsBulletin\Domain\Model\Requirements\DisjunctionRequirements;
use JobsBulletin\Domain\Model\Requirements\NoRequirements;
use JobsBulletin\Domain\Model\Requirements\SimpleRequirement;
use JobsBulletin\Domain\Repository\OfferRepository;

trait FindOffers
{
    private function createOfferRepository(int $offersLimit): OfferRepository
    {
        $offerRepository = $this->createMock(OfferRepository::class);
        $offerRepository->expects($this->at(0))
            ->method('getOffers')
            ->with($offersLimit, 0)
            ->willReturn($this->getSampleOffersRequirements());
        $offerRepository->expects($this->at(1))
            ->method('getOffers')
            ->with($offersLimit, $offersLimit)
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
                    new SimpleRequirement('a driving license'),
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
                    new SimpleRequirement('a driving license'),
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
                    new SimpleRequirement('a bike'),
                    new ConjunctionRequirements([
                        new SimpleRequirement('a motorcycle'),
                        new SimpleRequirement('a driving license'),
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
