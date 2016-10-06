<?php namespace App\TTC\Repositories\Backend;

use App\Exceptions\GeneralException;
use App\TTC\Models\Profile;
use App\TTC\Models\Survey;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

/**
 * Class SurveyRepository
 * @package App\TTC\Repositories\Backend
 */
class SurveyRepository implements SurveyContract
{
    /**
     * @param $id
     * @param bool $withEntities
     * @param bool $withMatchgroups
     * @param bool $withRepeat
     * @return Survey
     * @throws GeneralException
     */
    public function findOrThrowException($id, $withEntities = false, $withMatchgroups = false, $withRepeat = false)
    {
        if (!is_numeric($id)) {
            throw new GeneralException("Id is not an integer");
        }

        $with = [];

        if ($withEntities) {
            $with[] = 'entities';
        }

        if ($withMatchgroups) {
            $with[] = 'matchgroups';
            $with[] = 'matchgroups.rules';
        }

        if ($withRepeat) {
            $with[] = 'repeat';
        }

        $survey = Survey::with($with)->find($id);

        if (is_null($survey)) {
            throw new GeneralException("Cannot find this survey. Please try again.");
        }

        return $survey;
    }

    /**
     * @param int $perPage
     * @param string $orderBy
     * @param string $sort
     * @param array $where
     * @return mixed
     */
    public function getSurveysPaginated($perPage, $orderBy = 'created_at', $sort = 'asc', $where = [])
    {
        return $this->getSurveysPaginatedByUser(null, $perPage, $orderBy, $sort, $where);
    }

    /**
     * @param int $userId
     * @param int $perPage
     * @param string $orderBy
     * @param string $sort
     * @param array $where
     * @return mixed
     */
    public function getSurveysPaginatedByUser($userId, $perPage, $orderBy = 'created_at', $sort = 'asc', $where = [])
    {
        $surveys = $this->getSurveysQuery($orderBy, $sort, $userId, $where);

        return $surveys->paginate($perPage);
    }

    /**
     * @param string $orderBy
     * @param string $sort
     * @param null $userId
     * @param array $where
     * @return mixed
     */
    public function getSurveysQuery($orderBy = 'created_at', $sort = 'asc', $userId = null, $where = [])
    {
        $surveys = Survey::orderBy($orderBy, $sort);

        if ($userId !== null) {
            $surveys->whereUserId($userId);
        }

        foreach ($where as $k => $v) {
            if (is_array($v)) {
                if (sizeof($v) === 3) {
                    $surveys = $surveys->where($v[0], $v[1], $v[2]);
                } elseif (sizeof($v) === 2) {
                    $surveys = $surveys->where($v[0], $v[1]);
                }
            } else {
                $surveys = $surveys->where($k, '=', $v);
            }
        }

        return $surveys;
    }


    /**
     * @param $input
     * @throws GeneralException
     * @return Survey
     */
    public function create($input)
    {
        try {
            $survey = $this->createStub($input);

            $survey->status = 'draft';
            $survey->identifier = $this->generateSurveyIdentifier();

            if ($survey->save()) {
                return $survey;
            }
        } catch (QueryException $e) {
            throw new GeneralException("Queryexception: " . $e->getMessage());
        }

        throw new GeneralException("Cannot create this survey. Please try again.");
    }

    /**
     * @param $id
     * @param $input
     * @throws GeneralException
     * @return Survey
     */
    public function update($id, $input)
    {
        try {
            $survey = $this->findOrThrowException($id);

            $survey->fill($input);

            if ($survey->save()) {
                return $survey;
            }
        } catch (QueryException $e) {
            throw new GeneralException("Queryexception: " . $e->getMessage());
        }

        throw new GeneralException("This survey could not be updated. Please try again.");
    }

    /**
     * @param $id
     * @throws GeneralException
     * @return bool
     */
    public function delete($id)
    {
        $survey = $this->findOrThrowException($id);

        if ($survey->status === 'cancelled') {
            return Survey::destroy($id) === 1;
        }

        return false;
    }


    /**
     * Creates a survey from $input, this will not save the object
     *
     * @param $input
     * @return Survey
     */
    protected function createStub($input)
    {
        $survey = new Survey();

        $survey->fill($input);

        return $survey;
    }

    /**
     * Set matchgroups and their rules of survey
     *
     * @param $id
     * @param $input
     * @param bool $replace
     * @throws GeneralException
     * @throws \Exception
     * @return void
     */
    public function setMatchGroups($id, $input, $replace = false)
    {
        $survey = $this->findOrThrowException($id);

        if ($replace) {
            foreach ($survey->matchgroups as $matchgroup) {
                foreach ($matchgroup->rules as $rule) {
                    $rule->delete();
                }
                $matchgroup->delete();
            }
        }

        foreach ($input as $matchgroupSet) {
            if (isset($matchgroupSet['id']) && !$replace) {
                $matchgroup = Survey\Matchgroup::findOrFail($matchgroupSet['id']);
            } else {
                $matchgroup = $survey->matchgroups()->create(array_except($matchgroupSet, 'rules'));
            }

            foreach ($matchgroupSet['rules'] as $ruleSet) {
//                if (json_encode([]) == $ruleSet['values']) {
//                    continue;
//                }

                if (isset($ruleSet['id']) && !$replace) {
                    $rule = Survey\Matchrule::findOrFail($ruleSet['id']);
                } else {
                    $rule = $matchgroup->rules()->firstOrNew(array_merge($ruleSet,
                        ['matchgroup_id' => $matchgroup->id]));
                }

                $rule->fill(array_except($ruleSet, ['matchgroup_id']));

                $rule->save();

            }
        }
    }

    /**
     * Set repeat of survey
     *
     * @param $id
     * @param $input
     * @throws GeneralException
     * @return Survey\Repeat
     */
    public function setRepeat($id, $input)
    {
        $survey = $this->findOrThrowException($id);

        if (is_null($repeat = $survey->repeat)) {
            $repeat = new Survey\Repeat();
            $repeat->survey_id = $id;
        }

        $repeat->fill($input);

        if ($repeat->save() === false) {
            throw new GeneralException("Could not save this Survey Repeat.");
        }

        return $repeat;
    }

    /**
     * @param $surveyId
     * @return void
     */
    public function unsetRepeat($surveyId)
    {
        Survey\Repeat::whereSurveyId($surveyId)->delete();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return Survey::all();
    }

    /**
     * @return array|\Illuminate\Database\Eloquent\Collection|static[]
     */
    public function allOpen()
    {
        return Survey::whereStatus('open')->get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function allOpenAndActive()
    {
        return Survey::whereStatus('open')
            ->where('start_date', '<', date('Y-m-d H:i'))
            ->where('end_date', '>', date('Y-m-d H:i'))
            ->get();
    }

    /**
     * Generate a new unique Survey identifier
     * @return string
     */
    protected function generateSurveyIdentifier()
    {
        $length = 8;

        $identifier = str_random($length);
        while (Survey::whereIdentifier($identifier)->count() !== 0) {
            $identifier = str_random($length);
        }

        return $identifier;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteMatchGroup($id)
    {
        $matchgroup = Survey\Matchgroup::findOrFail($id);

        foreach ($matchgroup->rules as $rule) {
            $rule->delete();
        }

        $matchgroup->delete();
    }

    /**
     * @param Profile $profile
     * @return \Illuminate\Support\Collection
     */
    public function allOpenAndActiveAndEligible(Profile $profile)
    {
        $date = date('Y-m-d H:i');

        $surveys = new Collection();

        $rawSurveys = \DB::select("SELECT * FROM surveys
                      WHERE status = 'open'
                      AND start_date < :start_date
                      AND end_date > :end_date
                      AND id NOT IN (SELECT survey_id FROM profile_surveys WHERE profile_id = :profile_id AND previous = 0 AND (status = 'completed' OR status = 'abandoned'))",
            [
                'start_date' => $date,
                'end_date' => $date,
                'profile_id' => $profile->id,
            ]);

        foreach ($rawSurveys as $rawSurvey) {
            $survey = new Survey();

            $survey->setRawAttributes((array)$rawSurvey, true);

            $surveys->push($survey);
        }

        return $surveys;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function allOpenAndExpired()
    {
        return Survey::whereStatus('open')
            ->where('end_date', '<', date('Y-m-d H:i'))
            ->get();
    }
}
