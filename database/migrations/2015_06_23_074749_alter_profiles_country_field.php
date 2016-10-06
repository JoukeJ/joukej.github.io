<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterProfilesCountryField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn('geo_country');
            $table->integer('geo_country_id')->after('birthday')->unsigned();
        });

        Schema::table('profiles', function (Blueprint $table) {
            $table->foreign('geo_country_id')->references('id')->on('countries')->onUpdate('no action')->onDelete('restrict');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profiles', function(Blueprint $table){
            $table->dropForeign('profiles_geo_country_id_foreign');
        });

        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn('geo_country_id');
            $table->string('geo_country', 64)->after('birthday');
        });
    }
}
