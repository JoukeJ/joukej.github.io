<?php namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;

class RouteServiceProvider extends ServiceProvider
{

    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function boot(Router $router)
    {
        parent::boot($router);

        //
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function map(Router $router)
    {
        /**
         * Load routing for TTC by first checking route mode from .env-file
         */
        if (strpos(getenv('ROUTES'), 'backend') !== false) {
            // asimov routes
            $router->group(['namespace' => $this->namespace], function ($router) {
                require app_path('Http/routes.php');
            });

            // ttc backend routes
            $router->group(['namespace' => 'App\TTC\Http\Controllers\Backend'], function () {
                require app_path('/TTC/Routes/backend.php');
            });
        }

        if (strpos(getenv('ROUTES'), 'frontend') !== false) {
            $router->group(['namespace' => '\App\TTC\Http\Controllers\Frontend'], function () {
                require app_path('/TTC/Routes/frontend.php');
            });
        }

        if (strpos(getenv('ROUTES'), 'api') !== false) {
            $router->group(['namespace' => '\App\TTC\Http\Controllers\Api'], function () {
                require app_path('/TTC/Routes/api.php');
            });
        }

        /**
         * Load routes that are used by TTC backend+frontend
         */
        $router->group(['namespace' => 'App\TTC\Http\Controllers'], function () {
            require app_path('/TTC/Routes/both.php');
        });
    }

}
