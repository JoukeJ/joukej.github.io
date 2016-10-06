<?php

namespace App\TTC\Jobs\Backend\Survey;

use App\Exceptions\GeneralException;
use App\Jobs\Job;
use App\TTC\Models\Survey;
use App\TTC\Repositories\Backend\SurveyContract;
use Illuminate\Contracts\Bus\SelfHandling;

class CreateSurveyJob extends Job implements SelfHandling
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
     * @throws GeneralException
     * @return Survey
     */
    public function handle()
    {
        if ((\Auth::user()->may('management.survey.create'))) {
            return $this->surveys->create(\Input::all());
        }

        throw new GeneralException("Not enough permissions");
    }
}
