<?php

namespace App\TTC\Http\Controllers\Backend;


use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\TTC\Http\Request\Backend\Survey\CreateSurveyRequest;
use App\TTC\Http\Request\Backend\Survey\DeleteSurveyRequest;
use App\TTC\Http\Request\Backend\Survey\UpdateSurveyRequest;
use App\TTC\Jobs\Backend\Survey\CancelSurveyJob;
use App\TTC\Jobs\Backend\Survey\CreateSurveyJob;
use App\TTC\Jobs\Backend\Survey\DeleteSurveyJob;
use App\TTC\Jobs\Backend\Survey\OpenSurveyJob;
use App\TTC\Jobs\Backend\Survey\PermanentlyCloseSurveyJob;
use App\TTC\Jobs\Backend\Survey\UpdateSurveyJob;
use App\TTC\MatchMaker\MatchMaker;
use App\TTC\Models\Profile;
use App\TTC\Models\Profile\Identifier;
use App\TTC\Models\Survey;
use App\TTC\Repositories\Backend\SurveyContract;
use App\TTC\Statistic\Statistic;
use App\TTC\Tags\Entity\CanBePresented;
use Carbon\Carbon;
use Ddeboer\DataImport\Writer\CsvWriter;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

/**
 * Class SurveyController
 * @package App\TTC\Http\Controllers\Backend
 */
class SurveyController extends Controller
{

    /**
     * @var SurveyContract
     */
    private $surveys;

    /**
     * @param SurveyContract $surveys
     */
    function __construct(SurveyContract $surveys)
    {
        $this->surveys = $surveys;
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // @todo afronden

        return view("ttc.backend.surveys.index", [
            'title' => trans('survey/list.index'),
            'bootgridUrl' => URL::route('surveys.bootgrid'),
        ]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function myIndex()
    {
        // @todo afronden

        return view("ttc.backend.surveys.index", [
            'title' => trans('survey/list.my_index'),
            'bootgridUrl' => URL::route('surveys.my.bootgrid'),
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        try {
            $survey = $this->surveys->findOrThrowException($id, true);

            return \View::make('ttc.backend.surveys.show', [
                'survey' => $survey,
                'entities' => $survey->getPresentableEntities(),
                'new_entities' => $this->getPresentableNewEntityOptions(),
                'statistic' => $survey->status === 'open' ? new Statistic($survey) : null,
            ]);

        } catch (\Exception $e) {
            \App::abort(404, 'Survey not found.');
        }
    }


    /**
     * @return array
     */
    protected function getPresentableNewEntityOptions()
    {
        $entities = [];

        foreach (config('ttc.entity.types') as $type => $class) {
            $instance = new $class;

            if ($instance instanceof CanBePresented) {
                $entities[$type] = $instance;
            }
        }

        return $entities;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        return view('ttc.backend.surveys.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(CreateSurveyRequest $request)
    {
        \Input::merge([
            'user_id' => \Auth::user()->id,
        ]);

        $survey = $this->dispatch(app(CreateSurveyJob::class));

        \Notification::success(trans('survey/create.success'));

        return \Redirect::route('surveys.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $survey = Survey::findOrFail($id);
        $repeat = $survey->repeat;

        if ($repeat !== null) {
            $interval = $repeat->interval;
            $absolute_end_date = $repeat->absolute_end_date;
        }

        return view('ttc.backend.surveys.edit', [
            'survey' => $survey,
            'repeat' => [
                'interval' => isset($interval) ? $interval : "",
                'absolute_end_date' => isset($absolute_end_date) ? $absolute_end_date : "",
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id, UpdateSurveyRequest $request)
    {
        \Input::merge(['id' => $id]);

        $survey = $this->dispatch(app(UpdateSurveyJob::class));

        \Notification::success(trans('survey/edit.success'));

        if (\Input::get('redirect', null) !== null) {
            return \Redirect::to(\Input::get('redirect'));
        }

        return \Redirect::route('surveys.index');
    }

    /**
     * @param null $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function bootgrid($user_id = null)
    {
        $sorting = \Input::get('sort', ['name' => 'asc']);
        $sort = key($sorting);
        $sort_direction = $sorting[$sort];

        \Input::merge(['page' => \Input::get('current')]);

        $rowCount = \Input::get('rowCount');

        $where = [['status', '!=', 'cancelled']];
        if ($user_id !== null) {
            $surveys = $this->surveys->getSurveysPaginatedByUser($user_id, $rowCount, $sort, $sort_direction, $where);
        } else {
            $surveys = $this->surveys->getSurveysPaginated($rowCount, $sort, $sort_direction, $where);
        }

        $data = [];

        $data['current'] = \Input::get('current');
        $data['rowCount'] = \Input::get('rowCount');

        if ($surveys->total() === 0) {
            $data['rows'] = [];
        } else {
            foreach ($surveys->items() as $survey) {
                $a = [
                    'url' => URL::route('surveys.destroy', [$survey->id]),
                    'text' => trans('common.cannot_be_undone'),
                    'title' => trans('survey/list.confirm_delete_text'),
                    'name' => '<i class="fa fa-trash" />',
                ];
                $data['rows'][] = [
                    'name' => "<a href='" . URL::route('survey.show',
                            $survey->id) . "'>" . $survey->name . "</a>",
                    'owner' => $survey->user()->withTrashed()->first()->getName(),
                    'start_date' => Carbon::parse($survey->start_date)->format('d-m-Y'),
                    'end_date' => Carbon::parse($survey->end_date)->format('d-m-Y'),
                    'status' => ucfirst($survey->status),
                    'language' => \App\TTC\Common\Helper::getLanguages()[$survey->language],
                ];
            }
        }

        $data['total'] = $surveys->total();

        return response()->json($data);
    }

    /**
     * @param $survey
     * @return \Illuminate\View\View|string
     */
    protected function getSurveyEditLink($survey)
    {
        if ($survey->status === 'draft') {
            return "<a href='" . URL::route('surveys.edit',
                $survey->id) . "' title='Edit'><i class='fa fa-pencil' /></a>";
        } elseif ($survey->status === 'open') {
            return \View::make("asimov.partials.delete", [
                'url' => URL::route('surveys.close', [$survey->id]),
                'text' => trans('common.cannot_be_undone'),
                'title' => trans('survey/list.confirm_close_text'),
                'name' => "<i class='fa fa-ban' />",
                'confirm' => trans('common.close'),
                'method' => '',
            ])->render();
        }

        return "";
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function myBootgrid()
    {
        return $this->bootgrid(\Auth::user()->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id, DeleteSurveyRequest $request)
    {
        $survey = $this->dispatch(app(DeleteSurveyJob::class));

        if ($survey) {
            \Notification::success(trans('survey/destroy.success'));
        } else {
            if (is_object($this->surveys->findOrThrowException($id))) {
                \Notification::warning(trans('survey/destroy.norights'));
            } else {
                \Notification::danger(trans('survey/destroy.error'));
            }
        }

        return \Redirect::to(URL::previous());
    }

    /**
     * @param $surveyId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function close($surveyId)
    {
        $this->dispatch(app(PermanentlyCloseSurveyJob::class, [$surveyId]));

        \Notification::success(trans('survey/close.success'));

        return \Redirect::to(\URL::route('survey.show', $surveyId));
    }

    /**
     * @param $surveyId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function open($surveyId)
    {
        $this->dispatch(app(OpenSurveyJob::class, [$surveyId]));

        \Notification::success(trans('survey/open.success'));

        return \Redirect::to(\URL::route('survey.show', $surveyId));
    }

    /**
     * @param $surveyId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel($surveyId)
    {
        $this->dispatch(app(CancelSurveyJob::class, [$surveyId]));

        \Notification::success(trans('survey/cancel.success'));

        return \Redirect::to(\URL::route('survey.show', $surveyId));
    }

    public function exportSMSData($id)
    {
        $survey = Survey::find($id);

        /**
         * Be warned, hacks here
         *
         * Omdat het idee was om de profiles uit mash te halen en niet door is gegaan,
         * is dit een quick en dirty manier om ze toch nog te vullen
         *
         * Hij loopt alle profiles en matcht daar surveys bij. Kijkt of onze survey er bij zit,
         * zo ja, vind of maak een nieuwe identifier
         */
        ini_set('memory_limit', -1);
        $profiles = new Collection();

        $csvSurvey = Collection::make([Survey::find($id)]);

        $profileIds = [];

        foreach (Profile::all() as $profile) {
            $matchmaker = app(MatchMaker::class, [$profile]);

            $matchedSurveys = $matchmaker->matchOnSurveys($csvSurvey);

            foreach ($matchedSurveys as $survey) {
                if ($survey->id == $id) {
                    $profileIdentifier = Identifier::firstOrNew([
                        'profile_id' => $profile->id,
                        'survey_id' => $survey->id,
                    ]);

                    if ($profileIdentifier->identifier == null) {
                        $profileIdentifier->identifier = str_random(8);

                        $profileIdentifier->save();
                    }

                    $profiles->push($profile);
                    $profileIds[] = $profile->id;

                    break;
                }
            }
        }
        /**
         * End hacks
         */

        $profileIdentifiers = Identifier::where('survey_id', $id)
            ->whereIn('profile_id', $profileIds)
            ->get();

        $writer = new CsvWriter();
        $writer->setStream(fopen('php://temp', 'w'));
        $writer->setCloseStreamOnFinish(false);

        $writer->writeItem(['Phone', 'Url', 'Name']);

        foreach ($profileIdentifiers as $profileIdentifier) {
            $writer->writeItem([
                $profileIdentifier->profile->phonenumber,
                sprintf(env('SHORT_URL'), $profileIdentifier->identifier),
                $profileIdentifier->profile->name,
            ]);
        }

        $stream = $writer->getStream();
        rewind($stream);

        $callback = function () use ($stream) {
            echo stream_get_contents($stream);
        };

        $filename = 'participant_' . Str::slug($survey->name) . '_' . date('Y-m-d') . '.csv';
        return \Response::stream($callback, 200, [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=' . $filename,
            'Expires' => '0',
            'Pragma' => 'public',
        ]);
    }
}
