<?php
/**
 * Created by Luuk Holleman
 * Date: 17/06/15
 */

namespace Test\TTC\Survey;


use App\TTC\Jobs\Backend\Survey\CloseSurveyJob;
use App\TTC\Jobs\Backend\Survey\MatchMaker\CreateMatchgroupJob;
use App\TTC\Jobs\Backend\Survey\MatchMaker\CreateRepeatJob;
use App\TTC\Jobs\Backend\Survey\MatchMaker\DeleteMatchgroupJob;
use App\TTC\Jobs\Backend\Survey\MatchMaker\UpdateRepeatJob;
use App\TTC\Models\Survey;
use App\TTC\Models\Survey\Entity;
use App\TTC\Models\Survey\Matchgroup;
use Carbon\Carbon;
use Test\TTC\BaseTTCTest;

class JobTest extends BaseTTCTest
{
    public function testCreateMatchgroups()
    {
        $this->beSuperuser();


        $survey = factory(Survey::class)->create(['user_id' => $this->user->id]);

        $matchgroup1 = factory(Matchgroup::class)->make(['survey_id' => $survey->id]);
        $rule11 = factory(Survey\Matchrule::class)->make();
        $rule12 = factory(Survey\Matchrule::class)->make();

        $input = array_merge($matchgroup1->getAttributes(), [
            'rules' => [
                $rule11->getAttributes(),
                $rule12->getAttributes()
            ]
        ]);

        $job = \App::make(CreateMatchgroupJob::class, [$input]);
        $job->handle();

        foreach ([$matchgroup1] as $matchgroup) {
            $this->seeInDatabase('survey_matchgroups', [
                'name' => $matchgroup->name
            ]);
        }

        foreach ([$rule11, $rule12] as $rule) {
            $this->seeInDatabase('survey_matchrules',
                array_only($rule->getAttributes(), ['attribute', 'operator', 'values']));
        }
    }

    public function testUpdateMatchgroups()
    {
        $this->beSuperuser();


        $survey = factory(Survey::class)->create(['user_id' => $this->user->id]);

        $matchgroup1 = factory(Matchgroup::class)->create(['survey_id' => $survey->id]);
        $rule11 = factory(Survey\Matchrule::class)->create(['matchgroup_id' => $matchgroup1->id]);
        $rule12 = factory(Survey\Matchrule::class)->create(['matchgroup_id' => $matchgroup1->id]);

        $rule11->operator = 'niet bestaand';
        $rule12->values = 'ook niet bestaand';

        $input = array_merge($matchgroup1->getAttributes(), [
            'rules' => [
                $rule11->getAttributes(),
                $rule12->getAttributes()
            ]
        ]);

        $job = \App::make(CreateMatchgroupJob::class, [$input]);
        $job->handle();

        foreach ([$matchgroup1] as $matchgroup) {
            $this->seeInDatabase('survey_matchgroups', [
                'name' => $matchgroup->name
            ]);
        }

        foreach ([$rule11, $rule12] as $rule) {
            $this->seeInDatabase('survey_matchrules',
                array_only($rule->getAttributes(), ['attribute', 'operator', 'values']));
        }

        $this->assertCount(1, $survey->matchgroups);
        $this->assertCount(2, $survey->matchgroups()->first()->rules);
    }

    public function testDeleteMatchgroup()
    {
        $this->beSuperuser();

        $job = \App::make(DeleteMatchgroupJob::class);

        $survey = factory(Survey::class)->create(['user_id' => $this->user->id]);

        $matchgroup = factory(Matchgroup::class)->create(['survey_id' => $survey->id]);
        $rule1 = factory(Survey\Matchrule::class)->create(['matchgroup_id' => $matchgroup->id]);
        $rule2 = factory(Survey\Matchrule::class)->create(['matchgroup_id' => $matchgroup->id]);

        $route = \Mockery::mock('Illuminate\Routing\Route');
        $route->shouldReceive('getParameter')->andReturn($matchgroup->id);

        \Route::shouldReceive('current')->once()->andReturn($route);

        $job->handle();

        $this->notSeeInDatabase('survey_matchgroups', ['id' => $matchgroup->id]);
        $this->notSeeInDatabase('survey_matchrules', ['id' => $rule1->id]);
        $this->notSeeInDatabase('survey_matchrules', ['id' => $rule2->id]);
    }

    public function testCreateRepeat()
    {
        $this->beSuperuser();

        $survey = factory(Survey::class)->create(['user_id' => $this->user->id]);
        $repeat = factory(Survey\Repeat::class)->make(['survey_id' => $survey->id]);

        $input = [
            'survey_id' => $survey->id,
            'repeat' => $repeat->getAttributes()
        ];

        $job = \App::make(CreateRepeatJob::class, [$input]);
        $job->handle();

        $this->seeInDatabase('survey_repeats', ['survey_id' => $survey->id]);
    }

    public function testUpdateRepeat()
    {
        $this->beSuperuser();

        $survey = factory(Survey::class)->create(['user_id' => $this->user->id]);
        $repeat = factory(Survey\Repeat::class)->create(['survey_id' => $survey->id]);

        $repeat->interval = 'test';

        $input = [
            'survey_id' => $survey->id,
            'repeat' => $repeat->getAttributes()
        ];

        $job = \App::make(UpdateRepeatJob::class, [$input]);
        $job->handle();

        $this->seeInDatabase('survey_repeats', ['survey_id' => $survey->id, 'interval' => 'test']);
    }

    public function testCloseSurvey()
    {
        $this->beSuperuser();

        $survey = factory(Survey::class)->create(['user_id' => $this->user->id, 'status' => 'open']);

        $job = \App::make(CloseSurveyJob::class, [$survey->id]);

        $job->handle();

        $this->seeInDatabase('surveys', ['id' => $survey->id, 'status' => 'closed']);
    }

    public function testProfileSurveysAreAbandoned()
    {
        $this->beSuperuser();

        $survey = factory(Survey::class)->create([
            'user_id' => $this->user->id,
            'status' => 'open',
            'start_date' => date('Y-m-d H:i:s', strtotime('100 day ago')),
            'end_date' => date('Y-m-d H:i:s', strtotime('1 day ago')),
        ]);

        $profile = $this->createProfile();

        $profileSurvey1 = factory(\App\TTC\Models\Profile\Survey::class)->create([
            'profile_id' => $profile->id,
            'survey_id' => $survey->id,
            'status' => 'progress'
        ]);

        $profileSurvey2 = factory(\App\TTC\Models\Profile\Survey::class)->create([
            'profile_id' => $profile->id,
            'survey_id' => $survey->id,
            'status' => 'complete'
        ]);

        $job = \App::make(CloseSurveyJob::class, [$survey->id]);
        $job->handle();

        $this->seeInDatabase('profile_surveys', ['id' => $profileSurvey1->id, 'status' => 'abandoned']);
        $this->seeInDatabase('profile_surveys', ['id' => $profileSurvey2->id, 'status' => 'complete']);
    }

    public function testSurveyIsReopenedOnRepeat()
    {
        $this->beSuperuser();

        $endDate = Carbon::createFromTimestamp(strtotime('2 days ago'));

        $survey = factory(Survey::class)->create([
            'user_id' => $this->user->id,
            'status' => 'open',
            'start_date' => date('Y-m-d H:i:s', strtotime('9 days ago')),
            'end_date' => $endDate->format('Y-m-d H:i:s')
        ]);

        factory(Survey\Repeat::class)->create([
            'survey_id' => $survey->id,
            'interval' => 'week',
            'absolute_end_date' => date('Y-m-d H:i', strtotime('+10 days'))
        ]);

        $job = \App::make(CloseSurveyJob::class, [$survey->id]);

        $job->handle();

        $this->seeInDatabase('surveys',
            ['id' => $survey->id, 'status' => 'open', 'end_date' => $endDate->addDays(7)->format('Y-m-d H:i:s')]);
    }

    public function testSurveyIsReopenedOnRepeatAndMaximumOfAbsoluteEndDate()
    {
        $this->beSuperuser();

        $endDate = Carbon::createFromTimestamp(strtotime('2 days ago'));
        $absEndDate = Carbon::createFromTimestamp(strtotime('+3 days'));

        $survey = factory(Survey::class)->create([
            'user_id' => $this->user->id,
            'status' => 'open',
            'start_date' => date('Y-m-d H:i:s', strtotime('9 days ago')),
            'end_date' => $endDate->format('Y-m-d H:i:s')
        ]);

        factory(Survey\Repeat::class)->create([
            'survey_id' => $survey->id,
            'interval' => 'week',
            'absolute_end_date' => $absEndDate->format('Y-m-d H:i:s')
        ]);

        $job = \App::make(CloseSurveyJob::class, [$survey->id]);

        $job->handle();

        $this->seeInDatabase('surveys',
            ['id' => $survey->id, 'status' => 'open', 'end_date' => $absEndDate->format('Y-m-d H:i:s')]);
    }
}
