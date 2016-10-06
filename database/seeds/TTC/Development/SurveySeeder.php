<?php


namespace TTC\Development;


use App\Models\User;
use App\TTC\Models\Survey;
use Illuminate\Database\Seeder;

class SurveySeeder extends Seeder
{
    public function run()
    {
        for ($i = 0; $i < 100; $i++) {
            factory(Survey::class)->create();
        }
    }
}
