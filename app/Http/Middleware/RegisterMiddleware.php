<?php

namespace App\Http\Middleware;

use Closure;

class RegisterMiddleware
{
    public function handle($request, Closure $next)
    {
        // Validasi data pendaftaran (misalnya, validasi email atau password)
        $email = $request->input('email');
        $password = $request->input('password');

        if (empty($email) || empty($password)) {
            return response('Data pendaftaran tidak lengkap', 400);
        }

        return $next($request);
    }
}
