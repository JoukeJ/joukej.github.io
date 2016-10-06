<?php


namespace App\TTC\Repositories\Frontend;


use App\Exceptions\GeneralException;
use App\TTC\Models\Survey\Entity;
use Illuminate\Database\Eloquent\Collection;

interface EntityContract
{

    /**
     * @param $id
     * @throws GeneralException
     * @return Entity
     */
    public function findOrThrowException($id);

    /**
     * @param $identifier
     * @throws GeneralException
     * @return Entity
     */
    public function findByIdentifierOrThrowException($identifier);

    /**
     * @param $surveyId
     * @param null|Entity $fromEntity
     * @return Entity[]|Collection
     */
    public function getOrderedEntitiesBySurveyId($surveyId, $fromEntity = null);
}
