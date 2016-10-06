<?php
/**
 * Created by Luuk Holleman
 * Date: 01/07/15
 */

namespace App\Console\Commands;


use App\TTC\Jobs\Backend\Survey\CloseSurveyJob;
use App\TTC\Repositories\Backend\SurveyContract;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

/**
 * Class CloseRepeatingSurveys
 * @package App\Console\Commands
 */
class CloseSurveys extends Command
{
    use DispatchesJobs;
    /**
     * @var string
     */
    protected $signature = 'survey:close';

    /**
     * @var string
     */
    protected $description = 'Close surveys that are expired.';

    /**
     * @var SurveyContract
     */
    private $surveyRepository;

    /**
     * @param $surveyRepository
     */
    public function __construct(SurveyContract $surveyRepository)
    {
        $this->surveyRepository = $surveyRepository;

        parent::__construct();
    }

    /**
     *
     */
    public function handle()
    {
        $surveys = $this->surveyRepository->allOpenAndExpired();

        foreach ($surveys as $survey) {
            $this->dispatch(app(CloseSurveyJob::class, [$survey->id]));
        }
    }
}
