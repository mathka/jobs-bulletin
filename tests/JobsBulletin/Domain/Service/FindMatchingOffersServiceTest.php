<?php

declare(strict_types=1);

namespace Tests\JobsBulletin\Domain\Service;

use JobsBulletin\Domain\Service\FindOffers\MatchingOfferSelectionStrategy;
use JobsBulletin\Domain\Service\FindOffersService;
use PHPUnit\Framework\TestCase;

/**
 * @group jobs-bulletin
 */
class FindMatchingOffersServiceTest extends TestCase
{
    use FindOffers;

    /**
     * @var FindOffersService
     */
    private $service;

    public function setUp()
    {
        $offersLimit = 10;

        $this->service = new FindOffersService(
            $this->createOfferRepository($offersLimit),
            new MatchingOfferSelectionStrategy(),
            $offersLimit
        );
    }

    public function testFindMatchingOffers()
    {
        //Given
        $abilities = [
            'a bike',
            'a driving license',
        ];

        //When
        $result = $this->service->calculate($abilities);

        //Then
        $this->assertCount(2, $result);
        $this->assertEquals('Company F', $result[0]->getCompanyName());
        $this->assertEquals('Company J', $result[1]->getCompanyName());
    }
}
