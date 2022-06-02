<?php

namespace App\Api\Studies\Participants;

use App\Exceptions\SQLInsertFailedException;
use Illuminate\Support\Facades\DB;

class ParticipantRepository
{
    /**
     * @throws SQLInsertFailedException
     */
    public function insertParticipants(array $data): void
    {
        $success = DB::table('study_participants')->insert($data);

        if (!$success) {
            throw new SQLInsertFailedException('Failed to create a new participant');
        }
    }
}
