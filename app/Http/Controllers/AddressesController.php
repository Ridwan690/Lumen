<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressesController extends Controller {
    public function index()
    {
        $Address = Address::all();
        return response()->json(['Address' => $Address], 200);
    }
}