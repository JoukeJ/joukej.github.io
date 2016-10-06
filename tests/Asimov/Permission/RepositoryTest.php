<?php namespace Asimov\Permission;

use App\Models\Permission;
use Test\Asimov\BaseAsimovTest;

class RepositoryTest extends BaseAsimovTest
{
    /**
     * @var string
     */
    private $className = 'App\Repositories\Asimov\Management\Permissions\PermissionRepository';

    /**
     * @var \App\Repositories\Asimov\Management\Permissions\PermissionRepository
     */
    private $permissionRepository;

    public function setUp()
    {
        parent::setUp();

        $this->permissionRepository = \App::make($this->className);
    }

    public function testFindOrThrowExceptionAndThrowsException()
    {
        $this->setExpectedException('App\Exceptions\GeneralException');

        $this->permissionRepository->findOrThrowException(1);
    }

    public function testFindOrThrowException()
    {
        $perm = factory(Permission::class)->create();

        $this->permissionRepository->findOrThrowException($perm->id);
    }

    public function testGetAllPermissions()
    {
        for ($i = 0; $i < 10; $i++) {
            factory(Permission::class)->create();
        }

        $this->assertEquals(Permission::orderBy('display_name')->get(),
            $this->permissionRepository->getAllPermissions());
    }

    public function testCreate()
    {
        $permission = factory(Permission::class)->make();

        $newPermission = $this->permissionRepository->create($permission->getAttributes());

        $this->assertNotNull($newPermission->id);
    }

    // @todo rewrite using Request object
    /*
    public function testCreateWithInsufficientData()
    {
        $this->setExpectedException('App\Exceptions\GeneralException');
        $this->permissionRepository->create([]);
    }
    */

    public function testUpdate()
    {
        $permission = factory(Permission::class)->create();

        $newName = 'Godmode';
        $permission->name = $newName;

        $newPermission = $this->permissionRepository->update($permission->id, $permission->getAttributes());

        $this->assertEquals($newName, $newPermission->name);
    }

    public function testDelete()
    {
        $this->setExpectedException('App\Exceptions\GeneralException');

        $permission = factory(Permission::class)->create();

        $this->permissionRepository->delete($permission->id);

        $this->permissionRepository->findOrThrowException($permission->id);
    }

    public function testDeleteFailsIfRecordDoesNotExists()
    {
        $this->setExpectedException('App\Exceptions\GeneralException');

        $this->permissionRepository->delete(0);
    }
}
