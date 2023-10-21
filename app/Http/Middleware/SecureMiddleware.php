<?php

namespace App\Http\Middleware;

use Closure;

class SecureMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('Authorization');

        if ($token !== '123456789') {
            return response('Heloo', 401);
        }
        return $next($request);
    }
}
