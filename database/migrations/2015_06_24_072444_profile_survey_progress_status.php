<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ProfileSurveyProgressStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_surveys', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('profile_id')->unsigned();
            $table->integer('survey_id')->unsigned();
            $table->string('status', 16)->notNull();
            $table->timestamps();
        });

        Schema::table('profile_surveys', function (Blueprint $table) {
            $table->foreign('profile_id')->references('id')->on('profiles')->onUpdate('no action')->onDelete('cascade');
            $table->foreign('survey_id')->references('id')->on('surveys')->onUpdate('no action')->onDelete('cascade');
        });

        Schema::table('survey_answers', function (Blueprint $table) {
            $table->integer('profile_survey_id')->unsigned()->after('entity_id');
        });

        Schema::table('survey_answers', function (Blueprint $table) {
            $table->foreign('profile_survey_id')->references('id')->on('profile_surveys')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('survey_answers', function (Blueprint $table) {
            $table->dropForeign('survey_answers_profile_survey_id_foreign');
        });

        Schema::table('survey_answers', function (Blueprint $table) {
            $table->dropColumn('profile_survey_id');
        });

        Schema::table('profile_surveys', function (Blueprint $table) {
            $table->dropForeign('profile_surveys_profile_id_foreign');
            $table->dropForeign('profile_surveys_survey_id_foreign');
        });

        Schema::drop('profile_surveys');
    }
}
