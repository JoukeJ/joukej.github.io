<?php namespace App\TTC\Repositories\Frontend;

use App\Exceptions\GeneralException;
use App\TTC\Models\Survey\Entity;

class EntityRepository implements EntityContract
{

    /**
     * @var SurveyContract
     */
    protected $surveys;

    /**
     * EntityRepository constructor.
     * @param SurveyContract $surveys
     */
    public function __construct(SurveyContract $surveys)
    {
        $this->surveys = $surveys;
    }

    /**
     * @todo write test
     * @param $id
     * @throws GeneralException
     * @return Entity
     */
    public function findOrThrowException($id)
    {
        $entity = Entity::whereIdentifier($id)->first();

        if (is_null($entity)) {
            throw new GeneralException("Cannot find entity with this id.");
        }

        return $entity;
    }


    /**
     * @todo write test
     * @param $identifier
     * @throws GeneralException
     * @return Entity
     */
    public function findByIdentifierOrThrowException($identifier)
    {
        $entity = Entity::whereIdentifier($identifier)->first();

        if (is_null($entity)) {
            throw new GeneralException("Cannot find entity with this identifier.");
        }

        return $entity;
    }

    /**
     * @todo write test
     * @param $surveyId
     * @param null|Entity $fromEntity
     * @return \App\TTC\Models\Survey\Entity[]
     * @throws GeneralException
     */
    public function getOrderedEntitiesBySurveyId($surveyId, $fromEntity = null)
    {
        $survey = $this->surveys->findOrThrowException($surveyId);

        try {
            $entities = $survey->entities()
                ->orderBy('order', 'asc');

            if ($fromEntity !== null) {
                $entities = $entities->where('order', '>=', $fromEntity->order);
            }
        } catch (\Exception $e) {
            throw new GeneralException("Error getting entities (" . $e->getMessage() . ")");
        }

        return $entities->get();
    }


}
