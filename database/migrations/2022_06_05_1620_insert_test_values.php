<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** Table `studies` */
        $id = DB::table('studies')->insertGetId([
            'name' => 'Migraine Study',
            'state' => 'screening',
            'requirements' => json_encode(['age' => ['rule' => 'min', 'value' => 18]]),
        ]);

        /** Table `study_groups` */
        DB::table('study_groups')->insert([
            'name' => 'Cohort A',
            'study_id' => $id,
            'member_title' => 'participant',
            'frequency' => json_encode(['monthly' => 'monthly', 'weekly' => 'weekly']),
        ]);
        DB::table('study_groups')->insert([
            'name' => 'Cohort B',
            'study_id' => $id,
            'member_title' => 'candidate',
            'frequency' => json_encode(['daily' => 'daily']),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /** Table `study_groups` */
        DB::unprepared('DELETE FROM study_groups WHERE name IN (\'Cohort A\', \'Cohort B\')');

        /** Table `studies` */
        DB::unprepared('DELETE FROM studies WHERE name = \'Migraine Study\'');

    }
};
