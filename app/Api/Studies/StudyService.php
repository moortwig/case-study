<?php

namespace App\Api\Studies;

class StudyService
{
    public function getStudy(int $id): array
    {
        // TODO retrieve from database
        return $this->mockedStudyData();
    }

    /** Represents fetched result from query SELECT ... FROM studies WHERE id = :id */
    private function mockedStudyData(): array
    {
        return [
            'id' => 1,
            'name' => 'Migraine Study',
            'state' => 'screening',
            'requirements' => ['age' => ['rule' => 'min', 'value' => 18]],
        ];
    }
}
