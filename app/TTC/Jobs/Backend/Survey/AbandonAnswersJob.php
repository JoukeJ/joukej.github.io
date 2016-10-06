<?php
/**
 * Created by Luuk Holleman
 * Date: 01/07/15
 */

namespace App\TTC\Jobs\Backend\Survey;


use App\Jobs\Job;
use App\TTC\Models\Survey;
use Illuminate\Contracts\Bus\SelfHandling;

class AbandonAnswersJob extends Job implements SelfHandling
{
    /**
     * @var int
     */
    private $survey;

    /**
     * CreateSurveyJob constructor.
     * @param Survey $survey
     */
    public function __construct(Survey $survey)
    {
        $this->survey = $survey;
    }

    /**
     * Execute the job.
     * @return Survey
     */
    public function handle()
    {
        $surveyProfiles = $this->survey->profiles()->whereStatus('progress')->get();

        foreach ($surveyProfiles as $surveyProfile) {
            $surveyProfile->status = 'abandoned';
            $surveyProfile->previous = 1;

            $surveyProfile->save();
        }
    }
}
