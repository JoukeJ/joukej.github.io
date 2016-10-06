<?php

namespace App\TTC\Models\Survey\Entity\Question;

use App\TTC\Chain\Payload\PostPayload;
use App\TTC\Chain\Response\AnswerResponse;use App\TTC\Chain\Response\EndOfChain\ErrorResponse;
use App\TTC\Tags\Entity\CanSkipLogic;
use App\TTC\Tags\Entity\RequiresOptions;

/**
 * App\TTC\Models\Survey\Entity\Question\Radio
 *
 * @property integer $id
 * @property string $question
 * @property string $description
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity\Question\Radio whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity\Question\Radio whereQuestion($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity\Question\Radio whereDescription($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TTC\Models\Survey\Entity\Option[] $options
 * @property boolean $required
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity\Question\Radio whereRequired($value)
 */
class Radio extends BaseQuestion implements RequiresOptions, CanSkipLogic
{
    public $mustHaveOptions = true;

    protected $table = 'survey_entity_q_radio';

    public $presentView = 'question.radio';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function options()
    {
        return $this->morphMany('App\TTC\Models\Survey\Entity\Option', 'entity');
    }

    /**
     * @param $survey
     * @return \Illuminate\View\View
     */
    public function renderCreateForm($survey)
    {
        return view('ttc.backend.surveys.entities.questions.radio.create', [
            'entity' => $this,
            'entities' => $survey->getPresentableEntities()
        ]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function renderEditForm()
    {
        return view('ttc.backend.surveys.entities.questions.radio.edit', [
            'entity' => $this,
            'survey' => $this->baseEntity->survey
        ]);
    }

    /**
     * @return array
     */
    public function getStoreRules()
    {
        $rules = parent::getUpdateRules();

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
     * @param PostPayload|\App\TTC\Models\Survey\Entity\Question\PostPayload $payload
     * @return AnswerResponse
     */
    public function answer(PostPayload $payload)
    {
        $validator = $this->getValidator(['answer' => $payload->input]);
        if ($validator->fails()) {
            return new ErrorResponse($validator->messages()->toArray());
        }

        if((int)$payload->input !== 0) {
            $option = $this->options()->whereId($payload->input)->first();
            $jsonAnswer = json_encode($option->value);
        } else {
            $option = null;
            $jsonAnswer = json_encode($payload->input);
        }

        $answerResponse = new AnswerResponse($this, $jsonAnswer, $option);

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
        $rules[] = 'exists:survey_entity_options,id,entity_id,' . $this->id;

        return ['answer' => implode('|', $rules)];
    }
}
