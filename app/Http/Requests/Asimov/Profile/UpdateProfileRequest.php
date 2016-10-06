<?php namespace App\Http\Requests\Asimov\Profile;

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
        return $this->guard->user()->may('frontend.profile.update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->guard->user()->id;

        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id,
        ];
    }

}
