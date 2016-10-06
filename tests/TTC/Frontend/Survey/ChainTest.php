<?php


namespace Test\TTC\Frontend\Survey;


use App\Models\User;
use App\TTC\Chain\ChainPayload;
use App\TTC\Chain\Payload\GetPayload;
use App\TTC\Chain\Payload\PostPayload;
use App\TTC\Chain\Response\EndOfChain\ErrorResponse;
use App\TTC\Chain\Response\EndOfChain\RedirectResponse;
use App\TTC\Chain\Response\EndOfChain\RenderResponse;
use App\TTC\Chain\SurveyChain;
use App\TTC\Exceptions\Chain\InvalidPayloadException;
use App\TTC\Models\Profile;
use App\TTC\Models\Survey;
use App\TTC\Tags\Entity\CanBeAnswered;
use App\TTC\Tags\Entity\CanBePresented;
use App\TTC\Tags\Entity\RequiresOptions;
use Test\TTC\BaseTTCTest;

class ChainTest extends BaseTTCTest
{

    private $types;

    private $lastId = null;

    private $optionAmount = 5;

    /**
     * @var Profile
     */
    private $profile;

    /**
     * @var Survey
     */
    private $survey;

    const PAYLOAD_GET = 0x01;
    const PAYLOAD_POST = 0x02;

    //protected $useTransactions = false;

    protected $excludedTables = [];

    public function setUp()
    {
        parent::setUp();

        $this->beSuperuser();
        $this->types = \Config::get('ttc.entity.types');
        $this->profile = $this->createProfile();
        $this->survey = Survey::find(1);
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->lastId = null;
    }

/*    protected function _testEntity($id, $method, $answer = null)
    {
        return; // disabled
        $this->useTransactions = false;
        $this->useMigrations = true;

        $this->beSuperuser();

        \DB::connection('mysql_test')->unprepared(\File::get(base_path(DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'survey_seed.sql')));

        $entity = Survey\Entity::find($id);
        $chain = new SurveyChain($this->profile, $this->survey, $entity);

        $payload = null;
        if ($method === self::PAYLOAD_GET) {
            $payload = new GetPayload($this->profile, $this->survey, $entity);
        } elseif ($method === self::PAYLOAD_POST) {
            $payload = new PostPayload($this->profile, $this->survey, $entity, $answer);
        }

        return $chain->invokeRaw($payload);
    }

    public function testSeedGetText()
    {
        $result = $this->_testEntity(1, self::PAYLOAD_GET);
        $this->assertEquals($result instanceof RenderResponse, true);
    }

    public function testSeedPostTextRequiredFilled()
    {
        $result = $this->_testEntity(1, self::PAYLOAD_POST, 'test');

        $this->seeInDatabase('survey_answers', [
            'profile_id' => $this->profile->id,
            'entity_id' => 1,
            'answer' => json_encode('test')
        ]);
        $this->assertEquals($result instanceof RedirectResponse, true);
    }

    public function testSeedPostTextRequiredEmpty()
    {
        $result = $this->_testEntity(1, self::PAYLOAD_POST);
        $this->assertEquals($result instanceof ErrorResponse, true);
    }

    public function testSeedPostTextEmpty()
    {
        $result = $this->_testEntity(2, self::PAYLOAD_POST);
        $this->assertEquals($result instanceof RedirectResponse, true);
    }

    public function testSeedPostOpenRequiredFilled()
    {
        $result = $this->_testEntity(3, self::PAYLOAD_POST, 'test');

        $this->seeInDatabase('survey_answers', [
            'profile_id' => $this->profile->id,
            'entity_id' => 3,
            'answer' => json_encode('test')
        ]);
        $this->assertEquals($result instanceof RedirectResponse, true);
    }

    public function testSeedGetCheckbox()
    {
        $result = $this->_testEntity(5, self::PAYLOAD_GET);
        $this->assertEquals($result instanceof RenderResponse, true);
    }

    public function testSeedPostCheckboxRequiredFilled()
    {
        $result = $this->_testEntity(5, self::PAYLOAD_POST, [1]);
        $this->assertEquals($result instanceof RedirectResponse, true);
    }

    public function testSeedPostCheckboxRequiredEmpty()
    {
        $result = $this->_testEntity(5, self::PAYLOAD_POST, []);
        $this->assertEquals($result instanceof ErrorResponse, true);
    }

    public function testSeedPostCheckboxEmpty()
    {
        $result = $this->_testEntity(6, self::PAYLOAD_POST, []);
        $this->assertEquals($result instanceof RedirectResponse, true);
    }

    public function testSeedGetRadio()
    {
        $result = $this->_testEntity(5, self::PAYLOAD_GET);
        $this->assertEquals($result instanceof RenderResponse, true);
    }

    public function testSeedPostRadioRequiredFilled()
    {
        $result = $this->_testEntity(7, self::PAYLOAD_POST, 8);
        $this->assertEquals($result instanceof RedirectResponse, true);
    }

    public function testSeedPostRadioRequiredEmpty()
    {
        $result = $this->_testEntity(7, self::PAYLOAD_POST);
        $this->assertEquals($result instanceof ErrorResponse, true);
    }

    public function testSeedPostRadioEmpty()
    {
        $result = $this->_testEntity(8, self::PAYLOAD_POST);
        $this->assertEquals($result instanceof RedirectResponse, true);
    }

    public function testSeedPostRadioSkip()
    {
        $result = $this->_testEntity(8, self::PAYLOAD_POST, 14);
        $this->assertEquals($result instanceof RedirectResponse, true);
        $this->assertEquals($result->getToEntityId(), 10);
    }


    public function testSkipLogicToEndOfChain()
    {
        $survey = factory(Survey::class)->create([
            'user_id' => $this->user->id,
            'status' => 'draft',
        ]);

        $profile = $this->createProfile();

        $firstEntity = $this->_createEntityOfType($survey, 'q_radio');

        $optionId = $firstEntity->entity->options()->first()->id;

        $this->_createEntityOfType($survey, 'l_skip', [
            'option_id' => $optionId,
            'entity_id' => null
        ]);

        $this->_createEntityOfType($survey, 'q_open');
        $this->_createEntityOfType($survey, 'q_open');
        $this->_createEntityOfType($survey, 'q_open');

        $chain = new SurveyChain($profile, $survey, $firstEntity);

        $payload = new PostPayload($profile, $survey, $firstEntity, $optionId);
        $response = $chain->invokeRaw($payload);

        $this->assertEquals(get_class($response), RedirectResponse::class);
        $this->assertEquals($response->getToEntityId(), null);
    }

    public function testSkipLogicToEntity()
    {
        $survey = factory(Survey::class)->create([
            'user_id' => $this->user->id,
            'status' => 'draft',
        ]);

        $profile = $this->createProfile();

        $firstEntity = $this->_createEntityOfType($survey, 'q_radio');

        $optionId = $firstEntity->entity->options()->first()->id;

        $skip = $this->_createEntityOfType($survey, 'l_skip', [
            'option_id' => $optionId,
            'entity_id' => null
        ]);

        $this->_createEntityOfType($survey, 'q_open');
        $skipTo = $this->_createEntityOfType($survey, 'q_open');
        $this->_createEntityOfType($survey, 'q_open');

        $skip->entity->entity_id = $skipTo->id;
        $skip->entity->save();

        $chain = new SurveyChain($profile, $survey, $firstEntity);

        $payload = new PostPayload($profile, $survey, $firstEntity, $optionId);
        $response = $chain->invokeRaw($payload);

        $this->assertEquals(get_class($response), RedirectResponse::class);
        $this->assertEquals($response->getToEntityId(), $skipTo->id);
    }*/


    /*
     * Huge mofo test that tests way to much. This will create a survey with 100 entities and
     * tries to go through it and test if responses are all as expected.
     *
     * @todo split out in smaller tests
     */
    public function testSimulateChainTraversalAllIncluded()
    {
        unset($this->types['l_skip']);
        unset($this->types['q_image']);
        $survey = factory(Survey::class)->create([
            'user_id' => $this->user->id,
            'status' => 'draft',
        ]);

        $profile = $this->createProfile();

        $entities = [];
        for ($q = 0; $q < 100; $q++) {
            $entities[] = $this->_createRandomPresentableEntity($survey);
        }

        foreach ($entities as $n => $entity) {
            if ($entity->entity_type === Survey\Entity\Question\Radio::class) { // Add skip logic
                $this->_makeSkipLogic($entities, $entity, $n);
            }
        }

        $entities = $survey->entities()->orderBy('order')->get();

        // Test GET
        foreach ($entities as $n => $entity) {
            $chain = new SurveyChain($profile, $survey, $entity);
            $payload = new GetPayload($profile, $survey, $entity);

            $this->_assertResponse($payload, $entity, $chain);
        }

        // Test POST
        foreach ($entities as $entity) {
            $chain = new SurveyChain($profile, $survey, $entity);
            $answer = $this->_formatAnswerForEntity($entity);
            $payload = new PostPayload($profile, $survey, $entity, $answer);

            $this->_assertResponse($payload, $entity, $chain);
        }
    }

    private function _assertResponse(ChainPayload $payload, Survey\Entity $entity, SurveyChain $chain)
    {
        if ($payload instanceof GetPayload) {

            $response = null;

            if ($entity->isSubclassOf(Survey\Entity\Logic\BaseLogic::class)) {
                try {
                    $response = $chain->invokeRaw($payload);
                } catch (\Exception $e) {
                    $this->assertEquals($e instanceof InvalidPayloadException, true);
                    $this->assertEquals($e->getMessage(), "Logic entities cannot handle GET payloads.");
                }
                $this->assertNull($response);
            } else {
                $response = $chain->invokeRaw($payload);
            }

            if ($entity->isImplementationOf(CanBePresented::class)) {
                $this->assertEquals(RenderResponse::class, get_class($response));
            }

            if ($entity->isImplementationOf(CanBeAnswered::class)) {
                $this->assertEquals(RenderResponse::class, get_class($response));
            }


        } elseif ($payload instanceof PostPayload) {

            $response = null;

            if ($entity->isSubclassOf(Survey\Entity\Logic\BaseLogic::class)) {
                try {
                    $response = $chain->invokeRaw($payload);
                } catch (\Exception $e) {
                    $this->assertEquals($e instanceof InvalidPayloadException, true);
                    $this->assertEquals($e->getMessage(), "Skiplogic cannot function without an answer.");
                }

                $this->assertNull($response);

                return;
            }

            $response = $chain->invokeRaw($payload);

            $this->assertEquals(get_class($response), RedirectResponse::class);
        }
    }

    private function _createRandomPresentableEntity(Survey $survey)
    {
        return $this->_createEntityOfType($survey, array_rand($this->types));
    }

    private function _createEntityOfType(Survey $survey, $type, array $attributes = [])
    {
        $data = [];
        try {
            $data['entity_type'] = factory($this->types[$type])->make($attributes)->getAttributes();
        } catch (\Exception $e) {
            dd([$type, $e->getMessage()]);
        }
        $data['type'] = $type;
        $data['entity'] = ['survey_id' => $survey->id];

        if (with(new \ReflectionClass($this->types[$type]))->implementsInterface(RequiresOptions::class)) {
            $data['type_options'] = [];
            for ($n = 0; $n < $this->optionAmount; $n++) { // Add 5 options
                $data['type_options']['n' . $n] = $this->faker->sentence(4);
            }
        }

        $entity = $this->backendEntityRepository->create($data, $this->lastId);
        $this->lastId = $entity->id;

        return $entity;
    }

    private function _makeSkipLogic($entities, $entity, $current)
    {
        if ($current >= 95) {
            return;
        }

        $skip = [];
        for ($n = 0; $n < $this->optionAmount; $n++) {
            $r = rand(0, 3);

            if ($r === 0) {
                $skip[] = null;
            } elseif ($r === 1) {
                $skip[] = $entities[rand($current + 1, 99)]->id;
            } else {
                $skip[] = "";
            }
        }

        $this->backendEntityRepository->syncSkiplogic($entity->id, $skip);
    }


    private function _formatAnswerForEntity(Survey\Entity $entity)
    {
        if ($entity->isSubclassOf(Survey\Entity\Question\BaseQuestion::class)) {
            if ($entity->isImplementationOf(RequiresOptions::class)) {

                if ($entity->entity_type === Survey\Entity\Question\Checkbox::class) {
                    $answer = [$entity->entity->options()->first()->id];
                } else {
                    $answer = $entity->entity->options()->first()->id;
                }

            } else {
                $answer = 'This test is loco.';
            }
        } else {
            $answer = null;
        }

        return $answer;
    }

}
