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
        // $book = Book::find($id);
        
        // if (!$book) {
        //     return response()->json([
        //         'message' => 'Book not found'
        //     ], 404);
        // }
        
        // return response()->json(['data' => $book], 200);

        $acceptHeader = $request->header("Accept");
        if ($acceptHeader == "application/json"){
            $book = Book::find($id);

            if(!$book) {
                abort(404);
            }

        return response()->json($book, 200);
        } elseif ($acceptHeader === "application/xml") {
            $book = Book::find($id);

            if(!$book) {
                abort(404);
            }

            $xml = new \SimpleXMLElement('<book/>');
            $xmlItem = $xml->addChild('book');

                    $xmlItem->addChild('title', $book->title);
                    $xmlItem->addChild('author', $book->author);
                    $xmlItem->addChild('genre', $book->genre);
                    $xmlItem->addChild('user_id', $book->user_id);
                    $xmlItem->addChild('publisher', $book->publisher);
                    $xmlItem->addChild('is_available', $book->is_available);

                    $xmlItem->addChild('created_at', $book->created_at);
                    $xmlItem->addChild('updated_at', $book->updated_at);
            
            return $xml->asXML();
        }else {
            return response('Not Acceptable!', 406);
        } 
    }

    public function store(Request $request)
    {
        // $book = Book::create($request->all());
        
        // return response()->json(['data' => $book], 201);

        $acceptHeader = $request->header('Accept');

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            $contentTypeHeader = $request->header('Content-Type');

            if ($contentTypeHeader === 'application/json') {
                $input = $request->all();
                $validationRules = [
                    'title' => 'required|min:5',
                    'author' => 'required|min:10',
                    'genre' => 'required|min:5',
                    'user_id' => 'required|numeric',
                    'publisher' => 'required|min:5',
                    'is_featured' => 'required|boolean'

                ];
                $validator = Validator::make($input, $validationRules);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 400);
                }

                $book = Book::create($input);
                return response()->json($book, 200);

            } else if ($contentTypeHeader === 'application/xml') {
                $xmldata = $request->getContent();
                $xml = simplexml_load_string($xmldata);

                    if ($xml === false) {
                        return response('Bad Request', 400);
                    } else {
                        $book = Book::create([
                            'title' => $xml->title,
                            'author' => $xml->author,
                            'genre' => $xml->genre,
                            'user_id' => $xml->user_id,
                            'publisher' => $xml->publisher,
                            'is_available' => $xml->is_available
                        ]);
                        
                        if ($book->save()) {
                            return $xml -> asXML();
                        } else {
                            return response('Internal Error', 500);
                        }
                    }
            } 
        } else {
            return response('Not Acceptable!', 406);
        }
    }

    public function update(Request $request, $id)
    {
        // $book = Book::find($id);
        
        // if (!$book) {
        //     return response()->json([
        //         'message' => 'Book not found'
        //     ], 404);
        // }
        
        // $book->fill($request->all());
        // $book->save();

        $acceptHeader = $request->header('Accept');
        if ($acceptHeader == 'application/json' || $acceptHeader==="application/xml") {
            $contentTypeHeader = $request->header("Content-Type");
            if ($contentTypeHeader == "application/json") {
                $input = $request->all();
                $book = Book::find($id);

                if(!$book) {
                    return response('book not found', 404);
                }

                $validationRules = [
                    'title' => 'required|min:5',
                    'author' => 'required|min:10',
                    'genre' => 'required|min:5',
                    'user_id' => 'required|numeric',
                    'publisher' => 'required|min:5',
                    'is_featured' => 'required|boolean'

                ];
                $validator = Validator::make($input, $validationRules);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 400);
                }

                $book->fill($input);
                $book->save();

                return response()->json($book, 200);
            } elseif ($contentTypeHeader == "application/xml") {
                $xmldata = $request->getContent();
                $xml = simplexml_load_string($xmldata);

                if ($xml === false) {
                    return response('Bad Request', 400);
                } else {
                    $book = Book::find($id);

                    if(!$book) {
                        return response('book not found', 400);
                    }else {
                        $input = [
                            'title' => $xml->title,
                            'author' => $xml->author,
                            'genre' => $xml->genre,
                            'user_id' => $xml->user_id,
                            'publisher' => $xml->publisher
                        ];

                        $book->fill($input);
                        if ($book->save()){
                            return $xml -> asXML();
                        } else {
                            return response('Internal Error', 500);
                        }
                    }
                }
            } else {
                return response('Not Acceptable!', 406);
            }
        }
        
    }

    public function destroy(Request $request, $id)
    {
        // $book = Book::find($id);
        
        // if (!$book) {
        //     return response()->json([
        //         'message' => 'Book not found'
        //     ], 404);
        // }
        
        // $book->delete();
        
        // return response()->json(['message' => 'Book deleted'], 200);

        $acceptHeader = $request->header("Accept");
        if ($acceptHeader == "application/json") {
            $book = Book::find($id);

            if(!$book) {
                return response('book not found', 404);
            }

            $book->delete();
            $message = ['message' => 'deleted successfully', 'post_id' => $id];

            return response()->json($message, 200);
        } elseif ($acceptHeader === "application/xml") {
            $book = Book::find($id);

            if(!$book) {
                return response('book not found', 404);
            }

            if($book->delete()){
                $xml = new \SimpleXMLElement('<message/>');
                $xml->addChild('message', 'deleted successfully');
                $xml->addChild('id', $id);

                return $xml->asXML();
            }else{
                return response('Internal Server Error', 500);
            }
        }else{
            return response('Not Acceptable!', 406);
        }
    }
    

}