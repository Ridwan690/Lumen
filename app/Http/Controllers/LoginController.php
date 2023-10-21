<?php

namespace App\Http\Controllers;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        // Verifikasi username dan password
        if ($username === 'user' && $password === '123') {
            // Autentikasi berhasil, Anda dapat menghasilkan token atau menetapkan session
            return response()->json(['message' => 'Login successful']);
        } else {
            // Autentikasi gagal, kirim respons error
            return response()->json(['message' => 'Login failed. Username or password is incorrect.'], 401);
        }
    }

    //
}
