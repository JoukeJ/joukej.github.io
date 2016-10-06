<?php /* created by Rob van Bentem, 7/3/2015 */

namespace App\TTC\Listeners\Backend\Survey;

use App\Exceptions\GeneralException;
use App\TTC\Events\Backend\Survey\SurveyWasClosedEvent;
use App\TTC\Jobs\Backend\Survey\ReopenRepeatingSurveyJob;
use Illuminate\Foundation\Bus\DispatchesJobs;

class ReopenRepeatingSurvey
{
    use DispatchesJobs;

    /**
     * @param SurveyWasClosedEvent $event
     * @throws GeneralException
     */
    public function handle(SurveyWasClosedEvent $event)
    {
        $this->dispatch(app(ReopenRepeatingSurveyJob::class, [$event->survey]));
    }
}
