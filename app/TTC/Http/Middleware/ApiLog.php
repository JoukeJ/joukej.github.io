<?php
/**
 * Created by Luuk Holleman
 * Date: 24/06/15
 */

namespace App\TTC\Http\Middleware;


use App\Models\Api\Log;

class ApiLog
{
    public function handle($request, \Closure $next)
    {
        try {
            $response = $next($request);

            $content = json_decode($response->getContent(), true);

            $log = Log::create([
                'url' => $request->url(),
                'input' => json_encode($request->input())
            ]);

            $content['response_id'] = $log->id;

            $response->setContent(json_encode($content));

            return $response;
        } catch (\Exception $e) {
            $content['error'] = $e->getMessage();

            return json_encode($content);
        }
    }
}
