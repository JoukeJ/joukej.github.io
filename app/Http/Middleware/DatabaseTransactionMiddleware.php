<?php namespace App\Http\Middleware;

use Closure;

class DatabaseTransactionMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /**
         * The reason why there is no try catch here ais that this method will already be broken when there is an exception thrown, so DB::commit is never executed
         */
        \DB::beginTransaction();

        $response = $next($request);

        \DB::commit();

        return $response;
    }

}
