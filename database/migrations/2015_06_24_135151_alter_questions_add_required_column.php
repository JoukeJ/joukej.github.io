<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterQuestionsAddRequiredColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('survey_entity_q_open', function (Blueprint $table) {
            $table->boolean('required')->after('description')->default(true);
        });

        Schema::table('survey_entity_q_text', function (Blueprint $table) {
            $table->boolean('required')->after('description')->default(true);
        });

        Schema::table('survey_entity_q_radio', function (Blueprint $table) {
            $table->boolean('required')->after('description')->default(true);
        });

        Schema::table('survey_entity_q_checkbox', function (Blueprint $table) {
            $table->boolean('required')->after('description')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('survey_entity_q_open', function (Blueprint $table) {
            $table->dropColumn('required');
        });

        Schema::table('survey_entity_q_text', function (Blueprint $table) {
            $table->dropColumn('required');
        });

        Schema::table('survey_entity_q_radio', function (Blueprint $table) {
            $table->dropColumn('required');
        });

        Schema::table('survey_entity_q_checkbox', function (Blueprint $table) {
            $table->dropColumn('required');
        });
    }
}
