<?php
/**
 * Created by Luuk Holleman
 * Date: 01/07/15
 */

namespace App\TTC\Events\Backend\Survey;


use App\TTC\Models\Survey;

/**
 * Class SurveyWasClosedEvent
 * @package App\TTC\Events\Backend\Survey
 */
class SurveyWasClosedEvent extends \Event
{
    /**
     * @var Survey
     */
    public $survey;

    /**
     * @var bool
     */
    public $permanent;

    /**
     * @param Survey $survey
     * @param bool $permanent
     */
    public function __construct(Survey $survey, $permanent = false)
    {
        $this->survey = $survey;
        $this->permanent = $permanent;
    }
}
