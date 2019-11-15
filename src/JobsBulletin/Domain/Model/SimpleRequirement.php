<?php

declare(strict_types=1);

namespace JobsBulletin\Domain\Model;

class SimpleRequirement implements Requirement
{
    /**
     * @var string
     */
    private $condition;

    public function __construct(string $condition)
    {
        $this->condition = $condition;
    }

    /**
     * @inheritDoc
     */
    public function isMatched(array $abilities): bool
    {
        return in_array($this->condition, $abilities);
    }
}
