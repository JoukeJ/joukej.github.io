<?php


use Test\TTC\BaseTTCTest;

class MatchMakerTest extends BaseTTCTest
{
    public $profileParameters = [
        0 => [
            'gender' => 'male',
            'birthday' => '1980-1-1',
            'geo_country' => 'Nigeria'
        ],
        1 => [
            'gender' => 'male',
            'birthday' => '1980-1-1',
            'geo_country' => 'Kenya'
        ],
        2 => [
            'gender' => 'male',
            'birthday' => '1990-1-1',
            'geo_country' => 'Nigeria'
        ],
        3 => [
            'gender' => 'male',
            'birthday' => '1990-1-1',
            'geo_country' => 'Kenya'
        ],
        4 => [
            'gender' => 'male',
            'birthday' => '2000-1-1',
            'geo_country' => 'Nigeria'
        ],
        5 => [
            'gender' => 'male',
            'birthday' => '2000-1-1',
            'geo_country' => 'Kenya'
        ],
        6 => [
            'gender' => 'female',
            'birthday' => '1980-1-1',
            'geo_country' => 'Nigeria'
        ],
        7 => [
            'gender' => 'female',
            'birthday' => '1980-1-1',
            'geo_country' => 'Kenya'
        ],
        8 => [
            'gender' => 'female',
            'birthday' => '1990-1-1',
            'geo_country' => 'Nigeria'
        ],
        9 => [
            'gender' => 'female',
            'birthday' => '1990-1-1',
            'geo_country' => 'Kenya'
        ],
        10 => [
            'gender' => 'female',
            'birthday' => '2000-1-1',
            'geo_country' => 'Nigeria'
        ],
        11 => [
            'gender' => 'female',
            'birthday' => '2000-1-1',
            'geo_country' => 'Kenya'
        ],
    ];

    public function setUp()
    {
        parent::setUp();

        $this->beSuperuser();
    }


    public function createStubProfile($index)
    {
        return factory(\App\TTC\Models\Profile::class)->create(array_merge(array_except($this->profileParameters[$index],
            ['geo_country']),
            [
                'language_id' => $this->createLanguage()->id,
                'geo_country_id' => $this->createCountry($this->profileParameters[$index]['geo_country'])->id
            ]
        ));
    }

    public function createSurvey($rules = [], $attrs = [])
    {
        $survey = factory(\App\TTC\Models\Survey::class)->create(array_merge([
            'user_id' => $this->user->id,
            'start_date' => '2010-1-1 00:00:00',
            'end_date' => '2020-1-1 00:00:00'
        ], $attrs));

        $matchgroup = factory(\App\TTC\Models\Survey\Matchgroup::class)->create(['survey_id' => $survey->id]);

        foreach ($rules as $rule) {
            factory(\App\TTC\Models\Survey\Matchrule::class)->create([
                'matchgroup_id' => $matchgroup->id,
                'attribute' => $rule['attribute'],
                'values' => json_encode($rule['values']),
                'operator' => $rule['operator']
            ]);
        }

        return $survey;
    }

    public function getMatchMakerOnProfile($profileIndex)
    {
        return App::make(\App\TTC\MatchMaker\MatchMaker::class, [$this->createStubProfile($profileIndex)]);
    }

    public function testMatchWithNoRules()
    {
        $this->createSurvey([], ['status' => 'open']);

        $engine = $this->getMatchMakerOnProfile(0);

        $this->assertCount(0, $engine->findSurveys());
    }

    public function testMatchMale()
    {
        $this->createSurvey([
            [
                'attribute' => \App\TTC\MatchMaker\Attribute\Gender::class,
                'operator' => \App\TTC\MatchMaker\Operator\Equals::class,
                'values' => ['male']
            ]
        ], ['status' => 'open']);
        $this->createSurvey([
            [
                'attribute' => \App\TTC\MatchMaker\Attribute\Gender::class,
                'operator' => \App\TTC\MatchMaker\Operator\NotEquals::class,
                'values' => ['female']
            ]
        ], ['status' => 'open']);
        $this->createSurvey([
            [
                'attribute' => \App\TTC\MatchMaker\Attribute\Gender::class,
                'operator' => \App\TTC\MatchMaker\Operator\Equals::class,
                'values' => ['male']
            ],
            [
                'attribute' => \App\TTC\MatchMaker\Attribute\Gender::class,
                'operator' => \App\TTC\MatchMaker\Operator\NotEquals::class,
                'values' => ['female']
            ]
        ], ['status' => 'open']);


        for ($i = 0; $i <= 5; $i++) {
            $engine = $this->getMatchMakerOnProfile($i);

            $this->assertCount(3, $engine->findSurveys());
        }
    }

    public function testMatchFemale()
    {
        $this->createSurvey([
            [
                'attribute' => \App\TTC\MatchMaker\Attribute\Gender::class,
                'operator' => \App\TTC\MatchMaker\Operator\Equals::class,
                'values' => ['female']
            ]
        ], ['status' => 'open']);
        $this->createSurvey([
            [
                'attribute' => \App\TTC\MatchMaker\Attribute\Gender::class,
                'operator' => \App\TTC\MatchMaker\Operator\NotEquals::class,
                'values' => ['male']
            ]
        ], ['status' => 'open']);
        $this->createSurvey([
            [
                'attribute' => \App\TTC\MatchMaker\Attribute\Gender::class,
                'operator' => \App\TTC\MatchMaker\Operator\Equals::class,
                'values' => ['female']
            ],
            [
                'attribute' => \App\TTC\MatchMaker\Attribute\Gender::class,
                'operator' => \App\TTC\MatchMaker\Operator\NotEquals::class,
                'values' => ['male']
            ]
        ], ['status' => 'open']);

        for ($i = 6; $i <= 11; $i++) {
            $engine = $this->getMatchMakerOnProfile($i);

            $this->assertCount(3, $engine->findSurveys());
        }
    }

    public function testNonMatchGender()
    {
        // should not match
        $this->createSurvey([
            [
                'attribute' => \App\TTC\MatchMaker\Attribute\Gender::class,
                'operator' => \App\TTC\MatchMaker\Operator\Equals::class,
                'values' => ['x']
            ]
        ], ['status' => 'open']);

        for ($i = 0; $i <= 11; $i++) {
            $engine = $this->getMatchMakerOnProfile($i);

            $this->assertCount(0, $engine->findSurveys());
        }
    }

    public function testCountry()
    {
        $this->createSurvey([
            [
                'attribute' => \App\TTC\MatchMaker\Attribute\Country::class,
                'operator' => \App\TTC\MatchMaker\Operator\Equals::class,
                'values' => [$this->createCountry('Kenya')->id]
            ]
        ], ['status' => 'open']);
        $this->createSurvey([
            [
                'attribute' => \App\TTC\MatchMaker\Attribute\Country::class,
                'operator' => \App\TTC\MatchMaker\Operator\NotEquals::class,
                'values' => [$this->createCountry('Nigeria')->id]
            ]
        ], ['status' => 'open']);
        $this->createSurvey([
            [
                'attribute' => \App\TTC\MatchMaker\Attribute\Country::class,
                'operator' => \App\TTC\MatchMaker\Operator\In::class,
                'values' => [$this->createCountry('Nigeria')->id, $this->createCountry('Kenya')->id]
            ]
        ], ['status' => 'open']);

        for ($i = 1; $i <= 11; $i += 2) {
            $engine = $this->getMatchMakerOnProfile($i);

            $this->assertCount(3, $engine->findSurveys());
        }
    }

    public function testAge()
    {
        $this->createSurvey([
            [
                'attribute' => \App\TTC\MatchMaker\Attribute\Age::class,
                'operator' => \App\TTC\MatchMaker\Operator\GreaterThan::class,
                'values' => [27]
            ]
        ], ['status' => 'open']);
        $this->createSurvey([
            [
                'attribute' => \App\TTC\MatchMaker\Attribute\Age::class,
                'operator' => \App\TTC\MatchMaker\Operator\LessThan::class,
                'values' => [24]
            ]
        ], ['status' => 'open']);
        $this->createSurvey([
            [
                'attribute' => \App\TTC\MatchMaker\Attribute\Age::class,
                'operator' => \App\TTC\MatchMaker\Operator\Between::class,
                'values' => [20, 30]
            ]
        ], ['status' => 'open']);

        for ($i = 1; $i <= 11; $i++) {
            $engine = $this->getMatchMakerOnProfile($i);

            $this->assertCount(1, $engine->findSurveys(), $i . " " . $this->profileParameters[$i]['birthday']);
        }
    }

    public function testPriority()
    {
        $this->createSurvey([
            [
                'attribute' => \App\TTC\MatchMaker\Attribute\Gender::class,
                'operator' => \App\TTC\MatchMaker\Operator\Equals::class,
                'values' => ['male']
            ]
        ], ['priority' => 25, 'status' => 'open']);
        $this->createSurvey([
            [
                'attribute' => \App\TTC\MatchMaker\Attribute\Gender::class,
                'operator' => \App\TTC\MatchMaker\Operator\Equals::class,
                'values' => ['male']
            ]
        ], ['priority' => 100, 'status' => 'open']);
        $this->createSurvey([
            [
                'attribute' => \App\TTC\MatchMaker\Attribute\Gender::class,
                'operator' => \App\TTC\MatchMaker\Operator\Equals::class,
                'values' => ['male']
            ]
        ], ['priority' => 1, 'status' => 'open']);
        $this->createSurvey([
            [
                'attribute' => \App\TTC\MatchMaker\Attribute\Gender::class,
                'operator' => \App\TTC\MatchMaker\Operator\Equals::class,
                'values' => ['male']
            ]
        ], ['priority' => 50, 'status' => 'open']);

        $engine = $this->getMatchMakerOnProfile(1);

        $surveys = $engine->findSurveys();

        $this->assertEquals(100, $surveys->shift()->priority);
        $this->assertEquals(50, $surveys->shift()->priority);
        $this->assertEquals(25, $surveys->shift()->priority);
        $this->assertEquals(1, $surveys->shift()->priority);
    }

    public function testOnlyOpenSurveysMatches()
    {
        $this->createSurvey([
            [
                'attribute' => \App\TTC\MatchMaker\Attribute\Gender::class,
                'operator' => \App\TTC\MatchMaker\Operator\Equals::class,
                'values' => ['male']
            ]
        ], ['status' => 'draft']);

        $engine = $this->getMatchMakerOnProfile(0);

        $this->assertCount(0, $engine->findSurveys());
    }

    public function testEligibleSurvey()
    {
        $survey = $this->createSurvey([
            [
                'attribute' => \App\TTC\MatchMaker\Attribute\Gender::class,
                'operator' => \App\TTC\MatchMaker\Operator\Equals::class,
                'values' => ['male']
            ]
        ], ['status' => 'open']);

        $profile = $this->createStubProfile(0);

        factory(\App\TTC\Models\Profile\Survey::class)->create([
            'profile_id' => $profile->id,
            'survey_id' => $survey->id,
            'status' => 'progress',
            'previous' => 0
        ]);
        factory(\App\TTC\Models\Profile\Survey::class)->create([
            'profile_id' => $profile->id,
            'survey_id' => $survey->id,
            'status' => 'abandoned',
            'previous' => 1
        ]);
        factory(\App\TTC\Models\Profile\Survey::class)->create([
            'profile_id' => $profile->id,
            'survey_id' => $survey->id,
            'status' => 'completed',
            'previous' => 1
        ]);

        $engine = App::make(\App\TTC\MatchMaker\MatchMaker::class, [$profile]);

        $this->assertCount(1, $engine->findSurveys());
    }

    public function testEligibleSurveyForTheFirsttime()
    {
        $survey = $this->createSurvey([
            [
                'attribute' => \App\TTC\MatchMaker\Attribute\Gender::class,
                'operator' => \App\TTC\MatchMaker\Operator\Equals::class,
                'values' => ['male']
            ]
        ], ['status' => 'open']);

        $profile = $this->createStubProfile(0);

        $engine = App::make(\App\TTC\MatchMaker\MatchMaker::class, [$profile]);

        $this->assertCount(1, $engine->findSurveys());
    }

    public function testNonEligibleSurvey()
    {
        $survey = $this->createSurvey([
            [
                'attribute' => \App\TTC\MatchMaker\Attribute\Gender::class,
                'operator' => \App\TTC\MatchMaker\Operator\Equals::class,
                'values' => ['male']
            ]
        ], ['status' => 'open']);

        $profile = $this->createStubProfile(0);

        factory(\App\TTC\Models\Profile\Survey::class)->create([
            'profile_id' => $profile->id,
            'survey_id' => $survey->id,
            'status' => 'progress',
            'previous' => 1
        ]);
        factory(\App\TTC\Models\Profile\Survey::class)->create([
            'profile_id' => $profile->id,
            'survey_id' => $survey->id,
            'status' => 'abandoned',
            'previous' => 0
        ]);
        factory(\App\TTC\Models\Profile\Survey::class)->create([
            'profile_id' => $profile->id,
            'survey_id' => $survey->id,
            'status' => 'completed',
            'previous' => 0
        ]);

        $engine = App::make(\App\TTC\MatchMaker\MatchMaker::class, [$profile]);

        $this->assertCount(0, $engine->findSurveys());
    }
}
