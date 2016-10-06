<?php namespace App\Repositories\Asimov\User;

interface UserContract
{

    /**
     * @param $id
     * @return mixed
     */
    public function findOrThrowException($id);

    /**
     * @param $id
     * @param $input
     * @return mixed
     */
    public function updateProfile($id, $input);

    /**
     * @param $input
     * @return mixed
     */
    public function changePassword($input);
}
