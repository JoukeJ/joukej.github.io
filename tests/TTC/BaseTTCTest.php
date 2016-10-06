<?php

namespace Test\TTC;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\TTC\Models\Country;
use App\TTC\Models\Language;
use App\TTC\Models\Profile;
use App\TTC\Models\Survey;
use App\TTC\Repositories\Backend\EntityRepository as BackendEntityRepository;
use App\TTC\Repositories\Backend\SurveyRepository as BackendSurveyRepository;
use App\TTC\Repositories\Frontend\AnswerRepository;
use App\TTC\Repositories\Frontend\EntityRepository as FrontendEntityRepository;
use App\TTC\Repositories\Frontend\SurveyRepository as FrontendSurveyRepository;
use Exception;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseTransactions;


abstract class BaseTTCTest extends \TestCase
{
    use DatabaseTransactions;

    /**
     * @var \App\TTC\Repositories\Frontend\EntityRepository
     */
    protected $frontendEntityRepository;

    /**
     * @var \App\TTC\Repositories\Frontend\SurveyRepository
     */
    protected $frontendSurveyRepository;

    /**
     * @var \App\TTC\Repositories\Frontend\AnswerRepository
     */
    protected $frontendAnswerRepository;

    /**
     * @var \App\TTC\Repositories\Backend\EntityRepository
     */
    protected $backendEntityRepository;

    /**
     * @var \App\TTC\Repositories\Backend\SurveyRepository
     */
    protected $backendSurveyRepository;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Generator
     */
    protected $faker;

    protected $useTransactions = true;

    protected $useMigrations = false;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        \Dotenv::load('.');
        putenv('DB_DATABASE=' . getenv('DB_DATABASE_TEST'));

        $output = parent::createApplication();

        return $output;
    }

    public function setUp()
    {
        parent::setUp();

        $this->frontendSurveyRepository = \App::make(FrontendSurveyRepository::class);
        $this->frontendEntityRepository = \App::make(FrontendEntityRepository::class);
        $this->frontendAnswerRepository = \App::make(AnswerRepository::class);

        $this->backendSurveyRepository = \App::make(BackendSurveyRepository::class);
        $this->backendEntityRepository = \App::make(BackendEntityRepository::class);

        $this->faker = Factory::create();
    }

    public function beSuperuser()
    {
        $user = User::where('first_name', 'Admin')->first();

        if ($user === null) {
            $user = factory(User::class)->create([
                'first_name' => 'Admin',
            ]);
            $role = factory(Role::class)->create([
                'name' => 'superuser',
                'display_name' => 'Superuser',
            ]);
            $permission = factory(Permission::class)->create([
                'name' => 'superuser',
                'display_name' => 'Superuser',
            ]);

            $user->roles()->attach($role->id);
            $role->perms()->attach($permission->id);
        }


        $this->be($user);

        $this->user = $user;
    }

    protected function getSurveyMatchgroup($attributes = [], $create = true)
    {
        $matchgroup = factory(Survey\Matchgroup::class)->make($attributes);

        if ($create) {
            $matchgroup->save();
        }

        return $matchgroup;
    }

    protected function getSurveyMatchrule($attributes = [], $create = true)
    {
        $matchgroup = factory(Survey\Matchrule::class)->make($attributes);

        if ($create) {
            $matchgroup->save();
        }

        return $matchgroup;
    }

    protected function getSurveyRepeat($attributes = [], $create = true)
    {
        $repeat = factory(Survey\Repeat::class)->make($attributes);

        if ($create) {
            $repeat->save();
        }

        return $repeat;
    }

    /**
     * @param array $attributes
     * @param bool|true $create
     * @return mixed
     */
    protected function getSurvey($attributes = [], $create = true)
    {
        if (!array_key_exists('user_id', $attributes)) {
            $attributes['user_id'] = $this->user->id;
        }

        $survey = factory(Survey::class)->make($attributes);

        if ($create) {
            $survey->save();
        }

        return $survey;
    }

    /**
     * Returns a Entity with an associated Radio question
     * @param null $afterId
     * @return Survey\Entity
     * @throws \App\Exceptions\GeneralException
     */
    protected function createEntityRadioQuestion($afterId = null)
    {
        $survey = $this->getSurvey(['status' => 'draft']);

        $typeOptions = [
            'a' => $this->faker->sentence(5, true),
            'b' => $this->faker->sentence(5, true),
            'c' => $this->faker->sentence(5, true),
            'd' => $this->faker->sentence(5, true),
        ];

        $typeAttributes = factory(Survey\Entity\Question\Open::class)->make()->getAttributes();

        \Input::replace([
            'entity' => [
                'survey_id' => $survey->id,
                'identifier' => str_random(32),
            ],
            'type' => 'q_radio',
            'entity_type' => $typeAttributes,
            'type_options' => $typeOptions,
        ]);

        return $this->backendEntityRepository->create(\Input::all(), $afterId);
    }

    /**
     * @return Language
     */
    public function createLanguage()
    {
        try {
            $language = factory(\App\TTC\Models\Language::class)->create();
        } catch (Exception $e) {
            $language = \App\TTC\Models\Language::orderByRaw('RAND()')->first();
        }

        return $language;
    }

    /**
     * @param array $iso
     * @return Country
     */
    public function createCountry($iso = null)
    {
        if ($iso != null) {
            $data = ['iso' => $iso];
        } else {
            $data = ['iso' => $stubCountry = factory(Country::class)->make()->iso];
        }

        $country = Country::firstOrCreate($data);

        return $country;
    }

    /**
     * @return Profile
     */
    public function createProfile($amount = 1)
    {
        if ($amount > 1) {
            $profile = [];

            while ($amount--) {
                $profile[] = factory(Profile::class)->create([
                    'language_id' => $this->createLanguage()->id,
                    'geo_country_id' => $this->createCountry()->id,
                ]);
            }
        } else {
            $profile = factory(Profile::class)->create([
                'language_id' => $this->createLanguage()->id,
                'geo_country_id' => $this->createCountry()->id,
            ]);
        }

        return $profile;
    }
}
