<?php

declare(strict_types=1);

namespace JobsBulletin\Domain\Model;

interface Requirements
{
    /**
     * @param string[] $abilities
     * @return bool
     */
    public function areMet(array $abilities): bool;
}
