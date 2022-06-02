<?php

namespace App\Api\Studies\Participants;

use App\Exceptions\SQLInsertFailedException;

class ParticipantService
{
    private ParticipantRepository $repository;

    public function __construct(ParticipantRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws SQLInsertFailedException
     */
    public function insertParticipant(array $values): void
    {
        $insertData = [
            'studygroup_id' => $values['studyGroup']->id,
            'first_name' => $values['firstName'],
            'date_of_birth' => $values['dateOfBirth']->format('Y-m-d'),
            'frequency' => $values['frequency'],
            'daily_frequency' => $values['dailyFrequency'],
        ];

        $this->repository->insertParticipants($insertData);
    }
}
