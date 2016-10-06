<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProfileEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_events', function(Blueprint $table){
            $table->increments('id');
            $table->integer('profile_id')->unsigned();
            $table->integer('survey_id')->unsigned()->nullable();
            $table->string('name');
        });

        Schema::table('profile_events', function (Blueprint $table) {
            $table->foreign('profile_id')
                ->references('id')->on('profiles')
                ->onDelete('RESTRICT')
                ->onUpdate('NO ACTION');

            $table->foreign('survey_id')
                ->references('id')->on('surveys')
                ->onDelete('RESTRICT')
                ->onUpdate('NO ACTION');

            $table->unique(['name', 'survey_id', 'profile_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profile_events', function (Blueprint $table) {
            $table->dropForeign('profile_events_profile_id_foreign');
            $table->dropForeign('profile_events_survey_id_foreign');
        });

        Schema::drop('profile_events');
    }
}
