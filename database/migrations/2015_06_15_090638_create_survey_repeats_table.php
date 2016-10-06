<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSurveyRepeatsTable extends Migration {

	public function up()
	{
		Schema::create('survey_repeats', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('survey_id')->unsigned();
			$table->string('interval', 32);
			$table->datetime('absolute_end_date');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('survey_repeats');
	}
}