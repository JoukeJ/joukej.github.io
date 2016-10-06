<?php namespace app\Repositories\Asimov\User;

use App\Exceptions\GeneralException;
use App\Models\User;

class UserRepository implements UserContract
{
    /**
     * @param $id
     * @return mixed
     * @throws GeneralException
     */
    public function findOrThrowException($id)
    {
        $user = User::find($id);

        if (is_null($user) === false) {
            return $user;
        }

        throw new GeneralException("Cannot find a user with this id.");
    }

    /**
     * @param $id
     * @param $input
     * @return mixed
     * @throws GeneralException
     */
    public function updateProfile($id, $input)
    {
        $user = $this->findOrThrowException($id);

        $user->first_name = $input['first_name'];
        $user->last_name = $input['last_name'];

        if ($user->save() === false) {
            throw new GeneralException("Your profile could not be saved. Please try again.");
        }

        return $user;
    }

    /**
     * @param $input
     * @return mixed
     */
    public function changePassword($input)
    {
        // TODO: Implement changePassword() method.
    }
}
