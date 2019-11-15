<?php

declare(strict_types=1);

namespace JobsBulletin\Domain\Service;

class OfferRequirementsMatcher implements RequirementsMatcher
{
    public function isMatched(array $abilities, array $requirements): bool
    {
        if (!$requirements) {
            return true;
        }

        var_dump(
            \array_keys($requirements),
            in_array(['or'], \array_keys($requirements))
        );
        if (count($requirements) == 1 && !in_array(['or'], \array_keys($requirements))) {
            return in_array($requirements[0], $abilities);
        }
        if (\array_keys($requirements, 'or')) {
            $orRequirements = $requirements['or'];
            foreach ($orRequirements as $requirement) {
                if (in_array($requirement, $abilities)) {
                    return true;
                }
            }
        }
        return false;
    }
}
