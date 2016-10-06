<?php

use Illuminate\Database\Migrations\Migration;

class AlterEntitiesChangeIdentifierLength extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `survey_entities` MODIFY COLUMN `identifier` VARCHAR(16) NOT NULL after `survey_id`');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `survey_entities` MODIFY COLUMN `identifier` VARCHAR(32) NOT NULL');
    }
}
