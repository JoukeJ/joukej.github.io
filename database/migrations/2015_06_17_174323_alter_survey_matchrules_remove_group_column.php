<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterSurveyMatchrulesRemoveGroupColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('survey_matchrules', function (Blueprint $table) {
            $table->dropColumn('group');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('survey_matchrules', function (Blueprint $table) {
            $table->tinyInteger('group')->after('matchgroup_id');
        });
    }
}
