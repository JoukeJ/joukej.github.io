<?php namespace App\TTC\Listeners\Frontend\Survey;

use App\TTC\Events\Frontend\Survey\SurveyWasCompletedEvent;
use App\TTC\Repositories\Frontend\ProfileSurveyContract;

class SurveyWasCompletedListener
{

    /**
     * @var ProfileSurveyContract
     */
    protected $profileSurveys;

    /**
     * SurveyWasCompletedListener constructor.
     * @param ProfileSurveyContract $profileSurveys
     */
    public function __construct(ProfileSurveyContract $profileSurveys)
    {
        $this->profileSurveys = $profileSurveys;
    }

    /**
     * @param SurveyWasCompletedEvent $event
     */
    public function handle(SurveyWasCompletedEvent $event)
    {
        $this->profileSurveys->update($event->profileSurvey->id, [
            'status' => 'completed',
            'entity_id' => null
        ]);
    }

}
