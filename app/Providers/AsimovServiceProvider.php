<?php namespace App\Providers;

use App\Events\Asimov\Ui\MainMenuItemsEvent;
use Illuminate\Support\ServiceProvider;

class AsimovServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Repositories\Asimov\Management\Users\UserContract',
            'App\Repositories\Asimov\Management\Users\UserRepository'
        );

        $this->app->bind(
            'App\Repositories\Asimov\Management\Roles\RoleContract',
            'App\Repositories\Asimov\Management\Roles\RoleRepository'
        );

        $this->app->bind(
            'App\Repositories\Asimov\Management\Permissions\PermissionContract',
            'App\Repositories\Asimov\Management\Permissions\PermissionRepository'
        );

        $this->app->bind(
            'App\Repositories\Asimov\User\UserContract',
            'App\Repositories\Asimov\User\UserRepository'
        );

        \View::composer('asimov.layout.master', function ($view) {
            $col = new \Illuminate\Support\Collection();

            \Event::fire(new MainMenuItemsEvent($col));

            $output = implode(PHP_EOL, $col->toArray());

            $view->with('main_menu_items', $output);
        });

        \Blade::extend(function ($view) {
            $html = '<?php echo \View::make("asimov.partials.errors")->render(); ?>';

            return preg_replace('#\s*@errors#', $html, $view);
        });

        \Blade::extend(function ($view) {
            $html = '<?php echo \View::make("asimov.partials.delete", $1)->render(); ?>';

            return preg_replace("#\s*@delete\(\s*(.*)\s*\)#", $html, $view);
        });
    }
}
