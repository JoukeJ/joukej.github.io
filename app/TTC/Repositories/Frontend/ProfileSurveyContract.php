<?php namespace App\TTC\Repositories\Frontend;

use App\Exceptions\GeneralException;
use App\TTC\Models\Profile\Survey;

interface ProfileSurveyContract
{

    /**
     * @param array $input
     * @return Survey
     * @throws GeneralException
     */
    public function findOrCreate(array $input);

    /**
     * @param $profileSurveyId
     * @return Survey
     * @throws GeneralException
     */
    public function findOrThrowException($profileSurveyId);

    /**
     * @param $profileSurveyId
     * @param array $input
     * @return bool
     * @throws GeneralException
     */
    public function update($profileSurveyId, array $input);
}
