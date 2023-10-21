<?php

namespace App\Http\Middleware;

use Closure;

class UserMiddleware
{
    public function handle($request, Closure $next){
        if (!($request->input('role') == 'user')) {
            return response('Anda tidak memiliki akses.', 401);
        }
        return response('User Dashboard', 401);
    }
}