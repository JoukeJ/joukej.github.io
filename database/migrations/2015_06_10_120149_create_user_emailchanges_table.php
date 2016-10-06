<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserEmailchangesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_emailchanges', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');

            $table->string('token', 144);
            $table->string('email', 128);
            $table->boolean('valid')->default(true);

            $table->timestamps();


            $table->foreign('user_id', 'fk_user_emailchanges_user_id')
                ->references('id')->on('users')
                ->onDelete('CASCADE')
                ->onUpdate('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_emailchanges', function (Blueprint $table) {
            $table->dropForeign('fk_user_emailchanges_user_id');
        });

        Schema::drop('user_emailchanges');
    }
}
