<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_entity_q_image', function(Blueprint $table){
            $table->increments('id');
            $table->string('question', 128);
            $table->text('description')->nullable();
            $table->boolean('required')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('survey_entity_q_image');
    }
}
