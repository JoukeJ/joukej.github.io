<?php namespace App\TTC\Repositories\Frontend;

use App\Exceptions\GeneralException;
use App\TTC\Models\Survey;

interface SurveyContract
{

    /**
     * @param $id
     * @throws GeneralException
     * @return Survey
     */
    public function findOrThrowException($id);

    /**
     * @param $identifier
     * @throws GeneralException
     * @return Survey
     */
    public function findByIdentifierOrThrowException($identifier);

}
