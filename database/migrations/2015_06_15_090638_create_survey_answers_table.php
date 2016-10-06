<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSurveyAnswersTable extends Migration {

	public function up()
	{
		Schema::create('survey_answers', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('profile_id')->unsigned();
			$table->integer('survey_id')->unsigned();
			$table->integer('entity_id')->unsigned();
			$table->text('answer')->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('survey_answers');
	}
}