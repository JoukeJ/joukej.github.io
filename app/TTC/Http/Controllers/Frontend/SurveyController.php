<?php

namespace App\TTC\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\TTC\Common\Helper;
use App\TTC\Events\Backend\Profile\SurveyCompleted;
use App\TTC\Events\Backend\Profile\SurveyStarted;
use App\TTC\Jobs\Frontend\Entity\InteractWithEntityJob;
use App\TTC\Jobs\Frontend\Entity\RequestEntityJob;
use App\TTC\Models\Profile;
use App\TTC\Models\Survey;
use Illuminate\Support\Facades\URL;
use Input;

class SurveyController extends Controller
{
    /**
     * @param $profileId
     * @param $surveyId
     * @param $entityId
     * @return mixed
     */
    public function post($profileId, $surveyId, $entityId)
    {
        $survey = Survey::where('identifier', '=', $surveyId)->first();

        if (is_null($survey) || $survey->status != 'open') {
            return redirect(\URL::route('profile.show', $profileId));
        }

        return $this->dispatch(app(InteractWithEntityJob::class, [Input::get('answer')]));
    }

    /**
     * @param $profileId
     * @param $surveyId
     * @param $entityId
     * @return mixed
     */
    public function get($profileId, $surveyId, $entityId)
    {
        $survey = Survey::where('identifier', '=', $surveyId)->first();

        if (is_null($survey) || $survey->status != 'open') {
            return redirect(\URL::route('profile.show', $profileId));
        }

        $profile = Profile::where('identifier', $profileId)->firstOrFail();

        event(new SurveyStarted($survey, $profile));

        $response = $this->dispatch(app(RequestEntityJob::class));

        return $response;
    }

    /**
     * @param $profileId
     * @param $surveyId
     * @return \Illuminate\View\View
     */
    public function complete($profileId, $surveyId)
    {
        $profile = Profile::where('identifier', '=', $profileId)->firstOrFail();

        $survey = Survey::where('identifier', $surveyId)->first();

        event(new SurveyCompleted($survey, $profile));

        return Helper::getFrontendDeviceView('survey.complete', [
            'profile' => $profile
        ]);
    }
}
