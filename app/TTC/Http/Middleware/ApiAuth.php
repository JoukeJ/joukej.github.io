<?php
/**
 * Created by Luuk Holleman
 * Date: 24/06/15
 */

namespace App\TTC\Http\Middleware;

use App\Models\Api\Token;
use App\TTC\Exceptions\Api\NotAuthorizedIpException;
use App\TTC\Exceptions\Api\WrongTokenException;

class ApiAuth
{
    public function handle($request, \Closure $next)
    {
        try {
            $token = \Input::get('token');

            if (Token::whereToken($token)->exists()) {
                $ip = $request->getClientIp();

                if (Token\Ip::whereIp($ip)->exists()) {
                    return $next($request);
                }

                throw new NotAuthorizedIpException("Ip $ip is not authorized");
            }

            throw new WrongTokenException("Token $token is not valid");

        } catch (\Exception $e) {
            return json_encode([
                'status' => 'error',
                'error' => $e->getMessage()
            ]);
        }
    }
}
