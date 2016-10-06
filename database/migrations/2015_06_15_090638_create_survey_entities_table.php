<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSurveyEntitiesTable extends Migration {

	public function up()
	{
		Schema::create('survey_entities', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('survey_id')->unsigned();
			$table->integer('entity_id')->unsigned();
			$table->string('entity_type', 64);
			$table->integer('order')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('survey_entities');
	}
}