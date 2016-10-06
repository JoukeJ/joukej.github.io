<?php

namespace App\TTC\Models\Survey;

use App\TTC\Observer\LogObserver;

/**
 * App\TTC\Models\Survey\Repeat
 *
 * @property integer $id
 * @property integer $survey_id
 * @property string $interval
 * @property string $absolute_end_date
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\TTC\Models\Survey $survey
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Repeat whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Repeat whereSurveyId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Repeat whereInterval($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Repeat whereAbsoluteEndDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Repeat whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey\Repeat whereUpdatedAt($value)
 */
class Repeat extends \Eloquent
{

    protected $table = 'survey_repeats';
    public $timestamps = true;
    protected $fillable = array('survey_id', 'interval', 'absolute_end_date');
    protected $visible = array('survey_id', 'interval', 'absolute_end_date');

    public function survey()
    {
        return $this->belongsTo('App\TTC\Models\Survey');
    }

    public static function boot()
    {
        parent::boot();

        Repeat::observe(new LogObserver());
    }

}
