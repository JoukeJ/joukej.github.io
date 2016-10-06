<?php namespace Test\Asimov\User;

use App\Models\Role;
use App\Models\User;

class RepositoryTest extends \Test\Asimov\BaseAsimovTest
{
    private $className = 'App\Repositories\Asimov\Management\Users\UserRepository';

    /**
     * @var \App\Repositories\Asimov\Management\Users\UserRepository
     */
    private $userRepository;

    public function setUp()
    {
        parent::setUp();

        $this->userRepository = \App::make($this->className);
    }

    public function testFindOrThrowExceptionAndThrowsException()
    {
        $this->setExpectedException('App\Exceptions\GeneralException');

        $this->userRepository->findOrThrowException(1);
    }

    public function testFindOrThrowException()
    {
        with($stubUser = factory(User::class)->make())->save();

        $user = $this->userRepository->findOrThrowException($stubUser->id);

        $this->assertNotNull($user);
    }

    public function testFindOrThrowExceptionWithRoles()
    {
        with($stubUser = factory(User::class)->make())->save();
        with($stubRole = factory(Role::class)->make())->save();

        $stubUser->roles()->attach($stubRole->id);

        $user = $this->userRepository->findOrThrowException($stubUser->id, true);

        $this->assertArrayHasKey('roles', $user->relationsToArray());
        $this->assertEquals($stubRole->id, $user->relationsToArray()['roles'][0]['id']);
    }

    public function testGetUsersPaginated()
    {
        for ($i = 0; $i < 3; $i++) {
            factory(User::class)->make(['email' => uniqid() . '@test.nl'])->save();
        }

        $per_page = 2;
        $order_by = 'id';
        $sort = 'asc';

        $expected = User::orderBy($order_by, $sort)->paginate($per_page);

        $this->assertEquals($expected, $this->userRepository->getUsersPaginated($per_page, $order_by, $sort));
    }

    public function testgetDeletedUsersPaginated()
    {
        with($stubUser = factory(User::class)->make(['email' => uniqid() . '@test.nl']))->save();

        for ($i = 0; $i < 3; $i++) {
            with($stubUser = factory(User::class)->make(['email' => uniqid() . '@test.nl']))->save();
            $stubUser->delete();
        }

        $per_page = 2;

        $expected = User::withTrashed()->paginate($per_page);

        $this->assertEquals($expected, $this->userRepository->getDeletedUsersPaginated($per_page));
    }

    public function testGetAllUsers()
    {
        with($stubUser = factory(User::class)->make(['email' => uniqid() . '@test.nl']))->save();
        $stubUser->delete();

        for ($i = 0; $i < 10; $i++) {
            with($stubUser = factory(User::class)->make(['email' => uniqid() . '@test.nl']))->save();
        }

        $order_by = 'id';
        $sort = 'asc';

        $expected = User::orderBy($order_by, $sort)->get();

        $this->assertEquals($expected, $this->userRepository->getAllUsers($order_by, $sort));
    }

    public function testCreate()
    {
        $role = factory(Role::class)->create();

        $stubUser = factory(User::class)->make();

        $user = $this->userRepository->create($stubUser->getAttributes(), [$role->id]);

        $this->assertEquals($stubUser->first_name, $user->first_name);
    }

    public function testCreateWithInsufficientData()
    {
        $this->setExpectedException('App\Exceptions\GeneralException');

        $role = factory(Role::class)->create();

        $this->userRepository->create(['first_name' => 'Sjakie'], [$role->id]);
    }

    public function testPasswordIsHashedOnCreate()
    {
        $role = factory(Role::class)->create();

        $stubUser = factory(User::class)->make();

        $user = $this->userRepository->create($stubUser->getAttributes(), [$role->id]);

        // password has to be hashed so it shouldnt be the same
        $this->assertNotEquals($stubUser->password, $user->password);
    }

    public function testUpdate()
    {
        $user = factory(User::class)->create();

        $updatedUser = $this->userRepository->update($user->id, ['last_name' => 'van de Petteflat'], [], []);

        $this->assertNotEquals($user->last_name, $updatedUser->last_name);
    }

    public function testUpdateLeavesPasswordAlone()
    {
        $user = factory(User::class)->create();

        $updatedUser = $this->userRepository->update($user->id, ['last_name' => 'van de Petteflat'], [], []);

        $this->assertEquals($user->password, $updatedUser->password);
    }

    public function testDelete()
    {
        $user = factory(User::class)->create();

        $this->userRepository->delete($user->id);

        $deletedUser = $this->userRepository->findOrThrowException($user->id);

        $this->assertNotNull($deletedUser->deleted_at);
    }
}
