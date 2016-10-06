<?php

namespace App\TTC\Models\Survey\Entity\Question;

/**
 * App\TTC\Models\Survey\Entity\Question\Open
 *
 * @property integer $id
 * @property string $question
 * @property string $description
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity\Question\Open whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity\Question\Open whereQuestion($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity\Question\Open whereDescription($value)
 * @property boolean $required
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity\Question\Open whereRequired($value)
 */
class Open extends BaseQuestion
{
    protected $table = 'survey_entity_q_open';

    public $presentView = 'question.open';

    /**
     * @param $survey
     * @return \Illuminate\View\View
     */
    public function renderCreateForm($survey)
    {
        return view('ttc.backend.surveys.entities.questions.open.create', [
            'entity' => $this,
            'entities' => $survey->getPresentableEntities()
        ]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function renderEditForm()
    {
        return view('ttc.backend.surveys.entities.questions.open.edit', [
            'entity' => $this
        ]);
    }

    /**
     * @return array
     */
    public function getValidationRules()
    {
        $rules = [];

        if ($this->required) {
            $rules['answer'] = 'required';
        }

        return $rules;
    }
}
