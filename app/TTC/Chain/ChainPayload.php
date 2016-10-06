<?php namespace App\TTC\Chain;

use App\TTC\Models\Profile;
use App\TTC\Models\Survey;
use App\TTC\Models\Survey\Entity;

abstract class ChainPayload
{
    /**
     * @var Profile
     */
    protected $profile;

    /**
     * @var Survey
     */
    protected $survey;

    /**
     * @var Entity
     */
    protected $entity;

    /**
     * GetPayload constructor.
     * @param Profile $profile
     * @param Survey $survey
     * @param Entity $entity
     */
    public function __construct(Profile $profile, Survey $survey, Entity $entity)
    {
        $this->profile = $profile;
        $this->survey = $survey;
        $this->entity = $entity;
    }

    /**
     * @return Profile
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @return Survey
     */
    public function getSurvey()
    {
        return $this->survey;
    }

    /**
     * @return Entity
     */
    public function getEntity()
    {
        return $this->entity;
    }
}
