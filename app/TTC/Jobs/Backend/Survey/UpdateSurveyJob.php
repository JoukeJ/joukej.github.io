<?php

namespace App\TTC\Jobs\Backend\Survey;

use App\Exceptions\GeneralException;
use App\Jobs\Job;
use App\TTC\Events\Backend\Survey\SurveyWasUpdatedEvent;
use App\TTC\Models\Survey;
use App\TTC\Repositories\Backend\SurveyContract;
use Illuminate\Contracts\Bus\SelfHandling;

class UpdateSurveyJob extends Job implements SelfHandling
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
     * Execute the job.
     *
     * @throws GeneralException
     * @return Survey
     * @todo no direct access to \Input
     */
    public function handle()
    {
        $survey = $this->surveys->findOrThrowException(\Input::get('id'));

        if ((\Auth::user()->may('management.survey.update') && $survey->user_id == \Auth::user()->id) || \Auth::user()->may('superuser')) {
            $survey = $this->surveys->update(\Input::get('id'), \Input::except('id'));

            \Event::fire(new SurveyWasUpdatedEvent($survey, \Input::all()));

            return $survey;
        }

        throw new GeneralException("Not enough permissions.");
    }
}
