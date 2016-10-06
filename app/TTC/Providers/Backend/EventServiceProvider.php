<?php namespace App\TTC\Providers\Backend;

use App\Events\Asimov\Ui\MainMenuItemsEvent;
use App\TTC\Connector\RegisterHandlers;
use App\TTC\Events\Backend\Profile\SMSResponded;
use App\TTC\Events\Backend\Profile\SurveyCompleted;
use App\TTC\Events\Backend\Profile\SurveyStarted;
use App\TTC\Events\Backend\Profile\UpdatedProfile;
use App\TTC\Events\Backend\Survey\SurveyWasClosedEvent;
use App\TTC\Events\Backend\Survey\SurveyWasUpdatedEvent;
use App\TTC\Listeners\Backend\MainMenuItemsListener;
use App\TTC\Listeners\Backend\Profile\ProfileEventLog;
use App\TTC\Listeners\Backend\Survey\AbandonUnfinishedSurvey;
use App\TTC\Listeners\Backend\Survey\ReopenRepeatingSurvey;
use App\TTC\Listeners\Backend\Survey\UpdateRepeatingSurvey;
use App\TTC\Listeners\Backend\SurveyWasClosedListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Unifact\Connector\Events\ConnectorRegisterHandlerEvent;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        MainMenuItemsEvent::class => [
            MainMenuItemsListener::class
        ],
        SurveyWasClosedEvent::class => [
            AbandonUnfinishedSurvey::class,
            ReopenRepeatingSurvey::class
        ],
        SurveyWasUpdatedEvent::class => [
            UpdateRepeatingSurvey::class
        ],
        SurveyCompleted::class => [
            ProfileEventLog::class
        ],
        SurveyStarted::class => [
            ProfileEventLog::class
        ],
        UpdatedProfile::class => [
            ProfileEventLog::class
        ],
        SMSResponded::class => [
            \App\TTC\Listeners\Backend\Profile\SMSResponded::class
        ],
    ];
}
