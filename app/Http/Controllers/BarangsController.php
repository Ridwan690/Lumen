<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangsController extends Controller {
    public function index()
    {
        $Barang = barang::all();
        return response()->json($Barang);
    }
}