<?php namespace App\TTC\Events\Backend\Survey;

use App\Events\Event;
use App\TTC\Models\Survey;

class SurveyWasUpdatedEvent extends Event
{

    /**
     * @var Survey
     */
    public $survey;

    /**
     * @var array
     */
    public $input;

    /**
     * SurveyWasUpdatedEvent constructor.
     * @param Survey $survey
     * @param array $input
     */
    public function __construct(Survey $survey, array $input)
    {
        $this->survey = $survey;
        $this->input = $input;
    }
}
