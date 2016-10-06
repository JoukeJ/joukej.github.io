<?php

use Illuminate\Database\Migrations\Migration;

class TimezoneDefaultValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `users` CHANGE `timezone` `timezone` VARCHAR(20)  CHARACTER SET utf8  COLLATE utf8_unicode_ci  NOT NULL  DEFAULT 'utc';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `users` CHANGE `timezone` `timezone` VARCHAR(20)  CHARACTER SET utf8  COLLATE utf8_unicode_ci  NOT NULL  DEFAULT '';");
    }
}
