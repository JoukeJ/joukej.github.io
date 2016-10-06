<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddProfileIdentifierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_identifiers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('profile_id')->unsigned();
            $table->integer('survey_id')->unsigned()->nullable();
            $table->string('identifier', 8);
            $table->boolean('responded');
        });

        Schema::table('profile_identifiers', function (Blueprint $table) {
            $table->foreign('profile_id')
                ->references('id')->on('profiles')
                ->onDelete('RESTRICT')
                ->onUpdate('NO ACTION');

            $table->foreign('survey_id')
                ->references('id')->on('surveys')
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
        Schema::table('profile_identifiers', function (Blueprint $table) {
            $table->dropForeign('profile_identifiers_profile_id_foreign');
            $table->dropForeign('profile_identifiers_survey_id_foreign');
        });

        Schema::drop('profile_identifiers');
    }
}
