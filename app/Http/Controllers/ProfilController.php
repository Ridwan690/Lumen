<?php

namespace App\Http\Controllers;

class ProfilController extends Controller
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

    public function index(){
        $profil = [
            'nama' => 'Ridwan Nurhakim',
            'nim' => 'D112111039',
            'hobi' => 'Membaca Komik',
            'prodi' => 'Teknik Informatika',
        ];
        return response()->json($profil);
    }

    //
}
