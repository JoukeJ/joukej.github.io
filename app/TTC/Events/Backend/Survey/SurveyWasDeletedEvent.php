<?php /* created by Rob van Bentem, 7/3/2015 */

namespace App\TTC\Events\Backend\Survey;

use App\Events\Event;

class SurveyWasDeletedEvent extends Event
{
    /**
     * @var int
     */
    public $surveyId;

    /**
     * SurveyWasDeletedEvent constructor.
     * @param $surveyId
     */
    public function __construct($surveyId)
    {
        $this->surveyId = $surveyId;
    }


}
