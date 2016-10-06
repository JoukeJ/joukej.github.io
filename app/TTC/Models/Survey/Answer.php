<?php

namespace App\TTC\Models\Survey;

use App\TTC\Statistic\Collection\SurveyAnswerCollection;

/**
 * App\TTC\Models\Survey\Answer
 *
 * @property integer $id
 * @property integer $profile_id
 * @property integer $survey_id
 * @property integer $entity_id
 * @property string $answer
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\TTC\Models\Profile $profile
 * @property-read \App\TTC\Models\Survey $survey
 * @property-read \App\TTC\Models\Survey\Entity $entity
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Answer whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Answer whereProfileId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Answer whereSurveyId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Answer whereEntityId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Answer whereAnswer($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Answer whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Answer whereUpdatedAt($value)
 * @property integer $profile_survey_id
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Answer whereProfileSurveyId($value)
 */
class Answer extends \Eloquent
{

    protected $table = 'survey_answers';
    public $timestamps = true;
    protected $fillable = array('profile_id', 'survey_id', 'entity_id', 'profile_survey_id', 'answer');
    protected $visible = array('profile_id', 'survey_id', 'entity_id', 'profile_survey_id', 'answer');

    public function profile()
    {
        return $this->belongsTo('App\TTC\Models\Profile');
    }

    public function survey()
    {
        return $this->belongsTo('App\TTC\Models\Survey');
    }

    public function entity()
    {
        return $this->belongsTo('App\TTC\Models\Survey\Entity');
    }


    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        return new SurveyAnswerCollection($models);
    }
}
