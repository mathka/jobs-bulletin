<?php

declare(strict_types=1);

namespace JobsBulletin\Domain\Model\Requirements;

class SimpleRequirement implements Requirements
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
     * {@inheritdoc}
     */
    public function areMet(array $abilities): bool
    {
        return \in_array($this->condition, $abilities);
    }
}
