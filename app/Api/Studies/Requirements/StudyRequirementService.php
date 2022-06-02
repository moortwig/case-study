<?php

namespace App\Api\Studies\Requirements;

use Carbon\Carbon;

/**
 * Ensure the submitted data lives up to the requirements for a study.
 *
 * For simplicity, we presume just one rule/requirement. In reality, there may be more than just one rule ... :)
 */
class StudyRequirementService
{
    private StudyRequirementValidator $validator;

    public function __construct(StudyRequirementValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @throws AgeRequirementNotMetException
     */
    public function validate(array $requirements, ?Carbon $date): void
    {
        if (!$date) {
            return;
        }

        foreach ($requirements as $key => $requirement) {
            if (!StudyRequirement::requirementExists($key)) {
                return;
            }

            switch ($key) {
                case StudyRequirement::REQUIREMENT_AGE:
                    $this->validator->validateAge($date, $requirement);
                    break;
                default:
                    break;
            }

        }

    }
}
