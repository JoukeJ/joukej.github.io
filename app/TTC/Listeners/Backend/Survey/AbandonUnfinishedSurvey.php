<?php /* created by Rob van Bentem, 7/3/2015 */

namespace App\TTC\Listeners\Backend\Survey;

use App\TTC\Events\Backend\Survey\SurveyWasClosedEvent;
use App\TTC\Jobs\Backend\Survey\AbandonAnswersJob;
use Illuminate\Foundation\Bus\DispatchesJobs;

class AbandonUnfinishedSurvey
{
    use DispatchesJobs;

    /**
     * @param SurveyWasClosedEvent $event
     */
    public function handle(SurveyWasClosedEvent $event)
    {
        $this->dispatch(app(AbandonAnswersJob::class, [$event->survey]));
    }
}
