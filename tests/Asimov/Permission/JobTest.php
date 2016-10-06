<?php namespace Test\Asimov\Permission;

use App\Jobs\Asimov\Management\Permissions\CreatePermissionJob;
use App\Jobs\Asimov\Management\Permissions\DeletePermissionJob;
use App\Jobs\Asimov\Management\Permissions\UpdatePermissionJob;
use App\Models\Permission;
use App\Models\User;
use Test\Asimov\BaseAsimovTest;

class JobTest extends BaseAsimovTest
{
    public function testCreatePermission()
    {
        $job = \App::make(CreatePermissionJob::class);

        $permission = factory(Permission::class)->make();

        \Input::replace($permission->getAttributes());

        $newPermission = $job->handle();

        $this->assertEquals($permission->description, $newPermission->description);
    }

    public function testCreatePermissionWithDuplicateName()
    {
        $this->setExpectedException('App\Exceptions\GeneralException');

        $permission = factory(Permission::class)->create();

        $job = \App::make(CreatePermissionJob::class);

        \Input::replace($permission->getAttributes());

        $newPermission = $job->handle();
    }

    // @todo test in another way
    /*
    public function testCreatePermissionFails()
    {
        $this->setExpectedException('App\Exceptions\GeneralException');

        $command = \App::make(CreatePermissionJob::class);

        \Input::replace([]);

        $command->handle();
    }
    */

    public function testUpdatePermission()
    {
        $permission = factory(Permission::class)->create();

        $command = \App::make(UpdatePermissionJob::class);

        $newName = 'delete.harddisk';

        \Input::replace(array_merge($permission->getAttributes(), ['name' => $newName]));

        $updatedPermission = $command->handle();

        $this->assertNotEquals($permission->getAttributes(), $updatedPermission->getAttributes());
        $this->assertEquals($newName, $updatedPermission->name);

    }

    public function testUpdatePermissionFailsWithDuplicateName()
    {
        $this->setExpectedException('App\Exceptions\GeneralException');

        $command = \App::make(UpdatePermissionJob::class);

        $permission = factory(Permission::class)->create(['name' => 'im unique!']);
        $permissionDup = factory(Permission::class)->create(['name' => 'im a wannabe']);

        \Input::replace(array_merge($permission->getAttributes(), ['id' => $permissionDup->id]));

        $command->handle();
    }


    public function testDeletePermission()
    {
        $permission = factory(Permission::class)->create();

        $route = \Mockery::mock('Illuminate\Routing\Route');
        $route->shouldReceive('getParameter')->andReturn($permission->id);

        \Route::shouldReceive('current')->once()->andReturn($route);

        $command = \App::make(DeletePermissionJob::class);

        $command->handle();

        $this->assertNull(Permission::find($permission->id));
    }
}
