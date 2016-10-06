<?php


namespace Development;


use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{

    public function run()
    {

        $admin = Role::create([
            'name' => 'admin',
            'display_name' => 'Administrator',
            'description' => 'Systeem administrator',
        ]);

        $superuser = Permission::create([
            'name' => 'superuser',
            'display_name' => 'Superuser',
            'description' => 'Superuser',
        ]);

        $admin->attachPermission($superuser);


        $users = [];

        $users ['rob'] = User::create([
            'first_name' => 'Rob',
            'last_name' => 'van Bentem',
            'email' => 'vanbentem@unifact.eu',
            'language' => 'nl',
            'timezone' => 'Europe/Amsterdam',
            'password' => \Hash::make('yolo4life'),
        ]);

        $users['luuk'] = User::create([
            'first_name' => 'Luuk',
            'last_name' => 'Holleman',
            'email' => 'holleman@unifact.eu',
            'language' => 'nl',
            'timezone' => 'Europe/Amsterdam',
            'password' => \Hash::make('yolo4life'),
        ]);

        $users['roos'] = User::create([
            'first_name' => 'Roos',
            'last_name' => '',
            'email' => 'roos@unifact.eu',
            'language' => 'nl',
            'timezone' => 'Europe/Amsterdam',
            'password' => \Hash::make('yolo4life'),
        ]);

        $users['jvd'] = User::create([
            'first_name' => 'Joshua',
            'last_name' => 'van Diepen',
            'email' => 'vandiepen@unifact.eu',
            'language' => 'nl',
            'timezone' => 'Europe/Amsterdam',
            'password' => \Hash::make('yolo4life'),
        ]);

        $users['wind'] = User::create([
            'first_name' => 'Rurik',
            'last_name' => 'Wind',
            'email' => 'wind@unifact.eu',
            'language' => 'nl',
            'timezone' => 'Europe/Amsterdam',
            'password' => \Hash::make('yolo4life'),
        ]);

        $users['slaag'] = User::create([
            'first_name' => 'Arjan',
            'last_name' => 'Slaager',
            'email' => 'slaager@unifact.eu',
            'language' => 'nl',
            'timezone' => 'Europe/Amsterdam',
            'password' => \Hash::make('yolo4life'),
        ]);

        $users['ejvds'] = User::create([
            'first_name' => 'Ernst Jan',
            'last_name' => 'van der Steege',
            'email' => 'vandersteege@unifact.eu',
            'language' => 'nl',
            'timezone' => 'Europe/Amsterdam',
            'password' => \Hash::make('yolo4life'),
        ]);

        $users['garden'] = User::create([
            'first_name' => 'Maarten',
            'last_name' => 'Tuin',
            'email' => 'tuin@unifact.eu',
            'language' => 'nl',
            'timezone' => 'Europe/Amsterdam',
            'password' => \Hash::make('yolo4life'),
        ]);


        foreach ($users as $name => $user) {
            $user->attachRole($admin);
        }


    }

}
