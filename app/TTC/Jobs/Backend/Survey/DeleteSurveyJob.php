<?php

namespace App\TTC\Jobs\Backend\Survey;

use App\Exceptions\GeneralException;
use App\Jobs\Job;
use App\TTC\Events\Backend\Survey\SurveyWasDeletedEvent;
use App\TTC\Repositories\Backend\SurveyContract;
use Illuminate\Contracts\Bus\SelfHandling;

class DeleteSurveyJob extends Job implements SelfHandling
{
    /**
     * @var SurveyContract
     */
    private $surveys;

    /**
     * CreateSurveyJob constructor.
     * @param SurveyContract $surveys
     */
    public function __construct(SurveyContract $surveys)
    {
        $this->surveys = $surveys;
    }

    /**
     * @return bool
     * @throws GeneralException
     */
    public function handle()
    {
        $surveyId = \Route::current()->getParameter('surveys');
        $survey = $this->surveys->findOrThrowException($surveyId);

        if ((\Auth::user()->may('management.survey.update') && $survey->user_id == \Auth::user()->id) || \Auth::user()->may('superuser')) {
            $result = $this->surveys->delete($surveyId);

            if ($result === true) {
                \Event::fire(new SurveyWasDeletedEvent($surveyId));
            }

            return $result;
        }

        throw new GeneralException("Not enough permissions");
    }
}
