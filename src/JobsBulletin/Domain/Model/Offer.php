<?php

declare(strict_types=1);

namespace JobsBulletin\Domain\Model;

use JobsBulletin\Domain\Model\Requirements\Requirements;

class Offer
{
    /**
     * @var string
     */
    private $companyName;

    /**
     * @var Requirements
     */
    private $requirements;

    public function __construct(string $companyName, Requirements $requirements)
    {
        $this->companyName = $companyName;
        $this->requirements = $requirements;
    }

    public function getRequirements(): Requirements
    {
        return $this->requirements;
    }

    public function getCompanyName(): string
    {
        return $this->companyName;
    }
}
