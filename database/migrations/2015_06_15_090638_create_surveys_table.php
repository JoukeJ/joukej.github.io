<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSurveysTable extends Migration {

	public function up()
	{
		Schema::create('surveys', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->string('language', 2);
			$table->string('name', 128);
			$table->tinyInteger('priority')->unsigned();
			$table->string('status', 16)->index();
			$table->datetime('start_date');
			$table->datetime('end_date');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('surveys');
	}
}