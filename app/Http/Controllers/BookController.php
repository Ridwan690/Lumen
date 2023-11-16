<?php
namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $acceptHeader = $request->header("Accept");
        $books = Book::Where(['id' => Auth::user()->id])->OrderBy("id","ASC")->paginate(2)->toArray();;
        
        $response = [
                "total_count" => $books["total"],
                "limit" => $books["per_page"],
                "pagination" => [
                    "next_page" => $books["next_page_url"],
                    "current_page" => $books["current_page"]
                ],
                "data" => $books["data"],
            ];
                //JSON
            if ($acceptHeader === 'application/json') {
                return response()->json($response, 200);
            }  else {
            return response('Not Acceptable', 406);
        } 
    }

    public function show(Request $request, $id)
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

       
        
    }

    public function destroy(Request $request, $id)
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