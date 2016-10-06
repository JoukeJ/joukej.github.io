<?php
/**
 * Created by Luuk Holleman
 * Date: 24/06/15
 */

namespace App\TTC\Models\Profile;

use App\TTC\Models\Profile;
use App\TTC\Models\Survey\Entity;
use App\TTC\Statistic\Collection\ProfileSurveyCollection;


/**
 * App\TTC\Models\Profile\Survey
 *
 * @property integer $id
 * @property integer $profile_id
 * @property integer $survey_id
 * @property integer $entity_id
 * @property string $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\TTC\Models\Profile $profile
 * @property-read \App\TTC\Models\Survey $survey
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Profile\Survey whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Profile\Survey whereProfileId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Profile\Survey whereSurveyId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Profile\Survey whereEntityId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Profile\Survey whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Profile\Survey whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Profile\Survey whereUpdatedAt($value)
 * @property-read \App\TTC\Models\Survey\Entity $entity
 * @property boolean $previous 
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Profile\Survey wherePrevious($value)
 */
class Survey extends \Eloquent
{
    protected $table = 'profile_surveys';
    public $timestamps = true;
    protected $fillable = array('profile_id', 'survey_id', 'entity_id', 'status');
    protected $visible = array('profile_id', 'survey_id', 'entity_id', 'status');

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Profile
     */
    public function profile()
    {
        return $this->belongsTo('App\TTC\Models\Profile');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|\App\TTC\Models\Survey
     */
    public function survey()
    {
        return $this->belongsTo('App\TTC\Models\Survey');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Entity
     */
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
        return new ProfileSurveyCollection($models);
    }

    /**
     * @return string
     */
    public function getCurrentEntity()
    {
        if ($this->entity_id === null) {
            return $this->survey->getFirstEntity();
        }

        return $this->entity;
    }
}
