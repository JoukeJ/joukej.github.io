<?php
/**
 * Created by Luuk Holleman
 * Date: 01/07/15
 */

namespace App\TTC\Jobs\Backend\Survey;


use App\Exceptions\GeneralException;
use App\Jobs\Job;
use App\TTC\Models\Survey;
use Carbon\Carbon;
use Illuminate\Contracts\Bus\SelfHandling;

class ReopenRepeatingSurveyJob extends Job implements SelfHandling
{
    /**
     * @var Survey
     */
    private $survey;

    /**
     * @param Survey $survey
     */
    public function __construct(Survey $survey)
    {
        $this->survey = $survey;
    }

    /**
     * See if this survey is a repeating survey, and if so, increase start and end date with $repeat->intverval.
     * @todo keep counter of the number of repeats this survey repeat has.
     */
    public function handle()
    {
        if (is_null($repeat = $this->survey->repeat) === false) {
            if ($repeat->absolute_end_date > $this->survey->end_date) {
                $startDate = Carbon::createFromTimestamp(strtotime($this->survey->start_date));
                $endDate = Carbon::createFromTimestamp(strtotime($this->survey->end_date));

                if ($repeat->interval === 'week') {
                    $startDate->addWeek();
                    $endDate->addWeek();
                } else {
                    throw new GeneralException(sprintf("Unknown interval '%s'.", $repeat->interval));
                }

                if ($endDate > $repeat->absolute_end_date) {
                    $endDate = $repeat->absolute_end_date;
                }

                $this->survey->start_date = $startDate;
                $this->survey->end_date = $endDate;

                $this->survey->status = 'open';
                $this->survey->save();
            }
        }
    }
}
