<?php

declare(strict_types=1);

namespace JobsBulletin\Domain\Model;

interface Requirement
{
    /**
     * @param string[] $capabilities
     * @return bool
     */
    public function isMatched(array $capabilities): bool;
}
