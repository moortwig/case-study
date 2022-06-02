<?php

namespace App\Api\Studies\Requirements;

use Carbon\Carbon;

class StudyRequirementValidator
{
    /**
     * Presumes age in years in this version
     *
     * @throws AgeRequirementNotMetException
     */
    public function validateAge(Carbon $date, array $requirement): void
    {
        if (!StudyRequirement::ruleExists($requirement['rule'])) {
            return;
        }

        switch ($requirement['rule']) {
            case StudyRequirement::RULE_MINIMUM:
                $eighteenYearsAgo = Carbon::now()->subYears(18);
                if ($eighteenYearsAgo < $date) {
                    throw new AgeRequirementNotMetException('The requirement for age was not met');
                }
                break;
            default:
        }
    }
}
