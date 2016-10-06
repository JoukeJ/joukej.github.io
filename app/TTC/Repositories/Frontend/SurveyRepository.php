<?php namespace App\TTC\Repositories\Frontend;

use App\Exceptions\GeneralException;
use App\TTC\Models\Survey;

class SurveyRepository implements SurveyContract
{
    /**
     * @param $id
     * @throws GeneralException
     * @return Survey
     */
    public function findOrThrowException($id)
    {
        $survey = Survey::find($id);

        if (is_null($survey)) {
            throw new GeneralException("Cannot find survey with this id.");
        }

        return $survey;
    }

    /**
     * @param $identifier
     * @throws GeneralException
     * @return Survey
     */
    public function findByIdentifierOrThrowException($identifier)
    {
        $survey = Survey::whereIdentifier($identifier)->first();

        if (is_null($survey)) {
            throw new GeneralException("Cannot find survey with this identifier.");
        }

        return $survey;
    }

}
