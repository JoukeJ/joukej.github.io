<?php

use Illuminate\Database\Migrations\Migration;

class EnlargeAttributeAndOperatorFieldsInRules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `survey_matchrules` MODIFY COLUMN `attribute` VARCHAR(128)');
        DB::statement('ALTER TABLE `survey_matchrules` MODIFY COLUMN `operator` VARCHAR(128)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `survey_matchrules` MODIFY COLUMN `attribute` VARCHAR(32)');
        DB::statement('ALTER TABLE `survey_matchrules` MODIFY COLUMN `operator` VARCHAR(16)');
    }
}
