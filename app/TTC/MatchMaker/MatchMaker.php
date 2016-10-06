<?php

namespace App\TTC\MatchMaker;

use App\TTC\Models\Profile;
use App\TTC\Models\Survey;
use App\TTC\Repositories\Backend\SurveyContract;
use Illuminate\Support\Collection;

/**
 * Class MatchMaker
 * @package App\TTC
 */
class MatchMaker
{
    /**
     * @var Profile
     */
    private $profile;

    /**
     * @var \Illuminate\Support\Collection
     */
    private $surveys;

    /**
     * @var SurveyContract
     */
    private $surveyRepository;

    /**
     * @param Profile $profile
     * @param SurveyContract $survey
     */
    public function __construct(Profile $profile, SurveyContract $survey)
    {
        $this->profile = $profile;

        $this->surveys = Collection::make();

        $this->surveyRepository = $survey;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function findSurveys()
    {
        $surveys = $this->surveyRepository->allOpenAndActiveAndEligible($this->profile);

        return $this->matchOnSurveys($surveys);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function matchOnSurveys(Collection $surveys)
    {
        foreach ($surveys as $survey) {
            if ($this->matchAgainstGroups($survey)) {
                $this->surveys->push($survey);
            }
        }

        $this->surveys = $this->surveys->sortByDesc(function ($a) {
            return $a->priority;
        });

        return $this->surveys;
    }

    /**
     * @param Survey $survey
     * @return bool
     */
    private function matchAgainstGroups(Survey $survey)
    {
        $groups = $survey->matchgroups;

        $matched = false;

        foreach ($groups as $group) {
            if ($this->matchAgainstRules($group)) {
                $matched = true;
                break;
            }
        }

        return $matched;
    }

    /**
     * @param Survey\Matchgroup $group
     * @return bool
     */
    private function matchAgainstRules(Survey\Matchgroup $group)
    {
        $rules = $group->rules;

        $matched = 0;

        foreach ($rules as $rule) {

            $json = json_decode($rule->values, true);

            if (sizeof($json) === 1) {
                if (empty($json[0])) {
                    continue;
                }
            } elseif (sizeof($json) === 2) {
                if (empty($json[0]) && empty($json[1])) {
                    continue;
                }
            }

            $matched++;

            $attribute = \App::make(ucfirst($rule->attribute), [$this->profile, $rule]);

            if (!$attribute->match()) {
                return false;
            }
        }

        return $matched > 0;
    }
}
