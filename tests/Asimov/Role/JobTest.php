<?php namespace Test\Asimov\Role;

use App\Jobs\Asimov\Management\Roles\CreateRoleJob;
use App\Jobs\Asimov\Management\Roles\DeleteRoleJob;
use App\Jobs\Asimov\Management\Roles\UpdateRoleJob;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Test\Asimov\BaseAsimovTest;

class JobTest extends BaseAsimovTest
{

    /**
     * @var string
     */
    private $className = 'App\Repositories\Asimov\Management\Roles\RoleRepository';

    /**
     * @var \App\Repositories\Asimov\Management\Roles\RoleRepository
     */
    private $roleRepository;

    public function setUp()
    {
        parent::setUp();

        $this->roleRepository = \App::make($this->className);
    }

    public function testCreateRole()
    {
        $job = \App::make(CreateRoleJob::class);

        $role = factory(Role::class)->make();
        $permission = factory(Permission::class)->create();

        \Input::replace(array_merge($role->getAttributes(), ['permissions' => [$permission->id]]));

        $newRole = $job->handle();

        $this->assertEquals($role->description, $newRole->description);
    }

    public function testCreateRoleWithDuplicateName()
    {
        $this->setExpectedException('App\Exceptions\GeneralException');

        $permission = factory(Permission::class)->create();
        $role = factory(Role::class)->make();

        $this->roleRepository->create($role->getAttributes(), [$permission->id]);

        $job = \App::make(CreateRoleJob::class);

        \Input::replace(array_merge($role->getAttributes(), ['permissions' => [$permission->id]]));

        $job->handle();
    }


    public function testCreateRoleWithoutPermissions()
    {
        $this->setExpectedException('App\Exceptions\GeneralException');

        $role = factory(Role::class)->make();

        $command = \App::make(CreateRoleJob::class);

        \Input::replace(array_merge($role->getAttributes(), ['permissions' => []]));

        $command->handle();
    }

    public function testUpdateRole()
    {
        $permission = factory(Permission::class)->create();

        $stub = factory(Role::class)->make();
        $stubUpdate = factory(Role::class)->make();

        $role = $this->roleRepository->create($stub->getAttributes(), [$permission->id]);
        $role->fill($stubUpdate->getAttributes());

        $command = \App::make(UpdateRoleJob::class);
        \Input::replace(array_merge($role->getAttributes(), ['permissions' => [$permission->id]]));

        $updatedRole = $command->handle();

        $this->assertNotEquals($stub->description, $updatedRole->description);
        $this->assertEquals($stubUpdate->description, $updatedRole->description);
    }

    public function testUpdateRoleWithoutPermissions()
    {
        $this->setExpectedException('App\Exceptions\GeneralException');

        $permission = factory(Permission::class)->create();

        $stub = factory(Role::class)->make();
        $stubUpdate = factory(Role::class)->make();

        $role = $this->roleRepository->create($stub->getAttributes(), [$permission->id]);
        $role->fill($stubUpdate->getAttributes());

        $command = \App::make(UpdateRoleJob::class);
        \Input::replace(array_merge($role->getAttributes(), ['permissions' => []]));

        $command->handle();
    }

    public function testUpdateRoleWithDuplicateName()
    {
        $this->setExpectedException('App\Exceptions\GeneralException');

        $permission = factory(Permission::class)->create();
        $roleA = factory(Role::class)->make();
        $roleB = factory(Role::class)->make();

        $A = $this->roleRepository->create($roleA->getAttributes(), [$permission->id]);
        $B = $this->roleRepository->create($roleB->getAttributes(), [$permission->id]);

        $job = \App::make(UpdateRoleJob::class);

        \Input::replace(array_merge(['id' => $B->id], $roleA->getAttributes(), ['permissions' => [$permission->id]]));

        $job->handle();
    }

    public function testDeleteRole()
    {
        $permission = factory(Permission::class)->create();
        $role = factory(Role::class)->make();

        $role = $this->roleRepository->create($role->getAttributes(), [$permission->id]);

        $route = \Mockery::mock('Illuminate\Routing\Route');
        $route->shouldReceive('getParameter')->andReturn($role->id);

        \Route::shouldReceive('current')->once()->andReturn($route);

        $command = \App::make(DeleteRoleJob::class);

        $command->handle();

        $this->assertNull(Role::find($role->id));
    }
}

