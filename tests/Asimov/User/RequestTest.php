<?php

namespace Asimov\User;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Test\Asimov\BaseAsimovTest;

class RequestTest extends BaseAsimovTest
{
    use WithoutMiddleware;

    public function testUserCreate()
    {
        $this->beSuperuser();

        $user = factory(User::class)->make();
        $role = factory(Role::class)->create();

        $this->post(
            \URL::route('management.users.store'),
            array_merge($user->getAttributes(), ['roles' => [$role->getAttributes()['id']]])
        )->assertRedirectedToRoute('management.users.index');

        $this->seeInDatabase('users', ['first_name' => $user->getAttributes()['first_name']]);
    }

    public function testUserCreateWithoutRoles()
    {
        $this->beSuperuser();

        $user = factory(User::class)->make();

        $this->post(
            \URL::route('management.users.store'), $user->getAttributes()
        )->assertRedirectedToRoute('home');
    }
}
