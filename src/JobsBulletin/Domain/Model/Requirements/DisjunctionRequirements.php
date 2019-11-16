<?php

declare(strict_types=1);

namespace JobsBulletin\Domain\Model\Requirements;

class DisjunctionRequirements implements Requirements
{
    /**
     * @var Requirements[]
     */
    private $requirements;

    public function __construct(array $requirements)
    {
        $this->requirements = $requirements;
    }

    /**
     * {@inheritdoc}
     */
    public function areMet(array $abilities): bool
    {
        foreach ($this->requirements as $requirement) {
            if ($requirement->areMet($abilities)) {
                return true;
            }
        }

        return false;
    }
}
