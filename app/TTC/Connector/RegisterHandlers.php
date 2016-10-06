<?php

namespace App\TTC\Connector;


use App\TTC\Connector\Handler\RegisterProfileEvent;

class RegisterHandlers
{
    public function handle($event)
    {
        $event->manager->pushHandler(RegisterProfileEvent::make());
    }
}
