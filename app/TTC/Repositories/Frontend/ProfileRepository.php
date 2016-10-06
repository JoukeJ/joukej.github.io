<?php namespace App\TTC\Repositories\Frontend;

use App\Exceptions\GeneralException;
use App\TTC\Models\Profile;

class ProfileRepository implements ProfileContract
{
    /**
     * @param $id
     * @throws GeneralException
     * @return Profile
     */
    public function findOrThrowException($id)
    {
        try {
            $profile = Profile::findOrFail($id);

            if (is_null($profile)) {
                throw new GeneralException("Cannot find a profile with id ($id)");
            }

            return $profile;

        } catch (\Exception $e) {
            throw new GeneralException("Something went wrong");
        }
    }

    /**
     * @param $identifier
     * @throws GeneralException
     * @return Profile
     */
    public function findByIdentifierOrThrowException($identifier)
    {
        $profile = Profile::whereIdentifier($identifier)->first();

        if (is_null($profile)) {
            throw new GeneralException("Cannot find a profile with this identifier ($identifier).");
        }

        return $profile;
    }

    /**
     * @param $input
     * @return Profile
     * @throws GeneralException
     */
    public function create($input)
    {
        try {
            $data = array_merge($input, ['update_flag' => 1]);
            $profile = Profile::create($data);

            return $profile;
        } catch (\Exception $e) {
            throw new GeneralException("Cannot create profile " . $e->getMessage());
        }
    }

    /**
     * @param $identifier
     * @param $input
     * @return Profile
     * @throws GeneralException
     * @internal param $id
     */
    public function update($identifier, $input)
    {
        $profile = $this->findByIdentifierOrThrowException($identifier);

        try {
            $profile->fill($input);
            if ((int)array_get($input, 'update_flag', 1) !== 0) {
                $profile->update_flag = 1;
            }

            $profile->save();

            return $profile;
        } catch (\Exception $e) {
            throw new GeneralException("Cannot update profile");
        }
    }

    public function findByPhonenumber($telephone)
    {
        $profile = Profile::wherePhonenumber($telephone)->first();

        if (is_null($profile)) {
            throw new GeneralException("Cannot find a profile with this telephone ($telephone).");
        }

        return $profile;
    }
}
