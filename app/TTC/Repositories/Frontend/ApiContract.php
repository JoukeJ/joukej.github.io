<?php
/**
 * Created by Luuk Holleman
 * Date: 24/06/15
 */

namespace App\TTC\Repositories\Frontend;


use App\Exceptions\GeneralException;
use App\TTC\Exceptions\Api\ValidationFailedException;
use App\TTC\Models\Country;
use App\TTC\Models\Language;
use App\TTC\Models\Profile;

/**
 * Interface ApiContract
 * @package App\TTC\Repositories\Frontend
 */
interface ApiContract
{
    /**
     * @param $data
     * @param $created
     * @return Profile
     * @throws GeneralException
     * @throws ValidationFailedException
     */
    public function createOrUpdateProfile($data, &$created);

    /**
     * @param $phonenumber
     * @return Profile
     */
    public function findByPhonenumber($phonenumber);

    /**
     * @param $iso
     * @return Language
     */
    public function findLanguageByIso($iso);

    /**
     * @param $iso
     * @return Country
     */
    public function findCountryByIso($iso);

    /**
     * @return Profile[]
     */
    public function getProfilesFlaggedForExport();
}
