<?php namespace App\TTC\Events\Frontend\Survey;

use App\Events\Event;
use App\TTC\Models\Profile\Survey;

class SurveyWasCompletedEvent extends Event
{
    /**
     * @var Survey
     */
    public $profileSurvey;

    /**
     * SurveyWasCompletedEvent constructor.
     * @param Survey $profileSurvey
     */
    public function __construct(Survey $profileSurvey)
    {
        $this->profileSurvey = $profileSurvey;
    }


}
