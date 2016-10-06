<?php namespace Test\Asimov\User;

use App\Jobs\Asimov\Management\Users\CreateUserJob;
use App\Jobs\Asimov\Management\Users\DeleteUserJob;
use App\Jobs\Asimov\Management\Users\UpdateUserJob;
use App\Models\Role;
use App\Models\User;
use Test\Asimov\BaseAsimovTest;

class JobTest extends BaseAsimovTest
{
    private $stubUser = [
        'first_name' => 'Sjakie',
        'last_name' => 'van de Chocoladefabriek',
        'email' => 'sjakie@chocoladefabriek.nl',
        'language' => 'nl',
        'timezone' => 'Europe/Amsterdam',
        'password' => 'sjakie'
    ];

    private $stubRole = [
        'name' => 'role',
        'display_name' => 'Rol',
        'description' => 'Stubrol'
    ];

    public function testCreateUser()
    {
        $command = \App::make(CreateUserJob::class);

        $role = Role::create($this->stubRole);

        \Input::replace($this->stubUser + ['roles' => [$role->id]]);

        $newUser = $command->handle();

        $this->assertEquals($this->stubUser['first_name'], $newUser->first_name);
    }

    public function testCreateUserFails()
    {
        $this->setExpectedException('App\Exceptions\GeneralException');

        $command = \App::make(CreateUserJob::class);

        \Input::replace([]);

        $command->handle();
    }

    public function testUpdateUser()
    {
        $user = User::find(User::create($this->stubUser)->id);
        $role = Role::create($this->stubRole);

        $user->roles()->attach($role->id);

        $command = \App::make(UpdateUserJob::class);

        $newName = 'Puk';

        \Input::replace(array_merge($this->stubUser,
            ['id' => $user->id, 'first_name' => $newName, 'roles' => [$role->id]]));

        $newUser = $command->handle();

        $this->assertNotEquals($user->getAttributes(), $newUser->getAttributes());
        $this->assertEquals($newName, $newUser->first_name);

    }

    public function testUpdateUserFailsWhenUserDoesNotExists()
    {
        $this->setExpectedException('App\Exceptions\GeneralException');

        $command = \App::make(UpdateUserJob::class);

        $command->handle();
    }

    public function testDeleteUser()
    {
        $user = User::create($this->stubUser);

        $route = \Mockery::mock('Illuminate\Routing\Route');
        $route->shouldReceive('getParameter')->andReturn($user->id);

        \Route::shouldReceive('current')->once()->andReturn($route);

        $command = \App::make(DeleteUserJob::class);

        $command->handle();

        $this->assertNull(User::find($user->id));
    }
}
