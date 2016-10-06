<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMorphIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('survey_entities', function (Blueprint $table) {
            $table->index(['entity_type', 'entity_id'], 'survey_entities_morph');
        });

        Schema::table('survey_entity_options', function (Blueprint $table) {
            $table->index(['entity_type', 'entity_id'], 'survey_entity_options_morph');
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
            $table->dropIndex('survey_entities_morph');
        });

        Schema::table('survey_entity_options', function (Blueprint $table) {
            $table->dropIndex('survey_entity_options_morph');
        });
    }
}
