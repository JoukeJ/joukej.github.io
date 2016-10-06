<?php

use Illuminate\Database\Migrations\Migration;

class AlterProfilesIncreaseLatLngLength extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `profiles` MODIFY COLUMN `geo_lat` VARCHAR(32)');
        DB::statement('ALTER TABLE `profiles` MODIFY COLUMN `geo_lng` VARCHAR(32)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        DB::statement('ALTER TABLE `profiles` MODIFY COLUMN `geo_lat` VARCHAR(16)');
//        DB::statement('ALTER TABLE `profiles` MODIFY COLUMN `geo_lng` VARCHAR(16)');
    }
}
