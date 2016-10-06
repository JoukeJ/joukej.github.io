<?php

namespace App\TTC\Http\Controllers\Backend\Survey;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\TTC\Http\Request\Backend\Survey\Matchgroup\CreateMatchgroupRequest;
use App\TTC\Http\Request\Backend\Survey\Matchgroup\DeleteMatchgroupRequest;
use App\TTC\Http\Request\Backend\Survey\Matchgroup\UpdateMatchgroupRequest;
use App\TTC\Jobs\Backend\Survey\MatchMaker\CreateMatchgroupJob;
use App\TTC\Jobs\Backend\Survey\MatchMaker\DeleteMatchgroupJob;
use App\TTC\Jobs\Backend\Survey\MatchMaker\UpdateMatchgroupJob;
use App\TTC\Models\Survey;
use App\TTC\Repositories\Backend\SurveyContract;

class MatchgroupController extends Controller
{
    /**
     * @var SurveyContract
     */
    private $surveys;

    /**
     * @var MatchgroupContract
     */
    private $matchgroups;

    /**
     * @param SurveyContract $surveys
     */
    function __construct(SurveyContract $surveys)
    {
        $this->surveys = $surveys;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $survey_id
     * @return Response
     */
    public function create($survey_id)
    {
        $survey = Survey::findOrFail($survey_id);

        $attr_togo = config('ttc.survey.matchgroups.attributes');

        return view("ttc.backend.surveys.matchgroups.create", [
            'title' => trans('survey/matchgroups/list.title'),
            'survey' => $survey,
            'attr_togo' => $attr_togo
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $survey_id
     * @param CreateMatchgroupRequest $request
     * @return Response
     */
    public function store($survey_id, CreateMatchgroupRequest $request)
    {
        $survey = Survey::findOrFail($survey_id);
        $newRules = [];
        foreach (\Input::get('rules') as $num => $rule) {
            $newRules[$num] = $rule;
            $newRules[$num]['values'] = json_encode($rule['values']);
        }
        \Input::merge(['rules' => $newRules]);
        $matchgroup = $this->dispatch(app(CreateMatchgroupJob::class, [\Input::all()]));

        if ($matchgroup !== null) {
            \Notification::success(trans('survey/entities/create.success'));
        }

        return \Redirect::route('survey.show', [$survey_id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $survey_id
     * @param $matchgroup_id
     * @return Response
     * @internal param int $id
     */
    public function edit($survey_id, $matchgroup_id)
    {
        $survey = Survey::findOrFail($survey_id);
        $matchgroup = Survey\Matchgroup::findOrFail($matchgroup_id);

        $attr_all = config('ttc.survey.matchgroups.attributes');
        $attr_togo = $attr_all;


        foreach ($matchgroup->rules as $rule) {
            unset($attr_togo[$rule->getShortAttribute()]);
        }

        return view("ttc.backend.surveys.matchgroups.edit", [
            'title' => trans('survey/matchgroups/list.title'),
            'survey' => $survey,
            'matchgroup' => $matchgroup,
            'attr_togo' => $attr_togo,
            'attr_all' => $attr_all
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $survey_id
     * @param $matchgroup_id
     * @param UpdateMatchgroupRequest $request
     * @return Response
     * @internal param int $id
     */
    public function update($survey_id, $matchgroup_id, UpdateMatchgroupRequest $request)
    {
        \Input::merge(['id' => $matchgroup_id]);

        $newRules = [];
        foreach (\Input::get('rules') as $num => $rule) {
            $newRules[$num] = $rule;
            $newRules[$num]['values'] = json_encode($rule['values']);
        }
        \Input::merge(['rules' => $newRules]);


        $matchgroup = $this->dispatch(app(UpdateMatchgroupJob::class, [\Input::all()]));

        \Notification::success(trans('survey/matchgroups/edit.success'));

        if (\Input::get('redirect', null) !== null) {
            return \Redirect::to(\Input::get('redirect'));
        }

        return \Redirect::route('survey.show', $survey_id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $survey_id
     * @param $matchgroup_id
     * @param DeleteMatchgroupRequest $request
     * @return Response
     * @internal param int $id
     */
    public function destroy($survey_id, $matchgroup_id, DeleteMatchgroupRequest $request)
    {
        $matchgroup = $this->dispatch(app(DeleteMatchgroupJob::class));

        \Notification::success(trans('survey/matchgroups/destroy.success'));

        return \Redirect::route('survey.show', [$survey_id]);
    }
}
