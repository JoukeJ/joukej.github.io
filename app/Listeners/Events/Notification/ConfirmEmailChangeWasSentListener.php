<?php

namespace App\Listeners\Events\Notification;

use App\Events\User\ConfirmEmailChangeWasSentEvent;

class ConfirmEmailChangeWasSentListener
{
    /**
     * Handle the event.
     *
     * @param  ConfirmEmailChangeWasSentEvent $event
     * @return void
     */
    public function handle(ConfirmEmailChangeWasSentEvent $event)
    {
        \Notification::success(trans('notifications.emailchange_sent'));
    }
}
