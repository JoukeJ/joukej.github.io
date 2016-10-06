<?php

namespace App\TTC\Http\Controllers\Backend\Survey;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\TTC\Http\Request\Backend\Survey\Entity\CreateEntityRequest;
use App\TTC\Http\Request\Backend\Survey\Entity\DeleteEntityRequest;
use App\TTC\Http\Request\Backend\Survey\Entity\UpdateEntityRequest;
use App\TTC\Jobs\Backend\Entity\CreateEntityJob;
use App\TTC\Jobs\Backend\Entity\DeleteEntityJob;
use App\TTC\Jobs\Backend\Entity\SyncSkipLogicJob;
use App\TTC\Jobs\Backend\Entity\UpdateEntityJob;
use App\TTC\Models\Survey;
use App\TTC\Repositories\Backend\EntityContract;
use App\TTC\Repositories\Backend\SurveyContract;
use Input;
use Response;

class EntityController extends Controller
{
    /**
     * @var SurveyContract
     */
    private $surveys;

    /**
     * @var EntityContract
     */
    private $entities;

    /**
     * @param SurveyContract $surveys
     * @param EntityContract $entities
     */
    function __construct(SurveyContract $surveys, EntityContract $entities)
    {
        $this->surveys = $surveys;
        $this->entities = $entities;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $survey_id
     * @return Response
     * @throws GeneralException
     */
    public function create($survey_id)
    {
        $survey = Survey::findOrFail($survey_id);

        $entityTypes = config('ttc.entity.types');

        // check if valid type is given
        if (in_array(\Input::get('type', null), array_keys($entityTypes))) {

            $class = $entityTypes[\Input::get('type')];

            $entity = new $class;

            return view("ttc.backend.surveys.entities.create", [
                'title' => trans('survey/entities/list.add.' . \Input::get('type')),
                'survey' => $survey,
                'entities' => $survey->entities,
                'entity' => $entity
            ]);
        }

        throw new GeneralException('entity type not given or not valid');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store($survey_id, CreateEntityRequest $request)
    {
        $survey = Survey::findOrFail($survey_id);

        $entity = $this->dispatch(app(CreateEntityJob::class));

        if ($entity !== null) {
            \Notification::success(trans('survey/entities/create.success'));
        }

        return \Redirect::route('survey.show', [
            $entity->survey_id,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $survey_id
     * @param $entity_id
     * @return Response
     * @internal param int $id
     */
    public function edit($survey_id, $entity_id)
    {
        $entity = Survey\Entity::findOrFail($entity_id);

        return view("ttc.backend.surveys.entities.edit", [
            'title' => trans('survey/entities/edit.title.' . $entity->getShortType()),
            'survey' => $entity->survey,
            'entity' => $entity->entity
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $survey_id
     * @param $entity_id
     * @param UpdateEntityRequest $request
     * @return Response
     * @internal param int $id
     */
    public function update($survey_id, $entity_id, UpdateEntityRequest $request)
    {
        $entity = $this->dispatch(app(UpdateEntityJob::class));

        if (Input::has('skip')) {
            $skip = [];

            foreach (Input::get('skip') as $key => $value) {
                $skip[$key] = ($value === 'eos') ? null : $value;
            }

            Input::merge(['skip' => $skip]);
        }

        $this->dispatch(
            app(SyncSkipLogicJob::class, [
                    [
                        'entity_id' => $entity->id,
                        'skip' => Input::get('skip')
                    ]
                ]
            )
        );

        \Notification::success(trans('survey/entities/edit.success'));

        return \Redirect::route('survey.show', [
            $entity->survey_id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $survey_id
     * @param $entity_id
     * @param DeleteEntityRequest $request
     * @return Response
     * @internal param int $id
     */
    public function destroy($survey_id, $entity_id, DeleteEntityRequest $request)
    {
        $entity = $this->dispatch(app(DeleteEntityJob::class));

        \Notification::success('Deleted');

        return \Redirect::route('survey.show', [
            $survey_id
        ]);
    }
}
