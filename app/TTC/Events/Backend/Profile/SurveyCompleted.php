<?php

namespace App\TTC\Events\Backend\Profile;


use App\TTC\Models\Profile;
use App\TTC\Models\Survey;

/**
 * Class SurveyCompleted
 * @package app\TTC\Events\Backend\Profile
 */
class SurveyCompleted
{
    /**
     * @var
     */
    public $survey;

    /**
     * @var
     */
    public $profile;

    /**
     * @var string
     */
    public $name = 'survey-complete';

    /**
     * SurveyCompleted constructor.
     * @param $survey
     * @param $profile
     */
    public function __construct(Survey $survey, Profile $profile)
    {
        $this->survey = $survey;
        $this->profile = $profile;
    }
}
