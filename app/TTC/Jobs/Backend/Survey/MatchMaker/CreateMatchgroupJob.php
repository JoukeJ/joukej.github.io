<?php

namespace App\TTC\Jobs\Backend\Survey\MatchMaker;

use App\Jobs\Job;
use App\TTC\Repositories\Backend\SurveyContract;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Support\Arr;

class CreateMatchgroupJob extends Job implements SelfHandling
{
    /**
     * @var array
     */
    private $input;
    /**
     * @var SurveyContract
     */
    private $surveys;

    /**
     * Create a new job instance.
     *
     * @param array $input
     * @param SurveyContract $surveys
     */
    public function __construct(array $input, SurveyContract $surveys)
    {
        $this->input = $input;
        $this->surveys = $surveys;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /**
         * POST needs to look like
         * 'matchgroups' => [ array with matchgroups
         *    0 => [
         *        "survey_id" => 184
         *        "name" => "Annie Grady"
         *        "rules" => [ array with rules
         *            0 => [
         *                "attribute" => "age"
         *                "operator" => ">"
         *                "values" => 8
         *            ]
         *            1 => [
         *                "attribute" => "age"
         *                "operator" => "=="
         *                "values" => 1
         *            ]
         *        ]
         *    ]
         * ]
         */

        $this->surveys->setMatchGroups(Arr::get($this->input, 'survey_id'), [$this->input]);
    }
}
