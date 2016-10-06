<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSurveyEntityIVideoTable extends Migration {

	public function up()
	{
		Schema::create('survey_entity_i_video', function(Blueprint $table) {
			$table->increments('id');
			$table->text('description')->nullable();
			$table->string('service', 32);
			$table->string('url', 256);
		});
	}

	public function down()
	{
		Schema::drop('survey_entity_i_video');
	}
}