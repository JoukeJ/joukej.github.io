<?php

namespace App\TTC\MatchMaker\MashConnector\Events;


class UpdatedProfile implements MashEventInterface
{

    public function getEvent()
    {
        return 'opt-in';
    }

    public function getPName()
    {
        return 'Updated profile';
    }
}
