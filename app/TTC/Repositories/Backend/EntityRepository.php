<?php namespace App\TTC\Repositories\Backend;

use App\Exceptions\GeneralException;
use App\TTC\Factories\EntityFactory;
use App\TTC\Models\Survey;
use App\TTC\Models\Survey\Entity;
use App\TTC\Models\Survey\Entity\Logic\Skip;
use App\TTC\Tags\Entity\CanSkipLogic;
use App\TTC\Tags\Entity\RequiresOptions;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class EntityRepository implements EntityContract
{
    const orderIncrease = 1000000;

    /**
     * @var SurveyContract
     */
    private $surveyRepository;

    /**
     * EntityRepository constructor.
     * @param SurveyContract $surveyRepository
     */
    public function __construct(SurveyContract $surveyRepository)
    {
        $this->surveyRepository = $surveyRepository;
    }

    /**
     * @param $id
     * @param bool|false $withMorph
     * @throws GeneralException
     * @return Entity
     */
    public function findOrThrowException($id, $withMorph = false)
    {
        if (!is_numeric($id)) {
            throw new GeneralException("Id is not of type integer");
        }

        if ($withMorph) {
            $entity = Entity::with('entity')->find($id);
        } else {
            $entity = Entity::find($id);
        }

        if (is_null($entity)) {
            throw new GeneralException("Cannot find this entity. Please try again.");
        }

        return $entity;
    }

    /**
     * @param $identifier
     * @param bool|false $withMorph
     * @return Entity
     * @throws GeneralException
     */
    public function findFromIdentifierOrThrowException($identifier, $withMorph = false)
    {
        if ($withMorph) {
            $entity = Entity::with('entity')->whereIdentifier($identifier)->first();
        } else {
            $entity = Entity::whereIdentifier($identifier)->first();
        }

        if (is_null($entity)) {
            throw new GeneralException("Cannot find entity with given hash. Please try again.");
        }

        return $entity;
    }

    /**
     * @param $id
     * @throws GeneralException
     * @return Entity\Option
     */
    public function findEntityOptionOrThrowException($id)
    {
        if (!is_numeric($id)) {
            throw new GeneralException("Id is not of type integer");
        }

        $option = Entity\Option::find($id);

        if (is_null($option)) {
            throw new GeneralException("Could not find option. Please try again.");
        }

        return $option;
    }


    /**
     * If $afterId === 0 then it will make the entity the first in order
     * @param array $input
     * @param int|null $afterId
     * @throws GeneralException
     * @return Entity
     */
    public function create($input, $afterId = null)
    {
        $entity = $this->createStub(Arr::get($input, 'entity'));
        $entityType = $this->createTypeStub(Arr::get($input, 'type'), Arr::get($input, 'entity_type'));

        $survey = $this->surveyRepository->findOrThrowException(Arr::get($input, 'entity.survey_id'));
        $this->checkSurveyStatus($survey);

        if ($entityType instanceof RequiresOptions) {
            if (sizeof(Arr::get($input, 'type_options', [])) === 0) {
                throw new GeneralException("This entity type requires options while none are given. Please try again.");
            }
        }

        // Check if afterId belongs to survey_id
        if ((int)$afterId !== null && (int)$afterId !== 0) {
            $afterEntity = $this->findOrThrowException($afterId);

            if ((int)$afterEntity->survey_id !== (int)Arr::get($input, 'entity.survey_id')) {
                throw new GeneralException("Previous entity does not belong to given survey.");
            }
        }

        if (sizeof($entityType->files) > 0) {
            $this->handleEntityFiles($entityType);
        }

        $entity->order = $this->getEntityOrder($afterId, Arr::get($input, 'entity.survey_id'));

        if ($entityType->save()) { // Save the sub-entity

            $entity->entity_id = $entityType->id;
            $entity->entity_type = get_class($entityType);

            if ($entity->save()) { // Save the entity

                if ($entityType instanceof RequiresOptions) {
                    $this->addOptionToEntity($input, $entityType);
                }

                return $entity;
            }
        }

        throw new GeneralException("Could not create this entity. Please try again.");
    }

    /**
     * @param $id
     * @param $input
     * @return Entity
     * @throws GeneralException
     */
    public function update($id, $input)
    {
        $entity = $this->findOrThrowException($id, true);

        $this->checkSurveyStatus($entity->survey);

        $entityType = $entity->entity;

        if ($entityType instanceof RequiresOptions) {
            if (sizeof(Arr::get($input, 'type_options', [])) === 0) {
                throw new GeneralException("This entity type needs options while none are given. Please try again.");
            }
        }

        if (sizeof($entityType->files) > 0) {
            $this->handleEntityFiles($entityType);
        }

        $entityType->fill(Arr::get($input, 'entity_type'));
        $entityType->afterFill();

        if ($entityType->save()) {

            if ($entityType instanceof RequiresOptions) {
                $this->deleteSkipLogic($entityType);
                $this->addOptionToEntity($input, $entityType);
            }

            return $entity;
        }

        throw new GeneralException("Could not update this entity. Please try again.");
    }

    /**
     * @todo write test to see if file gets deleted
     * @param $id
     * @throws GeneralException
     * @return bool
     */
    public function delete($id)
    {
        $entity = $this->findOrThrowException($id);

        $this->checkSurveyStatus($entity->survey);

        if (sizeof($entity->entity->files) > 0) {
            foreach ($entity->entity->files as $file) {
                $this->deleteEntityTypeFile($entity->entity->{$file});
            }
        }

        $entity->entity->delete();

        return Entity::destroy($id) === 1;
    }

    /**
     * @param int $id
     * @param int $afterId
     * @throws GeneralException
     * @return Entity
     */
    public function move($id, $afterId)
    {
        $entity = $this->findOrThrowException($id);

        $this->checkSurveyStatus($entity->survey);

        $entity->order = $this->getEntityOrder($afterId, $entity->survey_id);

        if ($entity->save()) {
            return $entity;
        }

        throw new GeneralException("Could not update the entity. Please try again.");
    }

    /**
     * @param int $entityId
     * @param string[] $skiplogic
     * @return Entity\Logic\Skip[]|Collection
     * @throws bool
     */
    public function syncSkiplogic($entityId, $skiplogic)
    {
        $entity = $this->findOrThrowException($entityId);
        if ($entity->isImplementationOf(CanSkipLogic::class) === false) {
            return false;
        }

        $skipCollection = Collection::make($skiplogic)->reverse();

        $options = $entity->entity->options()->orderBy('id')->get()->reverse();

        foreach ($options as $option) {
            $shift = $skipCollection->shift();
            if (is_null($shift) || empty($shift) === false) {

                $skiplogicAttributes = [
                    'type' => 'l_skip',
                    'entity' => [
                        'survey_id' => $entity->survey_id,
                    ],
                    'entity_type' => [
                        'option_id' => $option->id,
                        'entity_id' => $shift
                    ]
                ];

                $this->create($skiplogicAttributes, $entityId);
            }
        }
    }

    /**
     * @param $input
     * @return Entity
     */
    protected function createStub($input)
    {
        $entity = new Entity();

        $entity->identifier = $this->generateEntityIdentifier(); // not fillable
        $entity->survey_id = $input['survey_id']; // not fillable

        return $entity;
    }

    /**
     * @param $type
     * @param $input
     * @return Entity\BaseEntity
     * @throws GeneralException
     */
    protected function createTypeStub($type, $input)
    {
        $type = EntityFactory::make($type);

        $type->initNew();
        $type->fill($input);
        $type->afterFill();

        return $type;
    }

    /**
     * @param $afterId
     * @param $surveyId
     * @return int
     * @throws GeneralException
     * @internal param $entity
     */
    protected function getEntityOrder($afterId, $surveyId)
    {
        if ($afterId !== null) {

            if ((int)$afterId !== 0) {
                $previousEntity = $this->findOrThrowException($afterId);

                $nextEntity = Entity::where('order', '>', $previousEntity->order)
                    ->whereSurveyId($previousEntity->survey_id)
                    ->orderBy('order', 'asc')
                    ->first();

                if (is_null($nextEntity)) {
                    return $previousEntity->order + self::orderIncrease;
                } else {
                    // Set ordering between prev and next entity. E.g. prev 100, next 200, this 150
                    return $previousEntity->order + floor(($nextEntity->order - $previousEntity->order) / 2);
                }
            } else {
                $firstEntity = Entity::whereSurveyId($surveyId)
                    ->orderBy('order', 'asc')
                    ->first();

                if (!is_null($firstEntity)) {
                    return floor($firstEntity->order / 2);
                }
            }
        }

        return self::orderIncrease;
    }

    /**
     * Generate a new unique Entity identifier
     * @return string
     */
    protected function generateEntityIdentifier()
    {
        $length = 16;

        $identifier = str_random($length);
        while (Entity::whereIdentifier($identifier)->count() !== 0) {
            $identifier = str_random($length);
        }

        return $identifier;
    }

    /**
     * @param Survey $survey
     * @throws GeneralException
     */
    protected function checkSurveyStatus(Survey $survey)
    {
        if ($survey->status !== 'draft') {
            throw new GeneralException("Cannot create/update/delete Entity with Survey not in draft.");
        }
    }

    /**
     * @param $entityType
     */
    protected function handleEntityFiles($entityType)
    {
        foreach ($entityType->files as $file) {
            if (\Input::hasFile($file)) {

                $this->deleteEntityTypeFile($entityType->{$file});

                $filename = str_random(64);
                $prefix = substr($filename, 0, 2);
                $saveDir = storage_path('ttc' . DIRECTORY_SEPARATOR . 'entity' . DIRECTORY_SEPARATOR . $prefix . DIRECTORY_SEPARATOR);

                \Input::file($file)->move($saveDir, $filename);

                $relativePath = str_replace(storage_path(), '', $saveDir) . $filename;

                $entityType->{$file} = $relativePath;
            }
        }
    }

    /**
     * @param $file
     */
    protected function deleteEntityTypeFile($file)
    {
        $existingFile = storage_path($file);
        if (\File::exists($existingFile)) {
            \File::delete($existingFile);
        }
    }

    /**
     * @param Entity\BaseEntity $entityType
     */
    protected function deleteSkipLogic(Entity\BaseEntity $entityType)
    {
        $currentOptions = $entityType->options()->lists('id');

        $currentSkips = Entity\Logic\Skip::whereIn('option_id', $currentOptions)->lists('id');
        $deleteEntities = Entity::whereIn('entity_id', $currentSkips)->whereEntityType(Skip::class)->lists('id');

        foreach ($deleteEntities as $deleteEntityId) {
            $this->delete($deleteEntityId);
        }
    }

    /**
     * @param $input
     * @param $entityType
     * @throws GeneralException
     */
    protected function addOptionToEntity($input, $entityType)
    {
        $existingOptions = [];
        foreach (Arr::get($input, 'type_options', []) as $optionId => $value) { // Attach options

            if (is_numeric($optionId)) { // update the old option with a new value
                $option = $this->findEntityOptionOrThrowException($optionId);
            } else { // this is a new option
                $option = new Entity\Option();

                $option->name = 'option';
                $option->entity_type = get_class($entityType);
                $option->entity_id = $entityType->id;
            }

            $option->value = $value;

            if ($option->save() === false) {
                throw new GeneralException("Could not save entity option. Please try again.");
            }

            $existingOptions[] = $option->id;
        }

        Entity\Logic\Skip::whereIn('option_id', $existingOptions);

        $optionDelete = $entityType->options()->whereNotIn('id', $existingOptions)->lists('id');
        Entity\Option::destroy($optionDelete->toArray());
    }

}
