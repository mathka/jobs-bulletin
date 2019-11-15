<?php

declare(strict_types=1);

namespace JobsBulletin\Domain\Model;

class Offer
{
    /**
     * @var string
     */
    private $companyName;

    /**
     * @var array
     */
    private $requirements = [];

    public function __construct(string $companyName, array $requirements)
    {
        $this->companyName = $companyName;
        $this->requirements = $requirements;
    }

    public function getRequirements(): array
    {
        return $this->requirements;
    }

    public function getCompanyName(): string
    {
        return $this->companyName;
    }
}
