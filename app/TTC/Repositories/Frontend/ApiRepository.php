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
use Illuminate\Support\Arr;

/**
 * Class ApiRepository
 * @package App\TTC\Repositories\Frontend
 */
class ApiRepository implements ApiContract
{
    /**
     * @param $phonenumber
     * @return Profile
     */
    public function findByPhonenumber($phonenumber)
    {
        return Profile::wherePhonenumber($phonenumber)->firstOrFail();
    }

    /**
     * @param $data
     * @param $created
     * @return Profile
     * @throws GeneralException
     * @throws ValidationFailedException
     */
    public function createOrUpdateProfile($data, &$created)
    {
        $profile = Profile::firstOrNew([
            'phonenumber' => Arr::get($data, 'phonenumber')
        ]);

        if ($profile->id == null) { // new
            $profile->identifier = str_random(8);

            $created = true;
        }

        $validator = \Validator::make($data, [
            'phonenumber' => 'required',
            'gender' => 'in:male,female',
            'birthday' => 'date'
        ]);

        if ($validator->passes()) {

            $profile->language_id = $this->findLanguageByIso(Arr::get($data, 'language'))->id;
            $profile->geo_country_id = $this->findCountryByIso(Arr::get($data, 'geo_country'))->id;

            $profile->fill(array_only($data, [
                'name',
                'gender',
                'geo_city',
                'geo_lat',
                'geo_lng',
                'batch',
                'owner'
            ]));

            if (\Input::get('birtyday', '') != '') {
                $profile->birthday = Arr::get($data, 'birthday');
            }

            try {
                $profile->save();

                return $profile;
            } catch (\Exception $e) {
                throw new GeneralException("Something unknown went wrong " . $e->getMessage());
            }
        } else {
            throw new ValidationFailedException("Validation failed: " . $validator->getMessageBag());
        }
    }

    /**
     * @param $iso
     * @return Language
     */
    public function findLanguageByIso($iso)
    {
        return Language::whereIso($iso)->firstOrFail();
    }

    /**
     * @param $iso
     * @return Country
     */
    public function findCountryByIso($iso)
    {
        return Country::whereIso($iso)->firstOrFail();
    }


    /**
     * @return Profile[]
     */
    public function getProfilesFlaggedForExport()
    {
        return Profile::where('update_flag', '=', 1)->get();
    }
}
