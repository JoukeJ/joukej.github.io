<?php


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class Cleaner extends Seeder
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

        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
