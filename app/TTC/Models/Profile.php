<?php

namespace App\TTC\Models;

/**
 * App\TTC\Models\Profile
 *
 * @property integer $id
 * @property integer $language_id
 * @property integer $phonenumber
 * @property string $name
 * @property string $gender
 * @property string $birthday
 * @property string $geo_country
 * @property string $geo_city
 * @property string $geo_lat
 * @property string $geo_lng
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\TTC\Models\Language $language
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Profile whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Profile whereLanguageId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Profile wherePhonenumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Profile whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Profile whereGender($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Profile whereBirthday($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Profile whereGeoCountry($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Profile whereGeoCity($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Profile whereGeoLat($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Profile whereGeoLng($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Profile whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Profile whereUpdatedAt($value)
 * @property string $identifier
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Profile whereIdentifier($value)
 * @property integer $geo_country_id
 * @property string $owner
 * @property string $batch
 * @property-read \App\TTC\Models\Country $country
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Profile whereGeoCountryId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Profile whereOwner($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Profile whereBatch($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|Survey[] $profileSurveys
 * @property string $device 
 * @property boolean $update_flag 
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Profile whereDevice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TTC\Models\Profile whereUpdateFlag($value)
 */
class Profile extends \Eloquent
{

    protected $table = 'profiles';
    public $timestamps = true;
    protected $fillable = array(
        'identifier',
        'language_id',
        'phonenumber',
        'name',
        'gender',
        'birthday',
        'geo_country_id',
        'geo_city',
        'geo_lat',
        'geo_lng',
        'batch',
        'owner',
        'device',
        'update_flag',
    );
    protected $visible = array(
        'phonenumber',
        'name',
        'gender',
        'birthday',
        'geo_country',
        'geo_city',
        'geo_lat',
        'geo_lng',
        'language',
        'batch',
        'owner',
        'device',
    );

    protected $appends = [
        'geo_country',
        'language'
    ];

    protected $hidden = array('language_id', 'timestamps');

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function language()
    {
        return $this->belongsTo('App\TTC\Models\Language', 'language_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo('App\TTC\Models\Country', 'geo_country_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\App\TTC\Models\Profile\Survey[]
     */
    public function profileSurveys()
    {
        return $this->hasMany('App\TTC\Models\Profile\Survey');
    }

    /**
     * @return string
     */
    public function getGeoCountryAttribute()
    {
        return $this->country->iso;
    }

    /**
     * @return string
     */
    public function getLanguageAttribute()
    {
        return $this->language()->first()->iso;
    }

    /**
     * @return bool
     */
    public function hasSurveyInProgress()
    {
        // if count() > 1, then something is wrong
        return $this->profileSurveys()
            ->whereStatus('progress')
            ->count() > 0 ? true : false;
    }

    /**
     * @return Survey
     */
    public function getProfileSurveyInProgress()
    {
        return $this->profileSurveys()
            ->whereStatus('progress')
            ->first();
    }

    /**
     * Returns the full shortened url to this profile (frontend side)
     * @return string
     */
    public function getShortUrl()
    {
        return sprintf(env('SHORT_URL'), $this->identifier);
    }
}
