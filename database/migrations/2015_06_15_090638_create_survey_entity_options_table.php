<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSurveyEntityOptionsTable extends Migration {

	public function up()
	{
		Schema::create('survey_entity_options', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('entity_id')->unsigned();
			$table->string('entity_type', 64);
			$table->string('name', 128);
			$table->string('value', 128);
		});
	}

	public function down()
	{
		Schema::drop('survey_entity_options');
	}
}