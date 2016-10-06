<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProfileSurveysAddEntityIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profile_surveys', function(Blueprint $table){
           $table->unsignedInteger('entity_id')->after('survey_id')->nullable()->default(null);

            $table->foreign('entity_id', 'profile_surveys_entity_id')
                ->references('id')->on('survey_entities')
                ->onDelete('RESTRICT')
                ->onUpdate('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profile_surveys', function(Blueprint $table){
            $table->dropForeign('profile_surveys_entity_id');
        });

        Schema::table('profile_surveys', function(Blueprint $table){
            $table->dropColumn('entity_id');
        });
    }
}
