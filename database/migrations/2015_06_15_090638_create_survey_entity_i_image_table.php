<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSurveyEntityIImageTable extends Migration {

	public function up()
	{
		Schema::create('survey_entity_i_image', function(Blueprint $table) {
			$table->increments('id');
			$table->text('description')->nullable();
			$table->string('path', 256);
		});
	}

	public function down()
	{
		Schema::drop('survey_entity_i_image');
	}
}