<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    // public function index()
    // {
    //     // Data produk acak
    //     $products = [
    //         [
    //             'id' => 1, 
    //             'name' => 'Laptop', 
    //             'price' => 99.99
    //         ],
    //         ['id' => 2, 
    //         'name' => 'Mouse', 
    //         'price' => 29.99
    //         ],
    //         [
    //         'id' => 3,
    //         'name' => 'Keyboard',
    //         'price' => 39.99
    //         ],
    //         ['id' => 4, 
    //         'name' => 'Monitor', 
    //         'price' => 49.99
    //         ],
    //     ];

    //     return response()->json(['products' => $products]);
    // }


    //

    public function index() {
        $products = Product::all();
        return response()->json(['data' => $products], 200);
    }
}
