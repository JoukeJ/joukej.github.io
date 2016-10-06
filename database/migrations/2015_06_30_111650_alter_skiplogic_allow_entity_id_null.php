<?php

use Illuminate\Database\Migrations\Migration;

class AlterSkiplogicAllowEntityIdNull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `survey_entity_l_skip` ALTER `entity_id` DROP DEFAULT;");
        DB::statement("ALTER TABLE `survey_entity_l_skip` CHANGE COLUMN `entity_id` `entity_id` INT(10) UNSIGNED NULL AFTER `option_id`;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //DB::statement("ALTER TABLE `survey_entity_l_skip` CHANGE COLUMN `entity_id` `entity_id` INT(10) UNSIGNED NOT NULL AFTER `option_id`;");
    }
}
