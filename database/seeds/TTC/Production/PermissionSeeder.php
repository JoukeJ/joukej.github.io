<?php namespace TTC\Production;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{

    public function run()
    {
        // Superuser
        $superuser = Permission::create([
            'name' => 'superuser',
            'display_name' => 'Superuser',
            'description' => 'Superuser',
        ]);

        // User management
        Permission::create([
            'name' => 'management.users',
            'display_name' => 'User management',
            'description' => 'User can management all users in application'
        ]);

        // Surveys
        Permission::create([
            'name' => 'management.survey.create',
            'display_name' => 'Create survey',
            'description' => 'User can create a survey'
        ]);
        Permission::create([
            'name' => 'management.survey.update',
            'display_name' => 'Edit survey',
            'description' => 'User can edit a survey'
        ]);
        Permission::create([
            'name' => 'management.survey.delete',
            'display_name' => 'Delete survey',
            'description' => 'User can delete a survey'
        ]);
        Permission::create([
            'name' => 'management.survey.seeall',
            'display_name' => 'See all surveys',
            'description' => 'User can all the surveys'
        ]);
        Permission::create([
            'name' => 'management.survey.seeown',
            'display_name' => 'See own surveys',
            'description' => 'User can see his/her own surveys'
        ]);
    }

}
