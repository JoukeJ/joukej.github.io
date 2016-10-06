<?php

namespace App\TTC\Models\Survey;

use App\TTC\Observer\LogObserver;

/**
 * App\TTC\Models\Survey\Matchgroup
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $survey_id
 * @property string $name
 * @property-read \App\TTC\Models\Survey $survey
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TTC\Models\Survey\Matchrule[] $rules
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Matchgroup whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Matchgroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Matchgroup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Matchgroup whereSurveyId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Matchgroup whereName($value)
 */
class Matchgroup extends \Eloquent
{

    protected $table = 'survey_matchgroups';
    public $timestamps = true;
    protected $fillable = array('survey_id', 'name');
    protected $visible = array('survey_id', 'name');

    public function survey()
    {
        return $this->belongsTo('App\TTC\Models\Survey');
    }

    public function rules()
    {
        return $this->hasMany('App\TTC\Models\Survey\Matchrule', 'matchgroup_id');
    }

    public static function boot()
    {
        parent::boot();

        Matchgroup::observe(new LogObserver());
    }

    /**
     * @return string
     */
    public function getShortDescription()
    {
        $string = [];
        foreach ($this->rules as $rule) {
            $str = $rule->getShortString();
            if ($str) {
                $string[] = $str;
            }
        }

        if(sizeof($string) === 0){
            $string[] = '-';
        }

        return ucfirst(implode(', ', $string));
    }
}
