<?php namespace App\TTC\Listeners\Backend\Survey;

use App\TTC\Events\Backend\Survey\SurveyWasUpdatedEvent;
use App\TTC\Repositories\Backend\SurveyContract;
use Illuminate\Support\Arr;

class UpdateRepeatingSurvey
{
    /**
     * @var SurveyContract
     */
    private $surveys;

    /**
     * UpdateRepeatingSurvey constructor.
     * @param SurveyContract $surveys
     */
    public function __construct(SurveyContract $surveys)
    {
        $this->surveys = $surveys;
    }

    /**
     * @param SurveyWasUpdatedEvent $event
     */
    public function handle(SurveyWasUpdatedEvent $event)
    {
        $input = Arr::get($event->input, 'repeat');

        if (empty(Arr::get($input, 'interval')) !== true) {
            $this->surveys->setRepeat($event->survey->id, $input);
        } else {
            $this->surveys->unsetRepeat($event->survey->id);
        }
    }
}
