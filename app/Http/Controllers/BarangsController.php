<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangsController extends Controller {
    public function index()
    {
        $barangs = Barang::all();
        return response()->json(['data' => $barangs], 200);
    }

    public function show($id)
    {
        $barangs = Barang::find($id);

        if (!$barangs) {
            return response()->json([
                'message' => 'Barang not found'
            ], 404);
        }

        return response()->json(['data' => $barangs], 200);
    }

    public function store(Request $request)
    {
        $barangs = Barang::create($request->all());

        return response()->json(['data' => $barangs], 201);
    }

    public function update(Request $request, $id)
    {
        $barangs = Barang::find($id);

        if (!$barangs) {
            return response()->json([
                'message' => 'Barang not found'
            ], 404);
        }

        $barangs->fill($request->all());
        $barangs->save();

        return response()->json(['data' => $barangs], 200);
    }

    public function destroy($id)
    {
        $barangs = Barang::find($id);

        if (!$barangs) {
            return response()->json([
                'message' => 'Barang not found'
            ], 404);
        }

        $barangs->delete();
        $message = ['message' => 'deleted successfully', 'Barang_id' => $id];

        return response()->json($message, 200);
    }

    
}