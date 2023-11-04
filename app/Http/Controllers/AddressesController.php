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

    public function show($id)
    {
        $Address = Address::find($id);
        if (!$Address) {
            return response()->json([
                'message' => 'Address not found'
            ], 404);
        }
        return response()->json(['Address' => $Address], 200);
    }

    public function store(Request $request)
    {
        $Address = Address::create($request->all());
        return response()->json(['Address' => $Address], 201);
    }

    public function update(Request $request, $id)
    {
        $Address = Address::find($id);
        if (!$Address) {
            return response()->json([
                'message' => 'Address not found'
            ], 404);
        }
        $Address->fill($request->all());
        $Address->save();
        return response()->json(['Address' => $Address], 200);
    }

    public function destroy($id)
    {
        $Address = Address::find($id);
        if (!$Address) {
            return response()->json([
                'message' => 'Address not found'
            ], 404);
        }
        $Address->delete();
        return response()->json([
            'message' => 'Address deleted'
        ], 200);
    }
}