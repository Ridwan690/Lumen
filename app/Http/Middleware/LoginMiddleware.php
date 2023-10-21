<?php
namespace App\Http\Middleware;

use Closure;

class LoginMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!($request->input('username') == 'user' && $request->input('password') == '123')) {
            return response('Unauthorized.', 401);
        }
        return response('Dashboard', 401);
    }
}