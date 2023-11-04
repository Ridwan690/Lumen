<?php
namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::all();
        
        return response()->json(['data' => $books], 200);
    }

    public function show($id)
    {
        $book = Book::find($id);
        
        if (!$book) {
            return response()->json([
                'message' => 'Book not found'
            ], 404);
        }
        
        return response()->json(['data' => $book], 200);
    }

    public function store(Request $request)
    {
        $book = Book::create($request->all());
        
        return response()->json(['data' => $book], 201);
    }

    public function update(Request $request, $id)
    {
        $book = Book::find($id);
        
        if (!$book) {
            return response()->json([
                'message' => 'Book not found'
            ], 404);
        }
        
        $book->fill($request->all());
        $book->save();
        
        return response()->json(['data' => $book], 200);
    }

    public function destroy($id)
    {
        $book = Book::find($id);
        
        if (!$book) {
            return response()->json([
                'message' => 'Book not found'
            ], 404);
        }
        
        $book->delete();
        
        return response()->json(['message' => 'Book deleted'], 200);
    }
    

}