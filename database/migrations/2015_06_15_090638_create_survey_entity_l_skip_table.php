<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSurveyEntityLSkipTable extends Migration {

	public function up()
	{
		Schema::create('survey_entity_l_skip', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('option_id')->unsigned();
			$table->integer('entity_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('survey_entity_l_skip');
	}
}