<?php

namespace TTC\Development;

use App\TTC\Models\Country;
use App\TTC\Models\Language;
use App\TTC\Models\Profile;
use App\TTC\Models\Survey;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    public function run()
    {
        for ($i = 0; $i < 1000; $i++) {
            $lang = Language::orderByRaw('RAND()')->first();
            $country = Country::orderByRaw('RAND()')->first();
            $profile = factory(Profile::class)->create(['language_id' => $lang->id, 'geo_country_id' => $country->id]);

            /*            for ($j = 0; $j < rand(0, 5); $j++) {
                            $survey = Survey::orderByRaw('RAND()')->first();

                            Profile\Survey::create([
                                'profile_id' => $profile->id,
                                'survey_id' => $survey->id,
                                'status' => $f->randomElement(['progress', 'completed', 'abandoned'])
                            ]);
                        }*/
        }
    }
}
