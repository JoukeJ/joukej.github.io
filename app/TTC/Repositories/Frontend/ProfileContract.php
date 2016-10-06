<?php

namespace App\TTC\Repositories\Frontend;

use App\Exceptions\GeneralException;
use App\TTC\Models\Profile;

/**
 * Interface ProfileContract
 * @package App\TTC\Repositories\Frontend
 */
interface ProfileContract
{

    /**
     * @param $id
     * @throws GeneralException
     * @return Profile
     */
    public function findOrThrowException($id);

    /**
     * @param $identifier
     * @throws GeneralException
     * @return Profile
     */
    public function findByIdentifierOrThrowException($identifier);

    /**
     * @param $identifier
     * @throws GeneralException
     * @return Profile
     */
    public function findByPhonenumber($telephone);

    /**
     * @param $input
     * @return Profile
     */
    public function create($input);

    /**
     * @param $id
     * @param $input
     * @return Profile
     */
    public function update($id, $input);
}
