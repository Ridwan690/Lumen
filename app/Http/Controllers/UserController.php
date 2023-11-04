<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
class UserController extends Controller
{
    // public function index()
    // {
    //     $users = [
            
    //         [
    //             "id" => 1,
    //             "name" => "Sumatrana",
    //             "email" => "sumatrana@gmail.com",
    //             "address" => "Padang",
    //             "gender" => "Laki-laki"
    //         ],
    //         [
    //             "id" => 2,
    //             "name" => "Jawarianto",
    //             "email" => "jawarianto@gmail.com",
    //             "address" => "Cimahi",
    //             "gender" => "Laki-laki"
    //         ],
    //         [
    //             "id" => 3,
    //             "name" => "Kalimantanio",
    //             "email" => "kalimantanio@gmail.com",
    //             "address" => "Samarinda",
    //             "gender" => "Laki-laki"
    //         ],
    //         [
    //             "id" => 4,
    //             "name" => "Sulawesiani",
    //             "email" => "sulawesiani@gmail.com",
    //             "address" => "Makasar",
    //             "gender" => "Perempuan"
    //         ],
    //         [
    //             "id" => 5,
    //             "name" => "Papuani",
    //             "email" => "papuani@gmail.com",
    //             "address" => "Jayapura",
    //             "gender" => "Perempuan"
    //         ]
    //     ];

    //     return response()->json($users);
    // }


    // public function getUserById($userId)
    // {

    //     $user = $this->findUserById($userId);

    //     if ($user) {
    //         return response()->json($user);
    //     } else {
    //         return response()->json(['message' => 'Pengguna tidak ditemukan'], 404);
    //     }
    // }

    // private function findUserById($userId)
    // {
    //     $users = [
    //         [
    //             "id" => 1,
    //             "name" => "Sumatrana",
    //             "email" => "sumatrana@gmail.com",
    //             "address" => "Padang",
    //             "gender" => "Laki-laki"
    //         ],
    //         [
    //             "id" => 2,
    //             "name" => "Jawarianto",
    //             "email" => "jawarianto@gmail.com",
    //             "address" => "Cimahi",
    //             "gender" => "Laki-laki"
    //         ],
    //         [
    //             "id" => 3,
    //             "name" => "Kalimantanio",
    //             "email" => "kalimantanio@gmail.com",
    //             "address" => "Samarinda",
    //             "gender" => "Laki-laki"
    //         ],
    //         [
    //             "id" => 4,
    //             "name" => "Sulawesiani",
    //             "email" => "sulawesiani@gmail.com",
    //             "address" => "Makasar",
    //             "gender" => "Perempuan"
    //         ],
    //         [
    //             "id" => 5,
    //             "name" => "Papuani",
    //             "email" => "papuani@gmail.com",
    //             "address" => "Jayapura",
    //             "gender" => "Perempuan"
    //         ]
    //     ];

    //     foreach ($users as $key => $value) {
    //         if ($value['id'] == $userId) {
    //             return $value;
    //         }
    //     }

    //     return false;
    // }


    public function index() {

        $users = User::all();
        return response()->json(['data' => $users], 200);
    }

    public function store(Request $request) {

        $input = $request->all();
        $users = User::create($input);

        return response()->json(['data' => $users], 200);
    }

    public function show($id) {
        
        $users = User::find($id);
        if (!$users) {
            abort(404);
        }
        return response()->json(['data' => $users], 200);
    }

    public function update(Request $request, $id) {

        $input = $request->all();

        $users = User::find($id);

        if (!$users) {
            abort(404);
        }

        $users->fill($input);
        $users->save();

        return response()->json(['data' => $users], 200);
    }

    public function destroy($id) {

        $users = User::find($id);

        if (!$users) {
            abort(404);
        }

        $users->delete();
        $message = ['message' => 'deleted successfully', 'user_id' => $id];

        return response()->json($message, 200);
    }
}