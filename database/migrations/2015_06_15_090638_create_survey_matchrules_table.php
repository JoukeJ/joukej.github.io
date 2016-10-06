<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSurveyMatchrulesTable extends Migration {

	public function up()
	{
		Schema::create('survey_matchrules', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('matchgroup_id')->unsigned();
			$table->tinyInteger('group')->unsigned();
			$table->string('attribute', 32);
			$table->string('operator', 16);
			$table->string('values', 256);
		});
	}

	public function down()
	{
		Schema::drop('survey_matchrules');
	}
}