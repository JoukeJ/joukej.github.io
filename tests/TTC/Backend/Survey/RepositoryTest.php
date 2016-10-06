<?php

namespace Test\TTC\Survey;

use App\TTC\Models\Survey;
use Test\TTC\BaseTTCTest;

class RepositoryTest extends BaseTTCTest
{
    public function setup()
    {
        parent::setup();

        $this->besuperuser();
    }

    public function testFind()
    {
        $stubSurvey = $this->getSurvey();

        $survey = $this->backendSurveyRepository->findOrThrowException($stubSurvey->id);

        $this->assertEquals($stubSurvey->id, $survey->id);
    }

    public function testFindThrowsException()
    {
        $this->setExpectedException('\App\Exceptions\GeneralException');

        $this->backendSurveyRepository->findOrThrowException(0);
    }

    public function testFindThrowsExceptionWhenGivenSomethingElseThanInteger()
    {
        $this->setExpectedException('\App\Exceptions\GeneralException');

        $this->backendSurveyRepository->findOrThrowException([0]);
    }

    public function testCreate()
    {
        $stubSurvey = $this->getSurvey([], false);

        $survey = $this->backendSurveyRepository->create($stubSurvey->getAttributes());

        $this->assertEquals($stubSurvey->name, $survey->name);
        $this->assertNotNull($survey->id);
    }

    public function testCreateThrowsException()
    {
        $this->setExpectedException('App\Exceptions\GeneralException');

        $stubSurvey = $this->getSurvey(['name' => null], false);

        $this->backendSurveyRepository->create($stubSurvey->getAttributes());
    }

    public function testCreateWithInsufficientDataThrowsException()
    {
        $this->setExpectedException('\App\Exceptions\GeneralException');

        $stubSurvey = $this->getSurvey(['start_date' => null], false);

        $this->backendSurveyRepository->create($stubSurvey->getAttributes());
    }

    public function testSetMatchgroups()
    {
        $stubSurvey = $this->getSurvey();

        $stubMatchgroup = $this->getSurveyMatchgroup(['survey_id' => $stubSurvey->id], false);

        // create an array of matchgroups with 2 rules
        $matchgroupSet = [];
        $matchgroupSet[0] = $stubMatchgroup->getAttributes();
        $matchgroupSet[0]['rules'] = [
            $this->getSurveyMatchrule([], false)->getAttributes(),
            $this->getSurveyMatchrule([], false)->getAttributes(),
        ];

        // create the matchgroup with their rules
        $this->backendSurveyRepository->setMatchGroups($stubSurvey->id, $matchgroupSet);

        // find survey with matchgroups and rules
        $survey = $this->backendSurveyRepository->findOrThrowException($stubSurvey->id, false, true, false);

        $this->assertEquals($stubSurvey->id, $survey->id);
        $this->assertArrayHasKey('matchgroups', $survey->getRelations());
        $this->assertCount(1, $survey->getRelations()['matchgroups']);
        $this->assertCount(2, $survey->getRelations()['matchgroups'][0]['rules']);
    }

    public function testSetMatchgroupsReplace()
    {
        $stubSurvey = $this->getSurvey();

        $stubMatchgroup = $this->getSurveyMatchgroup(['survey_id' => $stubSurvey->id], false);

        // create an array of matchgroups with 2 rules
        $matchgroupSet = [];
        $matchgroupSet[0] = $stubMatchgroup->getAttributes();
        $matchgroupSet[0]['rules'] = [
            $this->getSurveyMatchrule([], false)->getAttributes(),
            $this->getSurveyMatchrule([], false)->getAttributes(),
        ];

        // set first set of matchgroups
        $this->backendSurveyRepository->setMatchGroups($stubSurvey->id, $matchgroupSet);

        // prepare second set that will overwrite the first one
        $matchgroupSet = [];
        $matchgroupSet[0] = $stubMatchgroup->getAttributes();
        $matchgroupSet[0]['rules'] = [
            $this->getSurveyMatchrule([], false)->getAttributes(),
            $this->getSurveyMatchrule([], false)->getAttributes(),
        ];

        // same call, this time call replace
        $this->backendSurveyRepository->setMatchGroups($stubSurvey->id, $matchgroupSet, true);

        $survey = $this->backendSurveyRepository->findOrThrowException($stubSurvey->id, false, true, false);

        $this->assertEquals($stubSurvey->id, $survey->id);
        $this->assertArrayHasKey('matchgroups', $survey->getRelations());
        $this->assertCount(1, $survey->getRelations()['matchgroups']);
        $this->assertCount(2, $survey->getRelations()['matchgroups'][0]['rules']);
    }

    public function testSetRepeats()
    {
        $stubSurvey = $this->getSurvey();
        $repeat = factory(Survey\Repeat::class)->make()->getAttributes();

        // save repeats
        $this->backendSurveyRepository->setRepeat($stubSurvey->id, $repeat);

        // load survey with repeats relation so we can check if they're saved properly
        $survey = $this->backendSurveyRepository->findOrThrowException($stubSurvey->id, false, false, true);

        $this->assertEquals($stubSurvey->id, $survey->id);
        $this->assertEquals($repeat['interval'], $survey->repeat->interval);
    }

    public function testUpdate()
    {
        $stubSurvey = $this->getSurvey();

        $newName = 'naampie';

        $survey = $this->backendSurveyRepository->update($stubSurvey->id, ['name' => $newName]);

        $this->assertEquals($newName, $survey->name);
    }

    public function testAll()
    {
        $this->getSurvey();
        $this->getSurvey();

        $this->assertEquals(Survey::all(), $this->backendSurveyRepository->all());
    }

    public function testAllopen()
    {
        $this->getSurvey(['status' => 'draft']);
        $this->getSurvey(['status' => 'open']);

        $this->assertEquals(Survey::whereStatus('open')->get(), $this->backendSurveyRepository->allOpen());

        $this->assertCount(1, $this->backendSurveyRepository->allOpen());
    }

    public function testAllOpenAndActive()
    {
        $this->getSurvey(['status' => 'draft']);
        $this->getSurvey(['status' => 'open', 'start_date' => '1990-1-1 12:00:00', 'end_date' => '1991-1-1 12:00:00']);
        $this->getSurvey(['status' => 'open']);

        $this->assertEquals(Survey::whereStatus('open')
            ->where('start_date', '<', date('Y-m-d H:i:s'))
            ->where('end_date', '>', date('Y-m-d H:i:s'))
            ->get(), $this->backendSurveyRepository->allOpenAndActive());

        $this->assertCount(1, $this->backendSurveyRepository->allOpenAndActive());
    }
}
