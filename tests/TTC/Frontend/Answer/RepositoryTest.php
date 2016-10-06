<?php namespace Test\TTC\Frontend\Answer;

use App\Exceptions\GeneralException;
use App\TTC\Models\Profile\Survey;
use Test\TTC\BaseTTCTest;

class RepositoryTest extends BaseTTCTest
{

    public function setUp()
    {
        parent::setUp();

        $this->beSuperuser();
    }

    public function testCreate()
    {
        $profileSurvey = Survey::firstOrCreate([
            'status' => 'progress',
            'profile_id' => $this->createProfile()->id,
            'survey_id' => $this->getSurvey(['user_id' => $this->user->id])->id,
        ]);

        $arr = [
            'profile_id' => $profileSurvey['profile_id'],
            'survey_id' => $profileSurvey['survey_id'],
            'entity_id' => $this->createEntityRadioQuestion()->id,
            'profile_survey_id' => $profileSurvey->id,
            'answer' => json_encode(['wrwhrhwrhw'])
        ];

        $this->frontendAnswerRepository->create($arr);

        $this->seeInDatabase('survey_answers', $arr);
    }

    public function testCreateThrowsException()
    {
        $this->setExpectedException(GeneralException::class);

        $arr = [
            'survey_id' => $this->getSurvey(['user_id' => $this->user->id])->id,
            'entity_id' => $this->createEntityRadioQuestion()->id,
            'answer' => json_encode(['wrwhrhwrhw'])
        ];

        $this->frontendAnswerRepository->create($arr);
    }
}
