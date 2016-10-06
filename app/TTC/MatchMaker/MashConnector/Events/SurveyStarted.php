<?php

namespace App\TTC\MatchMaker\MashConnector\Events;


class SurveyStarted implements MashEventInterface
{
    public function getEvent()
    {
        return 'involved';
    }

    public function getPName()
    {
        return 'Survey Started';
    }
}
