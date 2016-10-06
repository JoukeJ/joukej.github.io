<?php

namespace App\TTC\Models;

use App\Models\User;
use App\TTC\Models\Survey\Entity;
use App\TTC\Observer\LogObserver;
use App\TTC\Statistic\SurveyCollection;
use App\TTC\Tags\Entity\CanBePresented;
use Carbon\Carbon;

/**
 * App\TTC\Models\Survey
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $language
 * @property string $name
 * @property boolean $priority
 * @property string $status
 * @property string $start_date
 * @property string $end_date
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TTC\Models\Survey\Matchgroup[] $matchgroups
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey whereLanguage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey wherePriority($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey whereStartDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey whereEndDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TTC\Models\Survey\Entity[] $entities
 * @property-read \App\Models\User $user
 * @property string $identifier
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TTC\Models\Survey\Repeat[] $repeats
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Survey whereIdentifier($value)
 * @property-read \App\TTC\Models\Survey\Repeat $repeat 
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TTC\Models\Profile\Survey[] $profiles 
 */
class Survey extends \Eloquent
{

    protected $table = 'surveys';
    public $timestamps = true;
    protected $fillable = array('user_id', 'language', 'name', 'priority', 'status', 'start_date', 'end_date');
    protected $visible = array(
        'user_id',
        'identifier',
        'language',
        'name',
        'priority',
        'status',
        'start_date',
        'end_date'
    );

    protected $dates = [
        'start_date',
        'end_date',
        'created_at',
        'updated_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function matchgroups()
    {
        return $this->hasMany('App\TTC\Models\Survey\Matchgroup');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entities()
    {
        return $this->hasMany('App\TTC\Models\Survey\Entity');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function repeat()
    {
        return $this->hasOne('App\TTC\Models\Survey\Repeat');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function profiles()
    {
        return $this->hasMany('App\TTC\Models\Profile\Survey');
    }

    /**
     *
     */
    public static function boot()
    {
        parent::boot();

        Survey::observe(new LogObserver());
    }

    /**
     * @return mixed
     */
    public function getPresentableEntities()
    {
        return $this->entities()->orderBy('order')->get()->filter(function (Entity $entity) {
            return $entity->isImplementationOf(CanBePresented::class);
        });
    }

    /**
     * @return mixed
     */
    public function getEntitiesOrdered()
    {
        return $this->entities()->orderBy('order')->get();
    }

    /**
     * @return Entity
     */
    public function getFirstEntity()
    {
        return $this->entities()->orderBy('order')->first();
    }

    /**
     * Returns true if the survey is currently available to be filled
     *
     * @return bool
     */
    public function isActive()
    {
        if($this->status !== 'open') return false;

        $now = Carbon::now();

        if($this->start_date < $now && $now < $this->end_date) return true;
    }
}
