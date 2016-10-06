<?php

namespace App\TTC\MatchMaker\Attribute;


use App\TTC\MatchMaker\Operator\Equals;
use App\TTC\MatchMaker\Operator\NotEquals;
use App\TTC\MatchMaker\Operator\Operator;

class Survey extends Attribute
{
    /**
     * @var array
     */
    public $operators = [
        Equals::class,
        NotEquals::class
    ];

    public function match()
    {
        $operator = app($this->rule->operator);

        $surveyId = json_decode($this->rule->values, true)[0];

        $profileSurvey = \App\TTC\Models\Profile\Survey::where('profile_id', $this->profile->id)
            ->where('survey_id', $surveyId)
            ->first();

        if($operator instanceof Equals){
            if($profileSurvey == null){
                return false;
            }

            return $profileSurvey->status === 'completed';
        } elseif ($operator instanceof NotEquals){
            if($profileSurvey == null){
                return true;
            }

            return $profileSurvey->status !== 'completed';
        }

        return false;
    }

    public function formatRuleString(Operator $operator, array $values)
    {
        if ($operator instanceof Equals) {
            return sprintf("participated in '%s'", $this->getSurveyNameById($values[0]));
        } elseif ($operator instanceof NotEquals) {
            return sprintf("not participated in '%s'", $this->getSurveyNameById($values[0]));
        }

        return null;
    }

    /**
     * @param $countryId
     * @return null|string
     */
    private function getSurveyNameById($countryId)
    {
        $survey = \App\TTC\Models\Survey::whereId($countryId)->first();

        if (is_null($survey)) {
            return null;
        }

        return $survey->name;
    }

    public function getUrlQuery()
    {

    }
}
