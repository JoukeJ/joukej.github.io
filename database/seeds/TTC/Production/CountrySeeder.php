<?php namespace TTC\Production;

use App\TTC\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{

    public function run()
    {
        $path = base_path('resources'.DIRECTORY_SEPARATOR.'countries.csv');

        $file = fopen($path, 'r');
        while (($line = fgetcsv($file)) !== FALSE) {
            Country::create([
                'name' => $line[0],
                'prefix' => $line[1],
                'iso' => $line[2]
            ]);
        }
        fclose($file);
    }

}
