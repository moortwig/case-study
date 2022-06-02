<?php

namespace App\Api\Studies\StudyGroups;

class StudyGroupService
{
    private StudyGroupRepository $repository;

    public function __construct(StudyGroupRepository $repository)
    {
        $this->repository = $repository;
    }

    public function matchesGroup(array $groupFrequencies, string $frequency): bool
    {
        return in_array($frequency, $groupFrequencies);
    }

    public function getGroup(int $studyId): array
    {
        $studyGroups = $this->repository->getGroups($studyId);

        $studyGroupsMappedById = [];
        foreach ($studyGroups as $group) {
            $frequency = json_decode($group->frequency, true);
            $group->frequency = $frequency; // XXX: hack
            $studyGroupsMappedById[$group->id] = $group;
        }

        return $studyGroupsMappedById;
    }
}
