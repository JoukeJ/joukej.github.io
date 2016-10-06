<?php namespace App\TTC\Jobs\Backend\Survey;

use App\Exceptions\GeneralException;
use App\TTC\Events\Backend\Survey\SurveyWasClosedEvent;
use App\TTC\Models\Survey;
use App\TTC\Repositories\Backend\SurveyContract;
use Illuminate\Contracts\Bus\SelfHandling;

class PermanentlyCloseSurveyJob implements SelfHandling
{

    /**
     * @var int
     */
    private $surveyId;

    /**
     * @var SurveyContract
     */
    private $surveys;

    /**
     * CreateSurveyJob constructor.
     * @param $surveyId
     * @param SurveyContract $surveys
     */
    public function __construct($surveyId, SurveyContract $surveys)
    {
        $this->surveyId = $surveyId;
        $this->surveys = $surveys;
    }

    /**
     * Execute the job.
     * @throws GeneralException
     * @return Survey
     */
    public function handle()
    {
        $resp = $this->surveys->update($this->surveyId, [
            'status' => 'closed'
        ]);

        \Event::fire(new SurveyWasClosedEvent($resp, true));

        return $resp;
    }
}
