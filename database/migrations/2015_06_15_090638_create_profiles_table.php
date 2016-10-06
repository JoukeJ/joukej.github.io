<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProfilesTable extends Migration {

	public function up()
	{
		Schema::create('profiles', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('language_id')->unsigned();
			$table->bigInteger('phonenumber')->index();
			$table->string('name', 128)->nullable();
			$table->string('gender', 16)->nullable();
			$table->date('birthday')->nullable();
			$table->string('geo_country', 64)->nullable();
			$table->string('geo_city', 64)->nullable();
			$table->string('geo_lat', 16)->nullable();
			$table->string('geo_lng', 16)->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('profiles');
	}
}