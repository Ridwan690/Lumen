<?php

namespace App\Http\Middleware;


use Closure;

class LogoutMiddleware
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
        // Tambahkan logika middleware di sini
        if (auth()->check()) {
            return response('Anda sudah logout', 401);
        }

        return $next($request);
    }
}
