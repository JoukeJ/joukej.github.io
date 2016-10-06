<?php namespace Test\TTC\Backend\Entity;

use App\TTC\Models\Survey;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Test\TTC\BaseTTCTest;

class RepositoryTest extends BaseTTCTest
{
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();

        $this->beSuperuser();
    }

    public function testFind()
    {
        $survey = factory(Survey::class)->create(['user_id' => $this->user->id]);

        $entity = factory(Survey\Entity\Question\Open::class)->create();

        $surveyEntity = factory(Survey\Entity::class)->create([
            'survey_id' => $survey->id,
            'entity_id' => $entity->id,
            'entity_type' => get_class($entity)
        ]);

        $this->backendEntityRepository->findOrThrowException($surveyEntity->id);
    }

    public function testFindThrowsExceptionWhenGivenSomethingElseThanInteger()
    {
        $this->setExpectedException('\App\Exceptions\GeneralException');

        $this->backendEntityRepository->findOrThrowException([0]);
    }

    public function testFindWithMorph()
    {
        $survey = factory(Survey::class)->create(['user_id' => $this->user->id]);

        $entity = factory(Survey\Entity\Question\Open::class)->create();

        $stubSurveyEntity = factory(Survey\Entity::class)->create([
            'survey_id' => $survey->id,
            'entity_id' => $entity->id,
            'entity_type' => get_class($entity)
        ]);

        $surveyEntity = $this->backendEntityRepository->findOrThrowException($stubSurveyEntity->id, true);

        $this->assertNotEmpty($surveyEntity->getRelation('entity'));
    }

    public function testFindFromIdentifier()
    {
        $survey = factory(Survey::class)->create(['user_id' => $this->user->id]);

        $entity = factory(Survey\Entity\Question\Open::class)->create();

        $stubSurveyEntity = factory(Survey\Entity::class)->create([
            'survey_id' => $survey->id,
            'entity_id' => $entity->id,
            'entity_type' => get_class($entity)
        ]);

        $surveyEntity = $this->backendEntityRepository->findFromIdentifierOrThrowException($stubSurveyEntity->identifier);

        $this->assertEquals($stubSurveyEntity->identifier, $surveyEntity->identifier);
    }

    public function testFindFromIdentieferThrowException()
    {
        $this->setExpectedException('App\Exceptions\GeneralException');

        $this->backendEntityRepository->findFromIdentifierOrThrowException(0);
    }

    public function testFindFromIdentifierWithMorph()
    {
        $survey = factory(Survey::class)->create(['user_id' => $this->user->id]);

        $entity = factory(Survey\Entity\Question\Open::class)->create();

        $stubSurveyEntity = factory(Survey\Entity::class)->create([
            'survey_id' => $survey->id,
            'entity_id' => $entity->id,
            'entity_type' => get_class($entity)
        ]);

        $surveyEntity = $this->backendEntityRepository->findFromIdentifierOrThrowException($stubSurveyEntity->identifier,
            true);

        $this->assertNotEmpty($surveyEntity->getRelation('entity'));
    }

    public function testFindEntityOption()
    {
        $entity = factory(Survey\Entity\Question\Checkbox::class)->create();

        $check = factory(Survey\Entity\Option::class)->create([
            'entity_id' => $entity->id,
            'entity_type' => get_class($entity)
        ]);

        $entityOption = $this->backendEntityRepository->findEntityOptionOrThrowException($check->id);

        $this->assertNotNull($entityOption);
    }

    public function testFindEntityOptionThrowsException()
    {
        $this->setExpectedException('App\Exceptions\GeneralException');

        $this->backendEntityRepository->findEntityOptionOrThrowException(0);
    }

    public function testCreateEntityQuestions()
    {
        $survey = $this->getSurvey(['status' => 'draft']);

        $types = \Config::get('ttc.entity.types');

        foreach ($types as $name => $class) {

            if (strpos($name, 'q_') !== 0) { // only test questions
                continue;
            }

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
                    'identifier' => str_random(16),
                ],
                'type' => $name,
                'entity_type' => $typeAttributes,
                'type_options' => $typeOptions
            ]);

            $entity = $this->backendEntityRepository->create(\Input::all());

            $this->assertEquals($survey->id, $entity->survey_id);
            $this->assertEquals($class, $entity->entity_type);
            $this->assertEquals($entity->entity->question, $typeAttributes['question']);
            $this->assertEquals($entity->entity->description, $typeAttributes['description']);

            if ($entity->entity->mustHaveOptions) {
                $entityType = $entity->entity;

                $this->assertEquals(1, $entityType->options()->where('value', '=', $typeOptions['a'])->count());
                $this->assertEquals(4, $entityType->options()->count());
            }
        }
    }

    public function testCreateEntityThrowsException()
    {
        $this->setExpectedException('App\Exceptions\GeneralException');

        $survey = $this->getSurvey();

        $this->backendEntityRepository->create(['identifier' => null, 'survey_id' => $survey->id]);
    }

    public function testUpdateEntityQuestionWithOptions()
    {
        $entity = $this->createEntityRadioQuestion();

        $typeOptions = [
            $entity->entity->options[0]->id => 'Updated value 1',
            $entity->entity->options[1]->id => 'Updated value 2',
            // do not add option[2], here we simulate it being deleted
            $entity->entity->options[3]->id => 'Updated value 4',
            'new option 1' => 'New value 1',
            'new option 2' => 'New value 2'
        ];

        // change question, description
        $typeAttributes = factory(Survey\Entity\Question\Open::class)->make()->getAttributes();
        $newIdentifier = str_random(16);

        \Input::replace([
            'entity' => [
                'identifier' => $newIdentifier, // this should not change
            ],
            'type' => 'q_open', // this should not change
            'entity_type' => $typeAttributes,
            'type_options' => $typeOptions
        ]);

        $updatedEntity = $this->backendEntityRepository->update($entity->id, \Input::all());


        // Identifier
        $this->assertNotEquals($newIdentifier, $updatedEntity->identifier);
        $this->assertEquals($entity->identifier, $updatedEntity->identifier);

        // Type must not be updated
        $this->assertEquals($entity->entity_type, $updatedEntity->entity_type);

        // Should be updated to the new options ($typeOptions)
        $this->assertEquals(5, $updatedEntity->entity->options()->count());

        // Check if option values are correct
        foreach ($updatedEntity->entity->options as $option) {
            $this->assertEquals(in_array($option->value, $typeOptions), true);
        }

        // Question attrs must be changed
        $this->assertEquals($typeAttributes['question'], $updatedEntity->entity->question);
        $this->assertEquals($typeAttributes['description'], $updatedEntity->entity->description);
    }

    public function testCreateEntityWithWrongAfterId()
    {
        $this->setExpectedException('\App\Exceptions\GeneralException');

        $entity = $this->createEntityRadioQuestion();
        $this->createEntityRadioQuestion($entity->survey_id);
    }

    public function testDeleteEntity()
    {
        $this->setExpectedException('App\Exceptions\GeneralException');

        $entity = $this->createEntityRadioQuestion();

        $this->backendEntityRepository->delete($entity->id);

        $this->backendEntityRepository->findOrThrowException($entity->id);
    }
}
