<?php

declare(strict_types=1);

namespace JobsBulletin\Domain\Model\Requirements;

class NoRequirements implements Requirements
{
    /**
     * {@inheritdoc}
     */
    public function areMet(array $abilities): bool
    {
        return true;
    }
}
