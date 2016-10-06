<?php

namespace App\TTC\Jobs\Frontend\Answer;

use App\Jobs\Job;
use App\TTC\Chain\Response\AnswerResponse;
use App\TTC\Models\Profile\Survey;
use App\TTC\Models\Survey\Answer;
use App\TTC\Repositories\Frontend\AnswerContract;
use App\TTC\Repositories\Frontend\ProfileSurveyContract;
use Illuminate\Contracts\Bus\SelfHandling;

class AnswerQuestionJob extends Job implements SelfHandling
{

    /**
     * @var AnswerResponse
     */
    protected $answerResponse;

    /**
     * @var AnswerContract
     */
    protected $answers;

    /**
     * @var ProfileSurveyContract
     */
    protected $profileSurveys;

    /**
     * AnswerQuestionJob constructor.
     * @param AnswerResponse $answerResponse
     * @param AnswerContract $answers
     * @param ProfileSurveyContract $profileSurveys
     */
    public function __construct(
        AnswerResponse $answerResponse,
        AnswerContract $answers,
        ProfileSurveyContract $profileSurveys
    ) {
        $this->answerResponse = $answerResponse;
        $this->answers = $answers;
        $this->profileSurveys = $profileSurveys;
    }


    /**
     * Execute the job.
     *
     * @return Answer
     */
    public function handle()
    {
        $profileSurvey = $this->profileSurveys->findOrCreate([
            'profile_id' => $this->answerResponse->getChainItem()->getSurveyChain()->getProfile()->id,
            'survey_id' => $this->answerResponse->getChainItem()->getSurveyChain()->getSurvey()->id,
            'status' => 'progress'
        ]);

        try {
            return $this->answers->create([
                'profile_id' => $this->answerResponse->getChainItem()->getSurveyChain()->getProfile()->id,
                'survey_id' => $this->answerResponse->getChainItem()->getSurveyChain()->getSurvey()->id,
                'entity_id' => $this->answerResponse->getChainItem()->getSurveyChain()->getEntity()->id,
                'profile_survey_id' => $profileSurvey->id,
                'answer' => $this->answerResponse->getJsonAnswer()
            ]);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
