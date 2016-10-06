<?php

namespace Test\TTC\Survey;

use App\TTC\Models\Survey;
use Test\TTC\BaseTTCTest;

class LogTest extends BaseTTCTest
{
    public function setUp()
    {
        parent::setUp();

        $this->beSuperuser();

        Survey::boot();
    }

    public function testCreatingLogs()
    {
        $survey = factory(Survey::class)->make(['user_id' => $this->user->id]);
        $this->backendSurveyRepository->create($survey->getAttributes());

        $this->seeInDatabase('survey_log',
            ['model' => Survey::class, 'action' => 'creating']);

        $log = Survey\Log::first();

        $this->assertNotEmpty($log->original);
        $this->assertNotEmpty($log->changed);
    }

    public function testUpdatingLogs()
    {
        $survey = factory(Survey::class)->create(['user_id' => $this->user->id]);

        $change = ['name' => 'test'];

        $this->backendSurveyRepository->update($survey->id, $change);

        $this->seeInDatabase('survey_log',
            ['model' => Survey::class, 'changed' => json_encode($change), 'action' => 'updating']);

        $log = Survey\Log::first();

        $this->assertNotEmpty($log->original);
        $this->assertNotEmpty($log->changed);
    }

    public function testDeletingLogs()
    {
        $survey = factory(Survey::class)->create(['user_id' => $this->user->id, 'status' => 'cancelled']);

        $this->backendSurveyRepository->delete($survey->id);

        $this->seeInDatabase('survey_log', ['model' => Survey::class, 'action' => 'deleting']);

        $log = Survey\Log::first();

        $this->assertNotEmpty($log->original);
    }
}
