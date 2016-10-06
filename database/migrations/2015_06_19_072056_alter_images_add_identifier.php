<?php

use App\TTC\Models\Survey\Entity\Info\Image;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterImagesAddIdentifier extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('survey_entity_i_image', function (Blueprint $table) {
            $table->string('identifier', 8)->after('id');
        });

        foreach (Image::all() as $image) {
            $image->identifier = str_random(8);
            $image->save();
        }

        DB::statement('ALTER TABLE `survey_entity_i_image` MODIFY COLUMN `identifier` VARCHAR(8) NOT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('survey_entity_i_image', function (Blueprint $table) {
            $table->dropColumn('identifier');
        });
    }
}
