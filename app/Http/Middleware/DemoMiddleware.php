<?php

namespace App\Http\Middleware;

use Closure;

class DemoMiddleware
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
        // Periksa apakah mode demo diaktifkan
        if ($this->isDemoMode()) {
            // Jika mode demo aktif, tampilkan pesan demo
            return response('Aplikasi sedang dalam mode demo. Beberapa fitur dinonaktifkan.', 200);
        }

        return $next($request);
    }
}
