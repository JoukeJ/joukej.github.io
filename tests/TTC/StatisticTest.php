<?php
/**
 * Created by Luuk Holleman
 * Date: 30/06/15
 */

namespace Test\TTC;


use App\TTC\Models\Profile\Survey;
use App\TTC\Models\Survey\Answer;
use App\TTC\Models\Survey\Entity;
use App\TTC\Statistic\Statistic;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class StatisticTest extends BaseTTCTest
{

    protected $excludedTables = [
        'users',
        'migrations'
    ];

    public function setUp()
    {
        parent::setUp();

        $this->beSuperuser();
    }

    public function testTotalParticipants()
    {
        $survey = $this->getSurvey([], true);

        for ($i = 0; $i < 10; $i++) {
            $profile = $this->createProfile();

            factory(Survey::class)->create([
                'profile_id' => $profile->id,
                'survey_id' => $survey->id,
                'status' => 'completed'
            ]);
            factory(Survey::class)->create([
                'profile_id' => $profile->id,
                'survey_id' => $survey->id,
                'status' => 'completed'
            ]);
            factory(Survey::class)->create([
                'profile_id' => $profile->id,
                'survey_id' => $survey->id,
                'status' => 'abandoned',
                'previous' => 1
            ]);
        }

        $stat = new Statistic($survey);

        $this->assertEquals(20, $stat->totalParticipants());
        $this->assertEquals(10, $stat->totalUniqueParticipants());
    }

    public function testCompletedParticipants()
    {
        $survey = $this->getSurvey([], true);

        for ($i = 0; $i < 10; $i++) {
            $profile = $this->createProfile();

            factory(Survey::class)->create([
                'profile_id' => $profile->id,
                'survey_id' => $survey->id,
                'status' => 'abandoned'
            ]);
            factory(Survey::class)->create([
                'profile_id' => $profile->id,
                'survey_id' => $survey->id,
                'status' => 'abandoned'
            ]);
            factory(Survey::class)->create([
                'profile_id' => $profile->id,
                'survey_id' => $survey->id,
                'status' => 'completed',
                'previous' => 1
            ]);
        }

        $stat = new Statistic($survey);

        $this->assertEquals(20, $stat->totalAbandoned());
        $this->assertEquals(10, $stat->totalUniqueAbandoned());
    }

    public function testProgressParticipants()
    {
        $survey = $this->getSurvey([], true);

        for ($i = 0; $i < 10; $i++) {
            $profile = $this->createProfile();

            factory(Survey::class)->create([
                'profile_id' => $profile->id,
                'survey_id' => $survey->id,
                'status' => 'progress'
            ]);
            factory(Survey::class)->create([
                'profile_id' => $profile->id,
                'survey_id' => $survey->id,
                'status' => 'progress'
            ]);
            factory(Survey::class)->create([
                'profile_id' => $profile->id,
                'survey_id' => $survey->id,
                'status' => 'completed',
                'previous' => 1
            ]);
        }

        $stat = new Statistic($survey);

        $this->assertEquals(10, $stat->totalInProgress());
    }

    public function testEntityText()
    {
        $survey = $this->getSurvey([], true);

        $profile = $this->createProfile();

        $profileSurvey = factory(Survey::class)->create([
            'profile_id' => $profile->id,
            'survey_id' => $survey->id,
            'status' => 'completed'
        ]);

        $text = factory(Entity\Question\Text::class)->create();

        $entity = factory(Entity::class)->create([
            'survey_id' => $survey->id,
            'entity_id' => $text->id,
            'entity_type' => Entity\Question\Text::class
        ]);

        factory(Answer::class)->create([
            'profile_id' => $profile->id,
            'survey_id' => $survey->id,
            'entity_id' => $entity->id,
            'profile_survey_id' => $profileSurvey->id,
            'answer' => '"Test"'
        ]);

        factory(Answer::class)->create([
            'profile_id' => $this->createProfile()->id,
            'survey_id' => $survey->id,
            'entity_id' => $entity->id,
            'profile_survey_id' => $profileSurvey->id,
            'answer' => '"Test"'
        ]);

        factory(Answer::class)->create([
            'profile_id' => $this->createProfile()->id,
            'survey_id' => $survey->id,
            'entity_id' => $entity->id,
            'profile_survey_id' => $profileSurvey->id,
            'answer' => '"Test2"'
        ]);

        factory(Answer::class)->create([
            'profile_id' => $this->createProfile()->id,
            'survey_id' => $survey->id,
            'entity_id' => $entity->id,
            'profile_survey_id' => $profileSurvey->id,
            'answer' => '"Test3"'
        ]);

        $stat = new Statistic($survey);

        $this->assertEquals([
            "Test" => 2,
            "Test2" => 1,
            "Test3" => 1
        ], $entityStat = $stat->getEntity($entity)->countPerAnswer());

        $this->assertEquals([
            "Test" => 50,
            "Test2" => 25,
            "Test3" => 25
        ], $entityStat = $stat->getEntity($entity)->percentages());
    }

    public function testOnlyCompletedAreCounted()
    {
        $survey = $this->getSurvey([], true);

        $profile = $this->createProfile();

        $profileSurvey = factory(Survey::class)->create([
            'profile_id' => $profile->id,
            'survey_id' => $survey->id,
            'status' => 'abandoned'
        ]);

        $text = factory(Entity\Question\Text::class)->create();

        $entity = factory(Entity::class)->create([
            'survey_id' => $survey->id,
            'entity_id' => $text->id,
            'entity_type' => Entity\Question\Text::class
        ]);

        factory(Answer::class)->create([
            'profile_id' => $this->createProfile()->id,
            'survey_id' => $survey->id,
            'entity_id' => $entity->id,
            'profile_survey_id' => $profileSurvey->id,
            'answer' => '"Test"'
        ]);

        $stat = new Statistic($survey);

        $this->assertEquals([], $entityStat = $stat->getEntity($entity)->countPerAnswer());

        $this->assertEquals([], $entityStat = $stat->getEntity($entity)->percentages());
    }

    public function testEntityOpen()
    {
        $survey = $this->getSurvey([], true);

        $profile = $this->createProfile();

        $profileSurvey = factory(Survey::class)->create([
            'profile_id' => $profile->id,
            'survey_id' => $survey->id,
            'status' => 'completed'
        ]);

        $text = factory(Entity\Question\Open::class)->create();

        $entity = factory(Entity::class)->create([
            'survey_id' => $survey->id,
            'entity_id' => $text->id,
            'entity_type' => Entity\Question\Open::class
        ]);

        factory(Answer::class)->create([
            'profile_id' => $this->createProfile()->id,
            'survey_id' => $survey->id,
            'entity_id' => $entity->id,
            'profile_survey_id' => $profileSurvey->id,
            'answer' => '"Test"'
        ]);

        factory(Answer::class)->create([
            'profile_id' => $this->createProfile()->id,
            'survey_id' => $survey->id,
            'entity_id' => $entity->id,
            'profile_survey_id' => $profileSurvey->id,
            'answer' => '"Test"'
        ]);

        factory(Answer::class)->create([
            'profile_id' => $this->createProfile()->id,
            'survey_id' => $survey->id,
            'entity_id' => $entity->id,
            'profile_survey_id' => $profileSurvey->id,
            'answer' => '"Test2"'
        ]);

        factory(Answer::class)->create([
            'profile_id' => $this->createProfile()->id,
            'survey_id' => $survey->id,
            'entity_id' => $entity->id,
            'profile_survey_id' => $profileSurvey->id,
            'answer' => '"Test3"'
        ]);

        $stat = new Statistic($survey);

        $this->assertEquals([
            "Test" => 2,
            "Test2" => 1,
            "Test3" => 1
        ], $entityStat = $stat->getEntity($entity)->countPerAnswer());

        $this->assertEquals([
            "Test" => 50,
            "Test2" => 25,
            "Test3" => 25
        ], $entityStat = $stat->getEntity($entity)->percentages());
    }

    public function testEntityCheckbox()
    {
        $survey = $this->getSurvey([], true);

        $profile = $this->createProfile();

        $profileSurvey = factory(Survey::class)->create([
            'profile_id' => $profile->id,
            'survey_id' => $survey->id,
            'status' => 'completed'
        ]);

        $text = factory(Entity\Question\Checkbox::class)->create();

        $entity = factory(Entity::class)->create([
            'survey_id' => $survey->id,
            'entity_id' => $text->id,
            'entity_type' => Entity\Question\Checkbox::class
        ]);

        factory(Answer::class)->create([
            'profile_id' => $this->createProfile()->id,
            'survey_id' => $survey->id,
            'entity_id' => $entity->id,
            'profile_survey_id' => $profileSurvey->id,
            'answer' => json_encode(["Optie 1", "Optie 2"])
        ]);

        factory(Answer::class)->create([
            'profile_id' => $this->createProfile()->id,
            'survey_id' => $survey->id,
            'entity_id' => $entity->id,
            'profile_survey_id' => $profileSurvey->id,
            'answer' => json_encode(["Optie 2", "Optie 3"])
        ]);

        factory(Answer::class)->create([
            'profile_id' => $this->createProfile()->id,
            'survey_id' => $survey->id,
            'entity_id' => $entity->id,
            'profile_survey_id' => $profileSurvey->id,
            'answer' => json_encode(["Optie 4"])
        ]);

        $stat = new Statistic($survey);

        $this->assertEquals([
            "Optie 1" => 1,
            "Optie 2" => 2,
            "Optie 3" => 1,
            "Optie 4" => 1
        ], $entityStat = $stat->getEntity($entity)->countPerAnswer());

        $this->assertEquals([
            "Optie 1" => 20,
            "Optie 2" => 40,
            "Optie 3" => 20,
            "Optie 4" => 20
        ], $entityStat = $stat->getEntity($entity)->percentages());
    }

    public function testEntityRadio()
    {
        $survey = $this->getSurvey([], true);

        $profile = $this->createProfile();

        $profileSurvey = factory(Survey::class)->create([
            'profile_id' => $profile->id,
            'survey_id' => $survey->id,
            'status' => 'completed'
        ]);

        $text = factory(Entity\Question\Radio::class)->create();

        $entity = factory(Entity::class)->create([
            'survey_id' => $survey->id,
            'entity_id' => $text->id,
            'entity_type' => Entity\Question\Radio::class
        ]);

        factory(Answer::class)->create([
            'profile_id' => $this->createProfile()->id,
            'survey_id' => $survey->id,
            'entity_id' => $entity->id,
            'profile_survey_id' => $profileSurvey->id,
            'answer' => '"Optie 1"'
        ]);

        factory(Answer::class)->create([
            'profile_id' => $this->createProfile()->id,
            'survey_id' => $survey->id,
            'entity_id' => $entity->id,
            'profile_survey_id' => $profileSurvey->id,
            'answer' => '"Optie 1"'
        ]);

        factory(Answer::class)->create([
            'profile_id' => $this->createProfile()->id,
            'survey_id' => $survey->id,
            'entity_id' => $entity->id,
            'profile_survey_id' => $profileSurvey->id,
            'answer' => '"Optie 2"'
        ]);

        factory(Answer::class)->create([
            'profile_id' => $this->createProfile()->id,
            'survey_id' => $survey->id,
            'entity_id' => $entity->id,
            'profile_survey_id' => $profileSurvey->id,
            'answer' => '"Optie 3"'
        ]);

        $stat = new Statistic($survey);

        $this->assertEquals([
            "Optie 1" => 2,
            "Optie 2" => 1,
            "Optie 3" => 1
        ], $entityStat = $stat->getEntity($entity)->countPerAnswer());

        $this->assertEquals([
            "Optie 1" => 50,
            "Optie 2" => 25,
            "Optie 3" => 25
        ], $entityStat = $stat->getEntity($entity)->percentages());
    }
}
