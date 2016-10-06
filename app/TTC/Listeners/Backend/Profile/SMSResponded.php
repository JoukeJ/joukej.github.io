<?php

namespace App\TTC\Listeners\Backend\Profile;


/**
 * Class SMSResponded
 * @package App\TTC\Listeners\Backend\Profile
 */
class SMSResponded
{
    /**
     * @param $event
     */
    public function handle($event)
    {
        $event->profileIdentifier->responded = 1;

        $event->profileIdentifier->save();
    }
}
