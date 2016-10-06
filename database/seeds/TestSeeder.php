<?php


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call('TTC\TestSeeder');
    }
}
