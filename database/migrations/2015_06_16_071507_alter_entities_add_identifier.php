<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterEntitiesAddIdentifier extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('survey_entities', function (Blueprint $table) {
            $table->string('identifier', 32)->after('entity_type');
            $table->unique('identifier', 'unique_entities_identifier');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('survey_entities', function (Blueprint $table) {
            $table->dropIndex('unique_entities_identifier');
            $table->dropColumn('identifier');
        });
    }
}
