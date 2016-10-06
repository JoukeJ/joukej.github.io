<?php namespace TTC;

use Illuminate\Database\Seeder;

class DevelopmentSeeder extends Seeder
{

    private $excludedTables = [
        'migrations'
    ];

    public function run()
    {
        \Eloquent::unguard();

        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $tables = \DB::select('SHOW TABLES');

        foreach ($tables as $table) {
            $column = "Tables_in_" . \DB::getConfig('database');

            if (in_array($table->$column, $this->excludedTables)) {
                continue;
            }

            \DB::table($table->$column)->truncate();
        }

        $this->call('TTC\ProductionSeeder');
        $this->call('TTC\Development\UserSeeder');
        $this->call('TTC\Development\SurveySeeder');
        $this->call('TTC\Development\EntitySeeder');
        $this->call('TTC\Development\ProfileSeeder');

        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
