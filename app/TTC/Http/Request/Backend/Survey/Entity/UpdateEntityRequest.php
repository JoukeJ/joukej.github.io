<?php

namespace App\TTC\Http\Request\Backend\Survey\Entity;

use App\TTC\Factories\EntityFactory;

class UpdateEntityRequest extends EntityRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // @todo check if survey belongs to user or if user can edit other surveys
        return \Auth::user()->may('management.survey.update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $entity = EntityFactory::make(\Input::get('type'));

        return $entity->getUpdateRules();
    }
}
