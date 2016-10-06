<?php namespace TTC;

use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{

    public function run()
    {
        $this->call('TTC\Production\CountrySeeder');
        $this->call('TTC\Production\LanguageSeeder');
        $this->call('TTC\Production\PermissionSeeder');
        $this->call('TTC\Production\RoleSeeder');
        $this->call('TTC\Production\UserSeeder');
    }

}
