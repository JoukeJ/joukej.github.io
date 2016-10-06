<?php namespace App\Http\Requests\Asimov\Management\Roles;

use App\Http\Requests\Request;

class CreateRoleRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::user()->may('management.roles.store');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'display_name' => 'required|string',
            'description' => 'required|string',
            'permissions' => 'required|array'
        ];
    }

}
