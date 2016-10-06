<?php /* created by Rob van Bentem, 24/07/2015 */

namespace App\TTC\Http\Controllers\Backend;

use App\Http\Controllers\Asimov\AsimovController;
use App\TTC\Repositories\Backend\SurveyContract;

class BackendController extends AsimovController
{

    /**
     * @var SurveyContract
     */
    private $surveys;

    /**
     * BackendController constructor.
     * @param SurveyContract $surveys
     */
    public function __construct(SurveyContract $surveys)
    {
        $this->surveys = $surveys;
    }

    /**
     * Show recent and open surveys
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {

        $where = [
            ['status', '!=', 'cancelled'],
        ];

        $perPage = 10;
        if (\Auth::user()->may('management.survey.seeall')) {
            $recentCreated = $this->surveys->getSurveysQuery('created_at', 'desc', null, $where)->paginate($perPage);
        } else {
            $recentCreated = $this->surveys->getSurveysQuery('created_at', 'desc', \Auth::user()->id,
                $where)->paginate($perPage);
        }

        if (\Auth::user()->may('management.survey.seeall')) {
            $openSurveys = $this->surveys->getSurveysQuery('created_at', 'desc', null, $where)->where('status', '=',
                'open')->paginate($perPage);
        } else {
            $openSurveys = $this->surveys->getSurveysQuery('created_at', 'desc', \Auth::user()->id,
                $where)->where('status', '=', 'open')->paginate($perPage);
        }


        return \View::make('ttc.backend.home.index', [
            'recent' => $recentCreated,
            'open' => $openSurveys,
        ]);
    }


}
