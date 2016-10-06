<?php

namespace App\TTC\Jobs\Backend\Survey\MatchMaker;


use App\Jobs\Job;
use App\TTC\MatchMaker\MashConnector\ProfilesMatchConnector;

class SyncProfilesJob extends Job
{
    private $survey;

    /**
     * SyncProfilesJob constructor.
     * @param $survey
     */
    public function __construct($survey)
    {
        $this->survey = $survey;
    }

    public function handle()
    {
        $profilesMatchConnector = new ProfilesMatchConnector($this->survey);

        $profiles = $profilesMatchConnector->get();

        foreach ($profiles as $profile) {

        }
    }
}
