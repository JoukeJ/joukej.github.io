<?php namespace App\Repositories\Asimov\Management\Users;

use App\Exceptions\GeneralException;
use App\Models\User;
use App\Repositories\Asimov\Management\Roles\RoleContract;

class UserRepository implements UserContract
{
    /**
     * @var RoleContract
     */
    private $roles;

    /**
     * @param RoleContract $roles
     */
    public function __construct(RoleContract $roles)
    {
        $this->roles = $roles;
    }

    /**
     * @param $id
     * @param bool $withRoles
     * @return User
     * @throws GeneralException
     */
    public function findOrThrowException($id, $withRoles = false)
    {
        if ($withRoles === true) {
            $user = User::with('roles')->withTrashed()->find($id);
        } else {
            $user = User::withTrashed()->find($id);
        }

        if (is_null($user) === false) {
            return $user;
        }

        throw new GeneralException("User with that id cannot be found.");
    }

    /**
     * @param $per_page
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getUsersPaginated($per_page, $order_by = 'id', $sort = 'asc')
    {
        return User::orderBy($order_by, $sort)->paginate($per_page);
    }

    /**
     * @param $per_page
     * @return \Illuminate\Pagination\Paginator
     */
    public function getDeletedUsersPaginated($per_page)
    {
        return User::withTrashed()->paginate($per_page);
    }

    /**
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getAllUsers($order_by = 'id', $sort = 'asc')
    {
        return User::orderBy($order_by, $sort)->get();
    }

    /**
     * @param $input
     * @param $roles
     * @return mixed
     * @throws GeneralException
     */
    public function create($input, $roles)
    {
        try {
            $user = $this->createUserStub($input);

            if ($user->save() === true) {

                $this->validateRoleAmount($roles);

                $user->attachRoles($roles);

                return $user;
            }
        } catch (\Exception $e) {
        }

        throw new GeneralException("The user could not be created. try again");
    }

    /**
     * @param $id
     * @param $input
     * @param $roles
     * @param $permissions
     * @return mixed
     * @throws GeneralException
     */
    public function update($id, $input, $roles, $permissions)
    {
        try {
            $user = $this->findOrThrowException($id);

            $user->fill($input);

            if ($user->save()) {
                // first detach all roles
                $user->detachRoles($this->roles->getAllRoles());

                $user->attachRoles($roles);

                return $user;
            }
        } catch (\Exception $e) {
        }

        throw new GeneralException("The user could not be created. try again");
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $user = $this->findOrThrowException($id);

        $user->delete();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function restore($id)
    {
        // TODO: Implement restore() method.
    }

    /**
     * @param $id
     * @param $status
     * @return mixed
     */
    public function mark($id, $status)
    {
        // TODO: Implement mark() method.
    }

    /**
     * @param $id
     * @param $input
     * @return mixed
     */
    public function updatePassword($id, $input)
    {
        // TODO: Implement updatePassword() method.
    }

    /**
     * @param $input
     * @return User
     */
    private function createUserStub($input)
    {
        $user = new User();

        $user->fill($input);

        $user->password = bcrypt($input['password']);

//        $user->remember_token = null;

        return $user;
    }

    /**
     * @param $roles
     * @throws GeneralException
     */
    private function validateRoleAmount($roles)
    {
        if (sizeof($roles) === 0) {
            throw new GeneralException("User must have at least one role");
        }
    }

    /**
     * @param $id
     * @param $email
     * @return bool
     */
    public function changeEmail($id, $email)
    {
        $user = $this->findOrThrowException($id);

        $user->email = $email;

        return $user->save();
    }


}
