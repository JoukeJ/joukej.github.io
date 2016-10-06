<?php


namespace App\TTC\Repositories\Backend;


use App\Exceptions\GeneralException;
use App\TTC\Models\Profile;
use App\TTC\Models\Survey;

interface SurveyContract
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function all();

    /**
     * @return \Illuminate\Support\Collection
     */
    public function allOpen();

    /**
     * @return \Illuminate\Support\Collection
     */
    public function allOpenAndActive();

    /**
     * @return \Illuminate\Support\Collection
     */
    public function allOpenAndExpired();

    /**
     * @param Profile $profile
     * @return \Illuminate\Support\Collection
     */
    public function allOpenAndActiveAndEligible(Profile $profile);

    /**
     * @param $id
     * @param bool $withEntities
     * @throws GeneralException
     * @return Survey
     */
    public function findOrThrowException($id, $withEntities = false);

    /**
     * @param int $perPage
     * @param string $orderBy
     * @param string $sort
     * @param array $where
     * @return mixed
     */
    public function getSurveysPaginated($perPage, $orderBy = 'created_at', $sort = 'asc', $where = []);

    /**
     * @param int $userId
     * @param int $perPage
     * @param string $orderBy
     * @param string $sort
     * @param array $where
     * @return mixed
     */
    public function getSurveysPaginatedByUser($userId, $perPage, $orderBy = 'created_at', $sort = 'asc', $where = []);

    /**
     * @param string $orderBy
     * @param string $sort
     * @param null $userId
     * @param array $where
     * @return mixed
     */
    public function getSurveysQuery($orderBy = 'created_at', $sort = 'asc', $userId = null, $where = []);

    /**
     * @param $input
     * @throws GeneralException
     * @return Survey
     */
    public function create($input);

    /**
     * @param $id
     * @param $input
     * @throws GeneralException
     * @return Survey
     */
    public function update($id, $input);

    /**
     * @param $id
     * @param $input
     * @param bool|false $replace
     * @throws GeneralException
     * @return void
     */
    public function setMatchGroups($id, $input, $replace = false);

    /**
     * @param $id
     * @param $input
     * @throws GeneralException
     * @return Survey\Repeat
     */
    public function setRepeat($id, $input);

    /**
     * @param $surveyId
     * @return void
     */
    public function unsetRepeat($surveyId);

    /**
     * @param $id
     * @return mixed
     */
    public function deleteMatchGroup($id);

    /**
     * @param $id
     * @throws GeneralException
     * @return bool
     */
    public function delete($id);
}
