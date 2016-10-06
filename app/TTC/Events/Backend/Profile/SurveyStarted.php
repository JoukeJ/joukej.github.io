<?php

namespace App\TTC\Events\Backend\Profile;


use App\TTC\Models\Profile;
use App\TTC\Models\Survey;

/**
 * Class SurveyResponded
 * @package App\TTC\Events\Backend\Profile
 */
class SurveyStarted
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
    public $name = 'survey-start';

    /**
     * SurveyResponded constructor.
     * @param $survey
     * @param $profile
     */
    public function __construct(Survey $survey, Profile $profile)
    {
        $this->survey = $survey;
        $this->profile = $profile;
    }
}
