<?php
/**
 * Created by Luuk Holleman
 * Date: 30/06/15
 */

namespace App\TTC\Statistic;

use App\TTC\Models\Profile\Survey;
use App\TTC\Models\Survey\Entity;
use App\TTC\Statistic\Entity as StatisticEntity;

/**
 * Class Statistic
 * @package App\TTC\Statistic
 */
class Statistic
{
    /**
     * @var
     */
    private $survey;

    /**
     * @param $survey
     */
    public function __construct($survey)
    {
        $this->survey = $survey;
    }

    public function totalParticipants()
    {
        return Survey::whereSurveyId($this->survey->id)->whereStatus('completed')->get()->count();
    }

    public function totalUniqueParticipants()
    {
        return Survey::whereSurveyId($this->survey->id)->whereStatus('completed')->get()->uniqueCount('profile_id');
    }

    public function totalAbandoned()
    {
        return Survey::whereSurveyId($this->survey->id)->whereStatus('abandoned')->get()->count();
    }

    public function totalUniqueAbandoned()
    {
        return Survey::whereSurveyId($this->survey->id)->whereStatus('abandoned')->get()->uniqueCount('profile_id');
    }

    public function totalInProgress()
    {
        return Survey::whereSurveyId($this->survey->id)->whereStatus('progress')->get()->uniqueCount('profile_id');
    }

    public function getEntity(Entity $entity)
    {
        return new StatisticEntity($this->survey, $entity);
    }
}
