<?php 

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function index(Request $request)
    {
        // $posts = Post::OrderBy("id", "DESC")->paginate(10);

        // $outPut = [
        //     "message" => "posts",
        //     "results" => $posts
        // ];

        // return response()->json($posts, 200);

        $acceptHeader = $request->header('Accept');

        if ($acceptHeader === 'application/json'|| $acceptHeader === 'application/xml') {
            $posts = Post::OrderBy("id", "DESC")->paginate(10);
                //JSON
            if ($acceptHeader === 'application/json') {
                return response()->json($posts->items('data'), 200);
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

    public function store(Request $request) 
    {
            // $input = $request->all();
            // $post = Post::create($input);

            // return response()->json(['data' => $post], 200); 

        $acceptHeader = $request->header('Accept');

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            
            $contentTypeHeader = $request->header('Content-Type');

            if ($contentTypeHeader === 'application/json') {
                $input = $request->all();

                $post = Post::create($input);
                return response()->json($post, 200);

            } else if ($contentTypeHeader === 'application/xml') {
                $xmldata = $request->getContent();
                $xml = simplexml_load_string($xmldata);

                    if ($xml === false) {
                        return response('Bad Request', 400);
                    } else {
                        $post = Post::create([
                            'title' => $xml->title,
                            'status' => $xml->status,
                            'content' => $xml->content,
                            'user_id' => $xml->user_id,
                            'author_name' => $xml->author_name,
                            'is_featured' => $xml->is_featured
                        ]);
                        
                        if ($post->save()) {
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

    

    public function show(Request $request, $id)
    {
        $acceptHeader = $request->header("Accept");
        if ($acceptHeader == "application/json"){
            $post = Post::find($id);

            if(!$post) {
                abort(404);
            }

        return response()->json($post, 200);
        } elseif ($acceptHeader === "application/xml") {
            $post = Post::find($id);

            if(!$post) {
                abort(404);
            }

            $xml = new \SimpleXMLElement('<post/>');
            $xmlItem = $xml->addChild('post');

            $xmlItem->addChild('id', $post->id);
            $xmlItem->addChild('title', $post->title);
            $xmlItem->addChild('status', $post->status);
            $xmlItem->addChild('content', $post->content);
            $xmlItem->addChild('user_id', $post->user_id);
            $xmlItem->addChild('author_name', $post->author_name);
            $xmlItem->addChild('is_featured', $post->is_featured);
            $xmlItem->addChild('created_at', $post->created_at);
            $xmlItem->addChild('updated_at', $post->updated_at);    
            
            return $xml->asXML();
        }else {
            return response('Not Acceptable!', 406);
        } 
    }

    public function update(Request $request, $id)
    {
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader == 'application/json' || $acceptHeader==="application/xml") {
            $contentTypeHeader = $request->header("Content-Type");
            if ($contentTypeHeader == "application/json") {
                $input = $request->all();
                $post = Post::find($id);

                if(!$post) {
                    return response('post not found', 404);
                }

                $post->fill($input);
                $post->save();

                return response()->json($post, 200);
            } elseif ($contentTypeHeader == "application/xml") {
                $xmldata = $request->getContent();
                $xml = simplexml_load_string($xmldata);

                if ($xml === false) {
                    return response('Bad Request', 400);
                } else {
                    $post = Post::find($id);

                    if(!$post) {
                        return response('post not found', 400);
                    }else {
                        $input = [
                            'title' => $xml->title,
                            'status' => $xml->status,
                            'content' => $xml->content,
                            'user_id' => $xml->user_id,
                            'author_name' => $xml->author_name,
                            'is_featured' => $xml->is_featured
                        ];

                        $post->fill($input);
                        if ($post->save()){
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
        $acceptHeader = $request->header("Accept");
        if ($acceptHeader == "application/json") {
            $post = Post::find($id);

            if(!$post) {
                return response('post not found', 404);
            }

            $post->delete();
            $message = ['message' => 'deleted successfully', 'post_id' => $id];

            return response()->json($message, 200);
        } elseif ($acceptHeader === "application/xml") {
            $post = Post::find($id);

            if(!$post) {
                return response('post not found', 404);
            }

            if($post->delete()){
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