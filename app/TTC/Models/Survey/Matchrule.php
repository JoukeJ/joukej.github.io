<?php

namespace App\TTC\Models\Survey;

use App\Exceptions\GeneralException;
use App\TTC\Observer\LogObserver;

/**
 * App\TTC\Models\Survey\Matchrule
 *
 * @property integer $id
 * @property integer $matchgroup_id
 * @property boolean $group
 * @property string $attribute
 * @property string $operator
 * @property string $values
 * @property-read \App\TTC\Models\Survey\Matchgroup $matchgroup
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Matchrule whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Matchrule whereMatchgroupId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Matchrule whereGroup($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Matchrule whereAttribute($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Matchrule whereOperator($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Matchrule whereValues($value)
 * @property-read mixed $short
 */
class Matchrule extends \Eloquent
{

    protected $table = 'survey_matchrules';
    public $timestamps = false;
    protected $fillable = array('matchgroup_id', 'group', 'attribute', 'operator', 'values');
    protected $visible = array('matchgroup_id', 'group', 'attribute', 'operator', 'values');

    public function matchgroup()
    {
        return $this->belongsTo('App\TTC\Models\Survey\Matchgroup', 'matchgroup_id');
    }

    public static function boot()
    {
        parent::boot();

        Matchrule::observe(new LogObserver());
    }

    /**
     * @return mixed
     */
    public function getShortAttribute()
    {
        $attr = array_flip(config('ttc.survey.matchgroups.attributes'));

        return $attr[$this->attribute];
    }

    /**
     * @return array
     * @throws GeneralException
     */
    public function getParsedValues()
    {
        if ($json = \GuzzleHttp\json_decode($this->values, true)) {
            return $json;
        }

        throw new GeneralException("Cannot parse values.");
    }

    /**
     * Returns a short string describing this rule
     * @return string
     */
    public function getShortString()
    {
        $values = $this->getParsedValues();

        if (empty($values[0])) {
            return null;
        }

        $attribute = app($this->attribute);

        return $attribute->formatRuleString(app($this->operator), $values);
    }
}
