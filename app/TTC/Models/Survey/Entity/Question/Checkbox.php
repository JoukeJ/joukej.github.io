<?php

namespace App\TTC\Models\Survey\Entity\Question;

use App\TTC\Chain\Payload\PostPayload;
use App\TTC\Chain\Response\AnswerResponse;
use App\TTC\Chain\Response\EndOfChain\ErrorResponse;
use App\TTC\Tags\Entity\RequiresOptions;

/**
 * App\TTC\Models\Survey\Entity\Question\Checkbox
 *
 * @property integer $id
 * @property string $question
 * @property string $description
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity\Question\Checkbox whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity\Question\Checkbox whereQuestion($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity\Question\Checkbox whereDescription($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TTC\Models\Survey\Entity\Option[] $options
 * @property boolean $required
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity\Question\Checkbox whereRequired($value)
 */
class Checkbox extends BaseQuestion implements RequiresOptions
{
    public $mustHaveOptions = true;

    public $presentView = 'question.checkbox';

    protected $table = 'survey_entity_q_checkbox';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function options()
    {
        return $this->morphMany('App\TTC\Models\Survey\Entity\Option', 'entity');
    }

    /**
     * @return array
     */
    public function getStoreRules()
    {
        $rules = parent::getStoreRules();

        $rules['type_options'] = 'required|array|array_values_filled';

        return $rules;
    }

    /**
     * @return array
     */
    public function getUpdateRules()
    {
        $rules = parent::getUpdateRules();

        $rules['type_options'] = 'required|array|array_values_filled';

        return $rules;
    }

    /**
     * @param $survey
     * @return \Illuminate\View\View
     */
    public function renderCreateForm($survey)
    {
        return view('ttc.backend.surveys.entities.questions.checkbox.create', [
            'entity' => $this,
            'entities' => $survey->getPresentableEntities()
        ]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function renderEditForm()
    {
        return view('ttc.backend.surveys.entities.questions.checkbox.edit', [
            'entity' => $this
        ]);
    }

    /**
     * @param PostPayload $payload
     * @return AnswerResponse
     */
    public function answer(PostPayload $payload)
    {
        $validator = $this->getValidator(['answer' => $payload->input]);

        if ($validator->fails()) {
            return new ErrorResponse($validator->messages()->toArray());
        }

        $options = $this->options()->whereIn('id', $payload->input)->get();

        $values = [];
        foreach ($options as $option) {
            $values[] = $option->value;
        }

        $jsonAnswer = json_encode($values);
        $answerResponse = new AnswerResponse($this, $jsonAnswer);

        return $answerResponse;
    }

    /**
     * @return array
     */
    public function getValidationRules()
    {
        $rules = [];

        if ($this->required) {
            $rules[] = 'required';
        }

        $rules[] = 'checkbox_options_belong_to_entity:' . $this->id;

        return [
            'answer' => implode("|", $rules)
        ];
    }
}
