<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

/**
 * @OA\Post(
 *   path="/auth/register",
 *   summary="Register a new user",
 *   tags={"Authentication"},
 *   @OA\RequestBody(
 *     @OA\MediaType(
 *       mediaType="application/json",
 *         @OA\Schema(
 *           @OA\Property(
 *             property="name",
 *             type="string"
 *           ),
 *           @OA\Property(
 *             property="email",
 *             type="string"
 *           ),
 *           @OA\Property(
 *             property="password",
 *             type="string"
 *           ),
 *           @OA\Property(
 *             property="password_confirmation",
 *             type="string"
 *           ),
 *           example={"name": "User Name", "email": "user@gmail.com", "password": "123456", "password_confirmation": "123456"}
 *         )
 *       )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="OK"
 *   )
 * )
 */


class AuthController extends Controller
{
    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
    $validationRules = [
        'name' => 'required|max:255',
        'email' => 'required|email|unique:users',
        'username' => 'required|max:20',
        'password' => 'required|confirmed',
        'address' => 'required|max:255',
        'phone_number' => 'required|max:15',
    ];

    $validator = Validator::make($request->all(), $validationRules);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 400);
    }

    $user = new User;
    $user->name = $request->input('name');
    $user->email = $request->input('email');
    $user->password = app('hash')->make($request->input('password'));
    $user->username = $request->input('username');
    $user->address = $request->input('address');
    $user->phone_number = $request->input('phone_number');

    $user->save();

    return response()->json($user, 200);
    }

    /**
     * Login and create token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function login(Request $request) {

        $input = $request->all();

        $validationRules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $credentials = $request->only(['email', 'password']);

        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ], 200);
    }

}
