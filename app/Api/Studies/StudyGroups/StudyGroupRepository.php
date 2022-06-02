<?php

namespace App\Api\Studies\StudyGroups;

use App\Exceptions\SQLRetrievalFailedException;
use Illuminate\Support\Facades\DB;

class StudyGroupRepository
{
    /**
     * @throws SQLRetrievalFailedException
     */
    public function getGroups(int $studyId): array
    {
        $sql = '
            SELECT
                id,
                study_id AS studyId,
                name,
                member_title AS memberTitle,
                frequency
            FROM study_groups
            WHERE study_id = :studyId;
        ';

        $result = DB::select($sql, ['studyId' => $studyId]);
        if (!$result) {
            throw new SQLRetrievalFailedException('Failed to retrieve study_groups');
        }

        return $result;
    }
}
