<?php

use App\TTC\Models\Profile;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterProfilesAddIdentifier extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('identifier', 8)->after('language_id');
        });

        $profiles = Profile::all();

        foreach ($profiles as $profile) {
            $profile->identifier = str_random(8);
            $profile->save();
        }

        DB::statement('ALTER TABLE `profiles` MODIFY COLUMN `identifier` VARCHAR(8) NOT NULL');

        Schema::table('profiles', function (Blueprint $table) {
            $table->unique('identifier', 'profiles_identifier_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropIndex('profiles_identifier_unique');
            $table->dropColumn('identifier');
        });
    }
}
