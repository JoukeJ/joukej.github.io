<?php

namespace App\TTC\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\TTC\Common\Helper;
use App\TTC\Events\Backend\Profile\UpdatedProfile;
use App\TTC\Http\Request\Frontend\Profile\UpdateProfileRequest;
use App\TTC\Jobs\Frontend\Profile\UpdateProfileJob;
use App\TTC\MatchMaker\MatchMaker;
use App\TTC\Models\Country;
use App\TTC\Models\Profile;
use Illuminate\Support\Facades\App;

class ProfileController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param $identifier
     * @return Response
     * @internal param int $id
     */
    public function show($identifier)
    {
        try {
            $profile = $this->findProfile($identifier);

            $genders = config('ttc.profile.genders');

            $validator = \Validator::make($profile->getAttributes(), [
                'name' => 'required',
                'birthday' => 'required|date',
                'gender' => 'required|in:' . implode(',', $genders),
                'geo_country_id' => 'required'
            ]);

            if ($validator->fails()) {
                return \Redirect::route('profile.edit', [$profile->identifier]);
            }


            if ($profile->hasSurveyInProgress()) {
                $profileSurvey = $profile->getProfileSurveyInProgress();

                $survey = $profileSurvey->survey;

                return \Redirect::route('survey', [
                    $profile->identifier,
                    $survey->identifier,
                    $profileSurvey->getCurrentEntity()->identifier,
                ]);
            }

            return Helper::getFrontendDeviceView('profile.show', [
                'identifier' => $identifier,
                'profile' => $profile,
            ]);
        } catch (\Exception $e) {
            abort(404, "Profile not found.");
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $identifier
     * @return Response
     * @internal param int $id
     */
    public function edit($identifier)
    {
        $profile = $this->findProfile($identifier);

        $countries = [];
        foreach (Country::orderBy('name')->get() as $c) {
            $countries[$c->id] = $c->name;
        }

        return Helper::getFrontendDeviceView('profile.edit', [
            'identifier' => $identifier,
            'profile' => $profile,
            'countries' => $countries,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $identifier
     * @param UpdateProfileRequest $request
     * @return Response
     * @internal param int $id
     */
    public function update($identifier, UpdateProfileRequest $request)
    {
        $profile = $this->findProfile($identifier);

        // combine day, month and year as birthday in input
        $data = array_merge(\Input::except(['day', 'month', 'year']),
            ['birthday' => \Input::get('year') . '-' . \Input::get('month') . '-' . \Input::get('day')]);

        $data['device'] = Helper::getDeviceType();

        $profile = $this->dispatch(app(UpdateProfileJob::class, [$profile->identifier, $data]));

        event(new UpdatedProfile($profile));

        return redirect(
            route(
                'profile.show', [
                    'identifier' => $identifier,
                ]
            )
        );
    }

    /**
     * @param $identifier
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function match($identifier)
    {
        $profileIdentifier = Profile\Identifier::where('identifier', $identifier)->first();

        if ($profileIdentifier != null) {
            event(new SMSResponded($profileIdentifier));
        }

        try {
            $profile = $this->findProfile($identifier);
        } catch (\Exception $e) {
            \App::abort(500, "Could not find this profile.");
        }

        $surveys = App::make(MatchMaker::class, [$profile])->findSurveys();

        if (sizeof($surveys) > 0) {
            $survey = $surveys->first();

            if ($profileIdentifier != null) {
                foreach ($surveys as $s) {
                    if ($s->id == $profileIdentifier->survey_id) {
                        $survey = $s;
                        break;
                    }
                }
            }

            $entity = $survey->getFirstEntity();

            return Helper::getFrontendDeviceView('profile.confirmsurvey', [
                'profile' => $profile,
                'survey' => $survey,
                'entity' => $entity,
            ]);
        }

        return Helper::getFrontendDeviceView('noSurveys', [
            'profile' => $profile,
        ]);
    }

    /**
     * @param $identifier
     * @return \Illuminate\Database\Eloquent\Model|mixed|null|static
     */
    private function findProfile($identifier)
    {
        $profile = null;
        try {
            $profileIdentifier = Profile\Identifier::where('identifier', '=', $identifier)->firstOrFail();

            $profile = $profileIdentifier->profile;
        } catch (\Exception $e) {
            $profile = Profile::where('identifier', '=', $identifier)->firstOrFail();
        }

        return $profile;
    }
}
