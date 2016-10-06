<?php

namespace App\TTC\Connector\Stage;


use App\TTC\MatchMaker\MashConnector\Events\SurveyCompleted;
use App\TTC\MatchMaker\MashConnector\Events\SurveyStarted;
use App\TTC\MatchMaker\MashConnector\Events\UpdatedProfile;
use App\TTC\MatchMaker\MashConnector\ProfileConnector;
use App\TTC\Models\Profile\Event;
use Unifact\Connector\Handler\Stage;

class SendEventStage extends Stage
{
    public function process(array $data)
    {
        $profileEvent = Event::find(json_decode($data, true)['profile_event_id']);

        $profileConnector = new ProfileConnector($profileEvent->profile);

        $event = null;

        switch($profileEvent){
            case 'survey-started':
                $event = new SurveyStarted();
                break;
            case 'survey-completed':
                $event = new SurveyCompleted();
                break;
            case 'updated-profile':
                $event = new UpdatedProfile();
                break;
        }

        if($event != null){
            return $profileConnector->sendEvent($event);
        }
        
        return $data;
    }
}
