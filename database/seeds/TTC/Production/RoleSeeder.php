<?php namespace TTC\Production;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder {

    public function run()
    {
        // TTC/System administrator
        $admin = Role::create([
            'name' => 'admin',
            'display_name' => 'TTC Administrator',
            'description' => 'TTC system administrator',
        ]);

        $admin->attachPermission(Permission::whereName('superuser')->first());


        // Basic TTC user
        $ttc = Role::create([
            'name' => 'ttc',
            'display_name' => 'TTC User',
            'description' => 'TTC basic user'
        ]);

        $ttc->attachPermission(Permission::whereName('management.survey.create')->first());
        $ttc->attachPermission(Permission::whereName('management.survey.delete')->first());
        $ttc->attachPermission(Permission::whereName('management.survey.update')->first());
        $ttc->attachPermission(Permission::whereName('management.survey.seeown')->first());
    }

}
