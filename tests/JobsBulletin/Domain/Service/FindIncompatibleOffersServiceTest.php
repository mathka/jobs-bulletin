<?php

declare(strict_types=1);

namespace Tests\JobsBulletin\Domain\Service;

use JobsBulletin\Domain\Service\FindOffers\IncompatibleOfferSelectionStrategy;
use JobsBulletin\Domain\Service\FindOffersService;
use PHPUnit\Framework\TestCase;

/**
 * @group jobs-bulletin
 */
class FindIncompatibleOffersServiceTest extends TestCase
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
            new IncompatibleOfferSelectionStrategy(),
            $offersLimit
        );
    }

    public function testFindIncompatibleOffers()
    {
        //Given
        $abilities = [
            'a bike',
            'a driving license',
        ];

        //When
        $result = $this->service->calculate($abilities);

        //Then
        $this->assertCount(8, $result);
        $this->assertEquals('Company A', $result[0]->getCompanyName());
        $this->assertEquals('Company B', $result[1]->getCompanyName());
        $this->assertEquals('Company C', $result[2]->getCompanyName());
        $this->assertEquals('Company D', $result[3]->getCompanyName());
        $this->assertEquals('Company E', $result[4]->getCompanyName());
        $this->assertEquals('Company G', $result[5]->getCompanyName());
        $this->assertEquals('Company H', $result[6]->getCompanyName());
        $this->assertEquals('Company K', $result[7]->getCompanyName());
    }
}
