<?php namespace App\Repositories\Asimov\Management\Roles;

use App\Exceptions\GeneralException;
use App\Models\Role;

interface RoleContract
{

    /**
     * @return Role[]
     */
    public function getAllRoles();

    /**
     * @param $id
     * @param $withPermissions
     * @return Role
     * @throws GeneralException
     */
    public function findOrThrowException($id, $withPermissions = false);

    /**
     * @param $input
     * @param $permissions
     * @return Role
     * @throws GeneralException
     */
    public function create($input, $permissions);

    /**
     * @param $id
     * @param $input
     * @param $permissions
     * @return Role
     * @throws GeneralException
     */
    public function update($id, $input, $permissions);

    /**
     * @param $id
     * @return bool
     * @throws GeneralException
     */
    public function delete($id);
}
