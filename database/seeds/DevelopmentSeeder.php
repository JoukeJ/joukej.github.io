<?php


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DevelopmentSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call('TTC\DevelopmentSeeder');
    }
}
