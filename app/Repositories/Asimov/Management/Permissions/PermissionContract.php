<?php namespace App\Repositories\Asimov\Management\Permissions;

use App\Exceptions\GeneralException;
use App\Models\Permission;

interface PermissionContract
{
    /**
     * @param string $sort
     * @return \App\Models\Permission[]
     */
    public function getAllPermissions($sort = 'display_name');

    /**
     * @param $input
     * @return Permission
     */
    public function create($input);

    /**
     * @param $id
     * @return Permission
     * @throws GeneralException
     */
    public function findOrThrowException($id);

    /**
     * @param $id
     * @param $input
     * @return Permission
     */
    public function update($id, $input);

    /**
     * @param $id
     * @return void
     */
    public function delete($id);
}
