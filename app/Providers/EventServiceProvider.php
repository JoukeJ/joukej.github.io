<?php namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\User\ConfirmEmailChangeWasSentEvent' => [
            'App\Listeners\Events\Notification\ConfirmEmailChangeWasSentListener',
        ],
        'App\Events\Asimov\Ui\MainMenuItemsEvent' => [
            'App\Listeners\Events\Asimov\MainMenuItemsListener'
        ]
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }

}
