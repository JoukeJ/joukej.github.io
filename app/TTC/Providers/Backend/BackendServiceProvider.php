<?php

namespace App\TTC\Providers\Backend;

use App\TTC\Repositories\Backend\EntityContract;
use App\TTC\Repositories\Backend\EntityRepository;
use App\TTC\Repositories\Backend\SurveyContract;
use App\TTC\Repositories\Backend\SurveyRepository;
use Illuminate\Support\ServiceProvider;

class BackendServiceProvider extends ServiceProvider
{
    public function boot()
    {
        \Validator::extend('array_values_filled', function ($attribute, $value, $parameters) {

            foreach ($value as $v) {
                if (trim($v) == '') {
                    return false;
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
    }
}
