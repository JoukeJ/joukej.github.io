<?php namespace App\Repositories\Asimov\Management\Permissions;

use App\Exceptions\GeneralException;
use App\Models\Permission;

class PermissionRepository implements PermissionContract
{
    /**
     * @param $id
     * @return Permission
     * @throws GeneralException
     */
    public function findOrThrowException($id)
    {
        $permission = Permission::find($id);

        if (is_null($permission)) {
            throw new GeneralException("Could not find the permission with the given id.");
        }

        return $permission;
    }

    /**
     * @return Permission[]
     */
    public function getAllPermissions($sort = 'display_name')
    {
        return Permission::orderBy($sort)->get();
    }

    /**
     * @param $input
     * @return Permission
     * @throws GeneralException
     */
    public function create($input)
    {
        try {
            $permission = $this->createStub($input);

            if ($permission->save()) {
                return $permission;
            }
        } catch (\Exception $e) {
            // intentionally left unhandled
        }

        throw new GeneralException("This permission could not be created. Please try again.");
    }

    /**
     * @param $input
     * @return Permission
     */
    protected function createStub($input)
    {
        $permission = new Permission();

        $permission->fill($input);

        return $permission;
    }

    /**
     * @param $id
     * @param $input
     * @return Permission
     * @throws GeneralException
     */
    public function update($id, $input)
    {
        try {
            $permission = $this->findOrThrowException($id);
            $permission->fill($input);

            $permission->save();

            return $permission;
        } catch (\Exception $e) {
            throw new GeneralException("Cannot update this user. Please try again.");
        }
    }

    /**
     * @param $id
     * @return void
     */
    public function delete($id)
    {
        $permission = $this->findOrThrowException($id);

        $permission->delete();
    }
}
