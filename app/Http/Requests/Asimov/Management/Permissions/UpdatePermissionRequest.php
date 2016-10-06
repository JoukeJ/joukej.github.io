<?php namespace App\Http\Requests\Asimov\Management\Permissions;

use App\Http\Requests\Request;

class UpdatePermissionRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::user()->may('management.permissions.update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required',
            'name' => 'required|string',
            'display_name' => 'required|string',
            'description' => 'required|string'
        ];
    }

}
