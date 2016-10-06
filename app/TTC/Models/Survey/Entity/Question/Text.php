<?php

namespace App\TTC\Models\Survey\Entity\Question;

/**
 * App\TTC\Models\Survey\Entity\Question\Text
 *
 * @property integer $id
 * @property string $question
 * @property string $description
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity\Question\Text whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity\Question\Text whereQuestion($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity\Question\Text whereDescription($value)
 * @property boolean $required
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Entity\Question\Text whereRequired($value)
 */
class Text extends BaseQuestion
{
    protected $table = 'survey_entity_q_text';

    public $presentView = 'question.text';

    /**
     * @param $survey
     * @return \Illuminate\View\View
     */
    public function renderCreateForm($survey)
    {
        return view('ttc.backend.surveys.entities.questions.text.create', [
            'entity' => $this,
            'entities' => $survey->getPresentableEntities()
        ]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function renderEditForm()
    {
        return view('ttc.backend.surveys.entities.questions.text.edit', [
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
            $rules['answer'] = 'required|min:1';
        }

        return $rules;
    }
}
