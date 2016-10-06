<?php namespace App\Repositories\Asimov\Management\Roles;

use App\Exceptions\GeneralException;
use App\Models\Role;

class RoleRepository implements RoleContract
{
    /**
     * @return Role[]
     */
    public function getAllRoles()
    {
        return Role::all();
    }

    /**
     * @param $id
     * @param bool $withPermissions
     * @return Role
     * @throws GeneralException
     */
    public function findOrThrowException($id, $withPermissions = false)
    {
        if ($withPermissions === true) {
            $role = Role::with('perms')->find($id);
        } else {
            $role = Role::find($id);
        }

        if (is_null($role)) {
            throw new GeneralException("This role could not be found.");
        }

        return $role;
    }

    /**
     * @param $input
     * @param $permissions
     * @return Role
     * @throws GeneralException
     */
    public function create($input, $permissions)
    {
        try {
            if (sizeof($permissions) === 0) {
                throw new GeneralException("Role must have at least one permission.");
            }

            $role = $this->createStub($input);
            if ($role->save()) {
                $role->attachPermissions($permissions);

                return $role;
            }

        } catch (\Exception $e) {
            // is handled below
        }

        throw new GeneralException("Something went wrong while saving te role. Please try again.");
    }

    /**
     * @param $id
     * @param $input
     * @param $permissions
     * @return Role
     * @throws GeneralException
     */
    public function update($id, $input, $permissions)
    {
        try {
            if (sizeof($permissions) === 0) {
                throw new GeneralException("Role must have at least one permission.");
            }

            $role = $this->findOrThrowException($id);

            $role->fill($input);

            if ($role->save()) {

                $role->perms()->sync($permissions);

                return $role;
            }

        } catch (\Exception $e) {
            // is handled below
        }
        throw new GeneralException("This role could not be updated. Please try again.");
    }

    protected function createStub($input)
    {
        $role = new Role();

        $role->fill($input);

        return $role;
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        return $this->findOrThrowException($id)->delete();
    }


}
