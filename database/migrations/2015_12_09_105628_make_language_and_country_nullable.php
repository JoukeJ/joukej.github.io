<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeLanguageAndCountryNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles', function(Blueprint $table){
            $table->integer('language_id')->unsigned()->nullable()->change();
            $table->integer('geo_country_id')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profiles', function(Blueprint $table) {
            $table->integer('language_id')->unsigned()->change();
            $table->integer('geo_country_id')->unsigned()->change();
        });
    }
}
