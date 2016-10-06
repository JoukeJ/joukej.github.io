<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSurveyAnswerUnique extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `survey_answers` ADD UNIQUE INDEX `survey_answers_unique_answer` (`profile_id`, `profile_survey_id`, `survey_id`, `entity_id`)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('survey_answers', function (Blueprint $table) {
            //$table->dropUnique('survey_answers_unique_answer');
        });
    }
}
