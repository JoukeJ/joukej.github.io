<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailHooksTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_hooks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('email_id');

            $table->boolean('sent')->default(0);
            $table->boolean('bounced')->default(0);
            $table->integer('opened')->default(0)->unsigned();
            $table->boolean('spam')->default(0);
            $table->boolean('rejected')->default(0);
            $table->boolean('delayed')->default(0);
            $table->boolean('soft_bounced')->default(0);
            $table->integer('clicked')->default(0)->unsigned();
            $table->boolean('unsubscribed')->default(0);

            $table->timestamps();

            $table->foreign('email_id', 'fk_hook_email_id')
                ->references('id')->on('emails')
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
        Schema::table('email_hooks', function (Blueprint $table) {
            $table->dropForeign('fk_hook_email_id');
        });

        Schema::drop('email_hooks');
    }

}
