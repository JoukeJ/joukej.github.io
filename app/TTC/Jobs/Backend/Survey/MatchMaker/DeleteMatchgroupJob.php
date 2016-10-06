<?php

namespace App\TTC\Jobs\Backend\Survey\MatchMaker;

use App\Jobs\Job;
use App\TTC\Repositories\Backend\SurveyContract;
use Illuminate\Contracts\Bus\SelfHandling;

class DeleteMatchgroupJob extends Job implements SelfHandling
{
    /**
     * @var SurveyContract
     */
    private $surveys;

    /**
     * Create a new job instance.
     *
     * @param SurveyContract $surveys
     */
    public function __construct(SurveyContract $surveys)
    {
        $this->surveys = $surveys;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->surveys->deleteMatchGroup(\Route::current()->getParameter('matchgroups'));
    }
}
