<?php

namespace App\TTC\Http\Request\Backend\Survey\Matchgroup;

use App\Http\Requests\Request;

class DeleteMatchgroupRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->guard->user()->may('management.survey.delete');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
