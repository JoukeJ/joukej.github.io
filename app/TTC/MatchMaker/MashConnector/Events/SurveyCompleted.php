<?php

namespace App\TTC\MatchMaker\MashConnector\Events;


class SurveyCompleted implements MashEventInterface
{
    public function getEvent()
    {
        return 'done';
    }

    public function getPName()
    {
        return 'Survey Completed';
    }
}
