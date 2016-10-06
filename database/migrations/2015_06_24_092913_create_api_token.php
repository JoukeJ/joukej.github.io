<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateApiToken extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_tokens', function (Blueprint $table) {
            ;
            $table->increments('id');
            $table->string('customer', 50);
            $table->string('token', 128);
        });

        Schema::create('api_token_ips', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('api_token_id')->unsigned();
            $table->string('ip', 16);
            $table->timestamps();
        });

        Schema::table('api_token_ips', function (Blueprint $table) {
            $table->foreign('api_token_id')->references('id')->on('api_tokens')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('api_token_ips', function (Blueprint $table) {
            $table->dropForeign('api_token_ips_api_token_id_foreign');
        });

        Schema::drop('api_token_ips');
        Schema::drop('api_tokens');
    }
}
