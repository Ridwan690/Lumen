<?php 

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::OrderBy("id", "DESC")->paginate(10);

        $outPut = [
            "message" => "posts",
            "results" => $posts
        ];

        return response()->json($posts, 200);

        // $acceptHeader = $request->header('Accept');

        // if ($acceptHeader === 'application/json'|| $acceptHeader === 'application/xml') {
        //     $posts = Post::OrderBy("id", "DESC")->paginate(10);

        //     if ($acceptHeader === 'application/json') {
        //         return response()->json($posts->items('data'), 200);
        //     } else {
        //         $xml = new \SimpleXMLElement('<posts/>');
        //         foreach ($posts->items('data') as $item) {

        //             $xmlItem = $xml->addChild('post');

        //             $xmlItem->addChild('id', $item->id);
        //             $xmlItem->addChild('title', $item->title);
        //             $xmlItem->addChild('status', $item->status);
        //             $xmlItem->addChild('content', $item->content);
        //             $xmlItem->addChild('user_id', $item->user_id);
        //             $xmlItem->addChild('created_at', $item->created_at);
        //             $xmlItem->addChild('updated_at', $item->updated_at);
        //         }
        //         return $xml->asXML();
        //     }

        // } else {
        //     return response('Not Acceptable', 406);
    // } 
    }

    public function store(Request $request) 
    {
        $input = $request->all();
        $post = Post::create($input);

        return response()->json(['data' => $post], 200);

        // $acceptHeader = $request->header('Accept');

        // if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            
        //     $contentTypeHeader = $request->header('Content-Type');

        //     if ($contentTypeHeader === 'application/json') {
        //         $input = $request->all();
        //         $post = Post::create($input);

        //         return response()->json($post, 200);
        //     } else {
        //         return response('Unsupported Media Type', 415);
        //     }
        // }else {
        //     return response('Not Acceptable!', 406);
        // }
    }

    

    public function show($id)
    {
        $post = Post::find($id);

        if(!$post) {
            abort(404);
        }

        return response()->json($post, 200);
    }

    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        if(!$post) {
            abort(404);
        }

        $input = $request->all();
        $post->fill($input);
        $post->save();

        return response()->json($post, 200);
    }

    public function destroy($id)
    {
        $post = Post::find($id);

        if(!$post) {
            abort(404);
        }

        $post->delete();
        $message = ['message' => 'deleted successfully', 'post_id' => $id];

        return response()->json($message, 200);
    }
}