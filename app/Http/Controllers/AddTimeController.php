<?php

namespace App\Http\Controllers;

class ExampleController extends Controller
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

    // app/Http/Controllers/YourController.php

    public function store(Request $request)
{
    // Data telah ditambahkan timestamp oleh middleware
    $data = $request->all();

    // Simpan data ke basis data
    YourModel::create($data);

    return response()->json(['message' => 'Data saved successfully']);
}


    //
}
