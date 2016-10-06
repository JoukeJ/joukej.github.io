<?php namespace App\TTC\Repositories\Frontend;

use App\Exceptions\GeneralException;
use App\TTC\Models\Profile\Survey;

class ProfileSurveyRepository implements ProfileSurveyContract
{

    /**
     * @param array $input
     * @return Survey
     * @throws GeneralException
     */
    public function findOrCreate(array $input)
    {
        $profileSurvey = Survey::firstOrCreate([
            'status' => 'progress',
            'profile_id' => $input['profile_id'],
            'survey_id' => $input['survey_id'],
        ]);

        if (is_null($profileSurvey)) {
            throw new GeneralException("Could not find or create profile survey");
        }

        return $profileSurvey;
    }

    /**
     * @param $profileSurveyId
     * @return Survey
     * @throws GeneralException
     */
    public function findOrThrowException($profileSurveyId)
    {
        $profileSurvey = Survey::find($profileSurveyId);

        if (is_null($profileSurvey)) {
            throw new GeneralException("Cannot find this profile survey.");
        }

        return $profileSurvey;
    }

    /**
     * @param $profileSurveyId
     * @param array $input
     * @return bool
     * @throws GeneralException
     */
    public function update($profileSurveyId, array $input)
    {
        $profileSurvey = $this->findOrThrowException($profileSurveyId);

        $profileSurvey->fill($input);

        return $profileSurvey->save();
    }


}
