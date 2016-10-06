<?php namespace Test\Asimov\Role;


use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Test\Asimov\BaseAsimovTest;

/**
 * Class RepositoryTest
 * @package Test\Asimov\Role
 */
class RepositoryTest extends BaseAsimovTest
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

    public function testFindOrThrowExceptionAndThrowsException()
    {
        $this->setExpectedException('App\Exceptions\GeneralException');

        $this->roleRepository->findOrThrowException(1);
    }

    public function testFindOrThrowException()
    {
        with($role = factory(Role::class)->make())->save();

        $this->roleRepository->findOrThrowException($role->id);
    }

    public function testFindOrThrowExceptionWithPermissions()
    {
        with($stubRole = factory(Role::class)->make())->save();
        with($stubPermission = factory(Permission::class)->make())->save();

        $stubRole->perms()->attach($stubPermission->id);

        $role = $this->roleRepository->findOrThrowException($stubRole->id, true);

        $this->assertArrayHasKey('perms', $role->relationsToArray());
        $this->assertEquals($stubPermission->id, $role->relationsToArray()['perms'][0]['id']);
    }

    public function testGetAllRoles()
    {
        for ($i = 0; $i < 2; $i++) {
            with(factory(Role::class)->make())->save();
        }

        $this->assertEquals(Role::all(), $this->roleRepository->getAllRoles());
    }

    public function testCreate()
    {
        $role = factory(Role::class)->make();

        with($permission = factory(Permission::class)->make())->save();

        $newRole = $this->roleRepository->create($role->getAttributes(), [$permission->id]);

        $this->assertNotNull($newRole->id);
    }

    public function testCreateWithInsufficientData()
    {
        $this->setExpectedException('App\Exceptions\GeneralException');

        $role = factory(Role::class)->make(['name' => null]);

        with($permission = factory(Permission::class)->make())->save();

        $newRole = $this->roleRepository->create($role->getAttributes(), [$permission->id]);

        $this->assertNotNull($newRole->id);
    }

    public function testUpdate()
    {
        with($role = factory(Role::class)->make())->save();
        with($permission = factory(Permission::class)->make())->save();

        $newName = 'Godmode';

        $role->name = $newName;

        $newRole = $this->roleRepository->update($role->id, $role->getAttributes(), [$permission->id]);

        $this->assertEquals($newName, $newRole->name);
    }

    public function testDelete()
    {
        $this->setExpectedException('App\Exceptions\GeneralException');

        with($role = factory(Role::class)->make())->save();

        $this->roleRepository->delete($role->id);

        $this->roleRepository->findOrThrowException($role->id);
    }

    public function testDeleteFailsIfRecordDoesNotExists()
    {
        $this->setExpectedException('App\Exceptions\GeneralException');

        $this->roleRepository->delete(0);
    }
}
