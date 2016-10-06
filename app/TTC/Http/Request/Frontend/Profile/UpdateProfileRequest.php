<?php

namespace App\TTC\Http\Request\Frontend\Profile;

use App\Http\Requests\Request;

class UpdateProfileRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $genders = config('ttc.profile.genders');

        return [
            'name' => 'required',
            'day' => 'required|integer',
            'month' => 'required|integer',
            'year' => 'required|integer',
            'gender' => 'required|in:' . implode(',', $genders),
            'geo_country_id' => 'required',
        ];
    }
}
