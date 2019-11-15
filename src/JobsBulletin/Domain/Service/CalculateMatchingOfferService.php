<?php

declare(strict_types=1);

namespace JobsBulletin\Domain\Service;

use JobsBulletin\Domain\Repository\OfferRepository;

class CalculateMatchingOfferService
{
    /**
     * @var OfferRepository
     */
    private $offerRepository;

    /**
     * @var RequirementsMatcher
     */
    private $requirementsMatcher;

    /**
     * @var int
     */
    private $offerLimit;

    public function __construct(
        OfferRepository $offerRepository,
        RequirementsMatcher $requirementsMatcher,
        int $offerLimit
    ) {
        $this->offerRepository = $offerRepository;
        $this->requirementsMatcher = $requirementsMatcher;
        $this->offerLimit = $offerLimit;
    }

    public function calculate(array $abilities): array
    {
        $offers = [];

        foreach ($this->getOffersRequirements() as $offerRequirements) {
            if ($this->requirementsMatcher->isMatched($abilities, $offerRequirements->getRequirements())) {
                $offers[] = $offerRequirements;
            }
        }

        return $offers;
    }

    private function getOffersRequirements(): \Generator
    {
        $offset = 0;

        while ($offersRequirements = $this->offerRepository->getOffersRequirements($this->offerLimit, $offset)) {
            foreach ($offersRequirements as $requirement) {
                yield $requirement;
            }
            $offset += $this->offerLimit;
        }
    }
}
