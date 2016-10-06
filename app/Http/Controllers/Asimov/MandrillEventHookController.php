<?php namespace App\Http\Controllers\Asimov;

use App\Models\Email\Hook;

class MandrillEventHookController extends AsimovController
{
    public function postHook()
    {
        $events = \Input::get('mandrill_events');

        foreach (json_decode($events) as $event) {
            $hook = Hook::firstOrCreate(['email' => $event['_id']]);

            switch ($event['event']) {
                case 'send':
                    $hook->sent++;
                    break;
            }

            $hook->save();
        }
    }
}
