<?php namespace TTC;

use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{

    private $excludedTables = [
        'migrations'
    ];

    public function run()
    {
        \DB::connection('mysql_test')->unprepared(\File::get(base_path(DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'survey_seed.sql')));
    }
}
