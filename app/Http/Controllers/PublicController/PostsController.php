<?php

namespace App\Http\Controllers\PublicController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class PostsController extends Controller
{
    /**
     * @OA\Get(
     *    path="/api/public/posts",
     *    summary="Get list of posts",
     *    @OA\Parameter(name="page",
     *    in="query",
     *    required=false,
     *    @OA\Schema(type="number")
     * ),
     * @OA\Response(
     *    response=200,
     *    description="OK"
     *    )
     * )
     */

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $acceptHeader = $request->header('Accept');
        $posts = Post::with('user')->OrderBy("id", "DESC")->paginate(10);
        $response = [
            "total_count" => $posts["total"],
            "limit" => $posts["per_page"],
            "pagination" => [
                "next_page" => $posts["next_page_url"],
                "current_page" => $posts["current_page"]
            ],
            "data" => $posts["data"],
        ];
        //dd($response);

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            //JSON
            if ($acceptHeader === 'application/json') {
                return response()->json($response, 200);
            } else {
                // XML
                $xml = new \SimpleXMLElement('<posts/>');
                foreach ($posts->items('data') as $item) {

                    $xmlItem = $xml->addChild('post');

                    $xmlItem->addChild('id', $item->id);
                    $xmlItem->addChild('title', $item->title);
                    $xmlItem->addChild('status', $item->status);
                    $xmlItem->addChild('content', $item->content);
                    $xmlItem->addChild('user_id', $item->user_id);
                    $xmlItem->addChild('author_name', $item->author_name);
                    $xmlItem->addChild('is_featured', $item->is_featured);
                    $xmlItem->addChild('created_at', $item->created_at);
                    $xmlItem->addChild('updated_at', $item->updated_at);
                }
                return $xml->asXML();
            }

        } else {
            return response('Not Acceptable', 406);
        }
    }

    public function show(Request $request, $id)
    {
        // $post = Post::find($id);

        $acceptHeader = $request->header("Accept");

        $post = Post::with(['user' => function($query){
            $query->select('id','name');
        }])->find($id);

        if(!$post){
            abort(404);
        }

        if($acceptHeader === 'application/json' || $acceptHeader === 'application/xml'){
            // JSON
            if($acceptHeader === 'application/json'){
                return response()->json($post,200);
            }else{
                // XML
                $xml = new \SimpleXMLElement('<post/>');

                $xml->addChild('id', $post->id);
                $xml->addChild('title', $post->title);
                $xml->addChild('status', $post->status);
                $xml->addChild('content', $post->content);
                $xml->addChild('user_id', $post->user_id);
                $xml->addChild('author_name', $post->author_name);
                $xml->addChild('is_featured', $post->is_featured);
                $xml->addChild('created_at', $post->created_at);
                $xml->addChild('updated_at', $post->updated_at);

                return $xml->asXML();
            }
        }else{  
            return response('Not Acceptable', 406);
        }

    }
}