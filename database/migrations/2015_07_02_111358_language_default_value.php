<?php

use Illuminate\Database\Migrations\Migration;

class LanguageDefaultValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `users` CHANGE `language` `language` VARCHAR(5)  CHARACTER SET utf8  COLLATE utf8_unicode_ci  NOT NULL  DEFAULT 'en';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `users` CHANGE `language` `language` VARCHAR(5)  CHARACTER SET utf8  COLLATE utf8_unicode_ci  NOT NULL  DEFAULT '';");
    }
}
