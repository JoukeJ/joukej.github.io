<?php namespace TTC\Production;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{

    public function run()
    {
        $demoAdmin = User::create([
            'first_name' => 'Default',
            'last_name' => 'Admin',
            'email' => getenv('AUTH_DEFAULT_EMAIL'),
            'language' => getenv('APP_LOCALE'),
            'timezone' => getenv('APP_TIMEZONE'),
            'password' => \Hash::make(getenv('AUTH_DEFAULT_PASS')),
        ]);

        $admin = Role::whereName('admin')->first();
        $demoAdmin->attachRole($admin);
    }
}
