<?php
/**
 * Created by Luuk Holleman
 * Date: 01/07/15
 */

namespace App\TTC\Statistic;


use App\TTC\Models\Survey;

/**
 * Class Entity
 * @package App\TTC\Statistic
 */
class Entity
{
    /**
     * @var Survey
     */
    private $survey;
    /**
     * @var Survey\Entity
     */
    private $entity;

    /**
     * @param Survey $survey
     * @param Survey\Entity $entity
     */
    public function __construct(Survey $survey, Survey\Entity $entity)
    {
        $this->survey = $survey;
        $this->entity = $entity;
    }

    private function query()
    {
        return Survey\Answer::whereSurveyId($this->survey->id)->whereEntityId($this->entity->id)->whereExists(function (
            $query
        ) {
            $query->select(\DB::raw(1))
                ->from('profile_surveys')
                ->whereRaw('profile_surveys.id = id')
                ->where('status', '=', 'completed');
        })->get();
    }

    public function countPerAnswer()
    {
        return $this->query()->countPerAnswer();
    }

    public function percentages()
    {
        return $this->query()->percentages();
    }
}
