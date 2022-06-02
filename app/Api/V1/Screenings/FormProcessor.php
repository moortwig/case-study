<?php

namespace App\Api\V1\Screenings;

use App\Api\Studies\Participants\ParticipantService;
use App\Api\Studies\Requirements\AgeRequirementNotMetException;
use App\Api\Studies\Requirements\StudyRequirementService;
use App\Api\Studies\StudyGroups\StudyGroupService;
use App\Api\Studies\StudyService;
use App\Exceptions\MissingDependentDataException;
use App\Exceptions\SQLInsertFailedException;
use Carbon\Carbon;

class FormProcessor
{
    private StudyRequirementService $requirementService;
    private StudyGroupService $studyGroupService;
    private ParticipantService $participantService;
    private StudyService $studyService;

    public function __construct(
        StudyService $studyService,
        StudyRequirementService $requirementService,
        StudyGroupService $studyGroupService,
        ParticipantService $participantService
    )
    {
        $this->studyService = $studyService;
        $this->requirementService = $requirementService;
        $this->studyGroupService = $studyGroupService;
        $this->participantService = $participantService;
    }

    /**
     * @throws AgeRequirementNotMetException
     * @throws SQLInsertFailedException
     * @throws MissingDependentDataException
     */
    public function process(array $values, int $studyId): array
    {
        $study = $this->studyService->getStudy($studyId);
        $studyGroups = $this->studyGroupService->getGroup($studyId);
        $this->requirementService->validate($study['requirements'], $values['dateOfBirth']);

        $values['studyGroup'] = null;
        foreach ($studyGroups as $group) {
            if ($this->studyGroupService->matchesGroup($group->frequency, $values['frequency'])) {
                $values['studyGroup'] = $group;
            }
        }

        if (!$values['studyGroup']) {
            throw new MissingDependentDataException('Sorry, there\'s no group we can assign you to');
        }

        $this->participantService->insertParticipant($values);

        return [
            'success' => true,
            'message' => sprintf(
                '%s %s is assigned to %s',
                ucfirst($values['studyGroup']->memberTitle),
                $values['firstName'],
                $values['studyGroup']->name,
            )
        ];
    }

    public function mapFormValuesByKey(array $formValues): array
    {
        $mapped = [];
        foreach ($formValues as $fieldValues) {
            $mapped[$fieldValues['inputId']] = $fieldValues;
        }

        return $mapped;
    }

    public function buildDateFromValues(array $values): ?Carbon
    {
        $year = null;
        $month = null;
        $day = null;
        foreach ($values as $fieldValues) {
            if ('year' === $fieldValues['validationType']) {
                $year = (int) $fieldValues['value'];
            }
            if ('month' === $fieldValues['validationType']) {
                $month = (int) $fieldValues['value'];
            }
            if ('day' === $fieldValues['validationType']) {
                $day = (int) $fieldValues['value'];
            }
        }

        if ($year && $month && $day) {
            return \Illuminate\Support\Carbon::createFromDate($year, $month, $day);
        }

        return null;
    }

    public function makeFriendly(array $values, ?Carbon $date): array
    {
        $frequency = $this->findElement($values, 'frequency');

        $dailyFrequency = null;
        if ('daily' === $frequency['value']) {
            $dailyFrequency = $this->findElement($values, 'daily-frequency');
        }

        return [
            'firstName' => ucfirst(strtolower(trim($values['first-name']['value']))),
            'dateOfBirth' => $date,
            'frequency' => $frequency['value'],
            'dailyFrequency' => $dailyFrequency ? $dailyFrequency['value'] : null,
        ];
    }

    private function findElement(array $values, string $needle): ?array
    {
        foreach ($values as $value) {
            if ($needle === $value['validationType']) {
                return $value;
            }
        }

        return null;
    }
}
