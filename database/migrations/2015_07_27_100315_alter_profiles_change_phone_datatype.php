<?php

use Illuminate\Database\Migrations\Migration;

class AlterProfilesChangePhoneDatatype extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `profiles` CHANGE COLUMN `phonenumber` `phonenumber` VARCHAR(32) NOT NULL AFTER `identifier`;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `profiles` CHANGE COLUMN `phonenumber` `phonenumber` BIGINT(20) NOT NULL COLLATE \'utf8_unicode_ci\' AFTER `identifier`;');
    }
}
