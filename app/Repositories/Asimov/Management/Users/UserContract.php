<?php namespace App\Repositories\Asimov\Management\Users;

use App\Models\User;

interface UserContract
{

    /**
     * @param $id
     * @param bool $withRoles
     * @return User
     */
    public function findOrThrowException($id, $withRoles = false);

    /**
     * @param $per_page
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getUsersPaginated($per_page, $order_by = 'id', $sort = 'asc');

    /**
     * @param $per_page
     * @return \Illuminate\Pagination\Paginator
     */
    public function getDeletedUsersPaginated($per_page);

    /**
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getAllUsers($order_by = 'id', $sort = 'asc');

    /**
     * @param $input
     * @param $roles
     * @return mixed
     */
    public function create($input, $roles);

    /**
     * @param $id
     * @param $input
     * @param $roles
     * @return mixed
     */
    public function update($id, $input, $roles, $permissions);

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id);

    /**
     * @param $id
     * @return mixed
     */
    public function restore($id);

    /**
     * @param $id
     * @param $status
     * @return mixed
     */
    public function mark($id, $status);

    /**
     * @param $id
     * @param $input
     * @return mixed
     */
    public function updatePassword($id, $input);

    /**
     * @param $id
     * @param $email
     * @return bool
     */
    public function changeEmail($id, $email);
}
