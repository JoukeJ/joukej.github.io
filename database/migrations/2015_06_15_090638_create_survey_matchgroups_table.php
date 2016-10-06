<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSurveyMatchgroupsTable extends Migration {

	public function up()
	{
		Schema::create('survey_matchgroups', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('survey_id')->unsigned();
			$table->string('name', 256);
		});
	}

	public function down()
	{
		Schema::drop('survey_matchgroups');
	}
}