<?php

namespace App\TTC\Http\Request\Backend\Survey;

use App\Http\Requests\Request;

class UpdateSurveyRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->guard->user()->may('management.survey.update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|max:128',
            'priority' => 'integer|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date'
        ];

        if (\Input::has('repeat.interval') && \Input::get('repeat.interval') !== '') {
            $rules['repeat.absolute_end_date'] = 'required|date';
        }

        return $rules;
    }
}
