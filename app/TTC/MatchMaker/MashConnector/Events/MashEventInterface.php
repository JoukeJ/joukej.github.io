<?php

namespace App\TTC\MatchMaker\MashConnector\Events;


interface MashEventInterface
{
    public function getPName();

    public function getEvent();
}
