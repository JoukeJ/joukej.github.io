<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{

    public $excludedRoutes = [
        'management/mandrill/*',
        'api/*'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        foreach ($this->excludedRoutes as $excludedRoute) {
            if ($request->is($excludedRoute)) {
                return $next($request);
            }
        }

        return parent::handle($request, $next);
    }

}
