<?php


namespace TTC\Development;


use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{

    public function run()
    {

        $users = [];

        $users ['rob'] = User::create([
            'first_name' => 'Rob',
            'last_name' => 'van Bentem',
            'email' => 'vanbentem@unifact.eu',
            'language' => 'nl',
            'timezone' => 'Europe/Amsterdam',
            'password' => \Hash::make('Unifact01'),
        ]);

        $users['luuk'] = User::create([
            'first_name' => 'Luuk',
            'last_name' => 'Holleman',
            'email' => 'holleman@unifact.eu',
            'language' => 'nl',
            'timezone' => 'Europe/Amsterdam',
            'password' => \Hash::make('Unifact01'),
        ]);

        $users['roos'] = User::create([
            'first_name' => 'Roos',
            'last_name' => '',
            'email' => 'roos@unifact.eu',
            'language' => 'nl',
            'timezone' => 'Europe/Amsterdam',
            'password' => \Hash::make('Unifact01'),
        ]);

        $users['jvd'] = User::create([
            'first_name' => 'Joshua',
            'last_name' => 'van Diepen',
            'email' => 'vandiepen@unifact.eu',
            'language' => 'nl',
            'timezone' => 'Europe/Amsterdam',
            'password' => \Hash::make('Unifact01'),
        ]);

        $users['wind'] = User::create([
            'first_name' => 'Rurik',
            'last_name' => 'Wind',
            'email' => 'wind@unifact.eu',
            'language' => 'nl',
            'timezone' => 'Europe/Amsterdam',
            'password' => \Hash::make('Unifact01'),
        ]);

        $users['slaag'] = User::create([
            'first_name' => 'Arjan',
            'last_name' => 'Slaager',
            'email' => 'slaager@unifact.eu',
            'language' => 'nl',
            'timezone' => 'Europe/Amsterdam',
            'password' => \Hash::make('Unifact01'),
        ]);

        $users['ejvds'] = User::create([
            'first_name' => 'Ernst Jan',
            'last_name' => 'van der Steege',
            'email' => 'vandersteege@unifact.eu',
            'language' => 'nl',
            'timezone' => 'Europe/Amsterdam',
            'password' => \Hash::make('Unifact01'),
        ]);

        $users['garden'] = User::create([
            'first_name' => 'Maarten',
            'last_name' => 'Tuin',
            'email' => 'tuin@unifact.eu',
            'language' => 'nl',
            'timezone' => 'Europe/Amsterdam',
            'password' => \Hash::make('Unifact01'),
        ]);

        $demoAdmin = User::create([
            'first_name' => 'Demo',
            'last_name' => 'Admin',
            'email' => 'ttcdemo1@unifact.eu',
            'language' => 'nl',
            'timezone' => 'Europe/Amsterdam',
            'password' => \Hash::make('ttcdemo@2015'),
        ]);

         $demoUser = User::create([
            'first_name' => 'Demo',
            'last_name' => 'User',
            'email' => 'ttcdemo2@unifact.eu',
            'language' => 'nl',
            'timezone' => 'Europe/Amsterdam',
            'password' => \Hash::make('ttcdemo@2015'),
        ]);


        $admin = Role::whereName('admin')->first();
        $user= Role::whereName('ttc')->first();

        foreach ($users as $name => $user) {
            $user->attachRole($admin);
        }

        $demoAdmin->attachRole($admin);
        $demoUser->attachRole($user);


    }

}
