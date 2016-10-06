<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLastNameTimeZoneLanguageToUser extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function(Blueprint $table){
			$table->renameColumn('name', 'first_name');
			$table->string('last_name', 50)->after('name');
			$table->string('timezone', 20)->after('email');
			$table->string('language', 5)->after('email');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function(Blueprint $table){
			$table->renameColumn('first_name', 'name');
			$table->dropColumn('last_name');
			$table->dropColumn('timezone');
			$table->dropColumn('language');
		});
	}

}
