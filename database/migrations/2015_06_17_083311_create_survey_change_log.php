<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSurveyChangeLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_log', function (Blueprint $table) {

            $table->engine = 'MyISAM';

            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('model', 128);
            $table->text('original');
            $table->text('changed');
            $table->string('action', 32);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('survey_log');
    }
}
