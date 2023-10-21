<?php

namespace App\Http\Middleware;

use Closure;

class AdminMiddleware
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
        if (!($request->input('role') == 'admin')) {
            return response('Anda tidak memiliki akses.', 401);
        }
        return response('Admin Dashboard', 401);
    }
}
