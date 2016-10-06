<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSurveyEntityQTextTable extends Migration {

	public function up()
	{
		Schema::create('survey_entity_q_text', function(Blueprint $table) {
			$table->increments('id');
			$table->string('question', 128);
			$table->text('description')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('survey_entity_q_text');
	}
}