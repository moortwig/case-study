<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::create('studies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('state');
            $table->text('requirements')->nullable();
            $table->timestamp('created_at')->useCurrent()->nullable();
            $table->timestamp('updated_at')->useCurrent()->nullable();
        });

        /** Table `study_groups` */
        Schema::create('study_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('study_id', false)->index();
            $table->string('member_title')->default('participant');
            $table->string('frequency')->nullable();
            $table->timestamp('created_at')->useCurrent()->nullable();
            $table->timestamp('updated_at')->useCurrent()->nullable();
            $table->unique(['name', 'study_id']);
        });

        /** Table `study_participants` */
        Schema::create('study_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('studygroup_id', false)->index();
            $table->string('first_name');
            $table->date('date_of_birth');
            $table->string('frequency');
            $table->string('daily_frequency')->nullable();
            $table->timestamp('assigned_at')->useCurrent()->nullable();
            $table->timestamp('created_at')->useCurrent()->nullable();
            $table->timestamp('updated_at')->useCurrent()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('studies');
        Schema::dropIfExists('study_groups');
        Schema::dropIfExists('study_participants');
    }
};
