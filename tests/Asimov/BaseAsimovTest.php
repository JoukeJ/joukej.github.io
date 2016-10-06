<?php namespace Test\Asimov;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class BaseAsimovTest extends \TestCase
{
    use DatabaseTransactions;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        \Dotenv::load('.');
        putenv('DB_DATABASE=' . getenv('DB_DATABASE_TEST'));

        $output = parent::createApplication();

        \Artisan::call('migrate');

        return $output;
    }

    public function beSuperuser()
    {
        $user = User::where('first_name', 'Admin')->first();

        if ($user === null) {
            $user = factory(User::class)->create([
                'first_name' => 'Admin',
            ]);
            $role = factory(Role::class)->create([
                'name' => 'superuser',
                'display_name' => 'Superuser',
            ]);
            $permission = factory(Permission::class)->create([
                'name' => 'superuser',
                'display_name' => 'Superuser',
            ]);

            $user->roles()->attach($role->id);
            $role->perms()->attach($permission->id);
        }


        $this->be($user);

        $this->user = $user;
    }
}
