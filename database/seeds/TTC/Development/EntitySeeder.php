<?php namespace TTC\Development;

use App\TTC\Factories\EntityFactory;
use App\TTC\Models\Survey;
use App\TTC\Models\Survey\Entity;
use App\TTC\Repositories\Backend\EntityContract;
use App\TTC\Repositories\Backend\EntityRepository;
use App\TTC\Tags\Entity\CanSkipLogic;
use Faker\Factory;
use Illuminate\Database\Seeder;

class EntitySeeder extends Seeder
{

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var EntityContract
     */
    private $backendEntityRepository;

    /**
     *
     */
    public function __construct()
    {
        $this->faker = Factory::create();
        $this->backendEntityRepository = \App::make(EntityContract::class);
    }

    public function run()
    {
        $types = \Config::get('ttc.entity.types');

        // Disable these types for now
        unset($types['l_skip']);

        $surveys = Survey::all();

        foreach ($surveys as $survey) {
            $max = rand(5, 15);
            for ($i = 1; $i <= $max; $i++) { // create 10 entities
                $this->createType(array_rand($types), $survey->id, $i * EntityRepository::orderIncrease);
            }

            $entities = $survey->entities()->orderBy('order', 'asc')->get();

            $oldStatus = $survey->status;
            $survey->status = 'draft';
            $survey->save();
            foreach ($entities as $n => $entity) {
                if ($entity->isImplementationOf(CanSkipLogic::class)) {
                    $this->_makeSkipLogic($entities, $entity, $n, sizeof($entities));
                }
            }

            $survey->status = $oldStatus;
            $survey->save();
        }
    }


    /**
     * @param $type
     * @param $surveyId
     * @param $order
     * @throws \App\Exceptions\GeneralException
     * @internal param $entityType
     */
    protected function createType($type, $surveyId, $order)
    {
        $entityType = EntityFactory::make($type);

        $attrs = factory(get_class($entityType))->make()->getAttributes();

        $entityType->fill($attrs);

        $entityType->save();

        if ($type === 'q_radio' || $type === 'q_checkbox') {
            for ($q = 0; $q < 4; $q++) {
                $option = Entity\Option::create([
                    'name' => 'option',
                    'value' => $this->faker->sentence(3),
                    'entity_type' => get_class($entityType),
                    'entity_id' => $entityType->id
                ])->save();
            }
        }

        factory(Entity::class)->create([
            'survey_id' => $surveyId,
            'entity_type' => get_class($entityType),
            'entity_id' => $entityType->id,
            'order' => $order
        ]);
    }

    private function _makeSkipLogic($entities, $entity, $current, $max)
    {
        if ($current >= $max - 3) {
            return;
        }

        $skip = [];
        for ($n = 0; $n < 4; $n++) {
            $skip[] = rand(0, 2) ? null : $entities[rand($current + 1, $max - 1)]->id;
        }

        $this->backendEntityRepository->syncSkiplogic($entity->id, $skip);
    }
}
