<?php

declare(strict_types=1);

namespace JobsBulletin\Domain\Service;

interface RequirementsMatcher
{
    public function isMatched(array $abilities, array $requirements): bool;
}
