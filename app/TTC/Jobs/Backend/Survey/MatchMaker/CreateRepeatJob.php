<?php
/**
 * Created by Luuk Holleman
 * Date: 18/06/15
 */

namespace App\TTC\Jobs\Backend\Survey\MatchMaker;

use App\Jobs\Job;
use App\TTC\Repositories\Backend\SurveyContract;
use Illuminate\Support\Arr;

/**
 * Class CreateRepeatJob
 * @package App\TTC\Jobs\Backend\Survey\MatchMaker
 */
class CreateRepeatJob extends Job
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
     * @param array $input
     * @param SurveyContract $surveys
     */
    public function __construct(array $input, SurveyContract $surveys)
    {
        $this->input = $input;
        $this->surveys = $surveys;
    }

    /**
     *
     */
    public function handle()
    {
        $this->surveys->setRepeat(Arr::get($this->input, 'survey_id'), Arr::get($this->input, 'repeat'));
    }
}
