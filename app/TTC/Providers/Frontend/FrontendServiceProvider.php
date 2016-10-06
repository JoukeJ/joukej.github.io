<?php

namespace App\TTC\Providers\Frontend;

use App\TTC\Models\Survey\Entity\Option;
use App\TTC\Models\Survey\Entity\Question\Checkbox;
use App\TTC\Repositories\Frontend\AnswerContract;
use App\TTC\Repositories\Frontend\AnswerRepository;
use App\TTC\Repositories\Frontend\EntityContract;
use App\TTC\Repositories\Frontend\EntityRepository;
use App\TTC\Repositories\Frontend\ProfileContract;
use App\TTC\Repositories\Frontend\ProfileRepository;
use App\TTC\Repositories\Frontend\ProfileSurveyContract;
use App\TTC\Repositories\Frontend\ProfileSurveyRepository;
use App\TTC\Repositories\Frontend\SurveyContract;
use App\TTC\Repositories\Frontend\SurveyRepository;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;

class FrontendServiceProvider extends EventServiceProvider
{

    protected $listen = [
        'App\TTC\Events\Frontend\Entity\EntityRedirectedEvent' => [
            'App\TTC\Listeners\Frontend\Entity\EntityRedirectedListener',
        ],
        'App\TTC\Events\Frontend\Survey\SurveyWasCompletedEvent' => [
            'App\TTC\Listeners\Frontend\Survey\SurveyWasCompletedListener',
        ],
    ];

    /**
     * Bootstrap the application services.
     *
     * @param DispatcherContract $events
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        \Validator::extend('checkbox_options_belong_to_entity', function ($attribute, $value, $parameters) {

            $entity_id = $parameters[0];

            if (is_array($value)) {
                foreach ($value as $id) {
                    if (Option::where('id', '=', $id)->where('entity_id', '=', $entity_id)->where('entity_type', '=',
                            Checkbox::class)->exists() === false
                    ) {
                        return false;
                    }
                }
            }

            return true;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SurveyContract::class, SurveyRepository::class);
        $this->app->bind(EntityContract::class, EntityRepository::class);
        $this->app->bind(ProfileContract::class, ProfileRepository::class);
        $this->app->bind(AnswerContract::class, AnswerRepository::class);
        $this->app->bind(ProfileSurveyContract::class, ProfileSurveyRepository::class);
    }
}
