<?php

namespace App\TTC\Listeners\Backend\Profile;


use App\TTC\Models\Profile\Event;
use Illuminate\Database\QueryException;

/**
 * Class ProfileEventLog
 * @package App\TTC\Listeners\Backend\Profile
 */
class ProfileEventLog
{
    /**
     * @param $event
     */
    public function handle($event)
    {
        try{
            $eventLog = new Event();

            $eventLog->profile_id = $event->profile->id;
            $eventLog->name = $event->name;

            if(property_exists($event, 'survey')){
                $eventLog->survey_id = $event->survey->id;
            }

            $eventLog->save();
        } catch (QueryException $e){
            // ignore
        }
    }
}
