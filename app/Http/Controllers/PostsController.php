<?php 

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;


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

        if (Gate::denies('read-post')){
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized'
            ], 403);
        }

        if(Auth::user()->role === 'admin'){
            $posts = Post::OrderBy("id", "DESC")->paginate(2)->toArray();
        } else {
            $posts = Post::Where(['user_id' => Auth::user()->id])->OrderBy("id", "DESC")->paginate(2)->toArray();
        }
        
        
            $response = [
                "total_count" => $posts["total"],
                "limit" => $posts["per_page"],
                "pagination" => [
                    "next_page" => $posts["next_page_url"],
                    "current_page" => $posts["current_page"]
                ],
                "data" => $posts["data"],
            ];


        if ($acceptHeader === 'application/json'|| $acceptHeader === 'application/xml') {
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

    public function store(Request $request) 
    {
            // $input = $request->all();
            // $post = Post::create($input);

            // return response()->json(['data' => $post], 200); 

        $acceptHeader = $request->header('Accept');

        if (Gate::denies('create-post')) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized to create post'
            ], 403);
        }

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml' ) {
            $contentTypeHeader = $request->header('Content-Type');

            if ($contentTypeHeader === 'application/json') {
                $input = $request->all();
                $validationRules = [
                    'title' => 'required|min:5',
                    'status' => 'required|in:draft,published',
                    'content' => 'required|min:10',
                    'user_id' => 'required|numeric',
                    'author_name' => 'required|min:5',
                    'is_featured' => 'required|boolean'
                ];
                $validator = Validator::make($input, $validationRules);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 400);
                }

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
        // Upload Media            
            } else {
                $input = $request->all();

                $validationRules = [
                    'title' => 'required|min:5',
                    'status' => 'required|in:draft,published',
                    'content' => 'required|min:10',
                    'user_id' => 'required|numeric',
                    'author_name' => 'required|min:5',
                    'is_featured' => 'required|boolean'
                ];

                $validator = \Validator::make($input, $validationRules);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 400);
                }

                $post = Post::create($input);

                if ($request->hasFile('image')) {

                    $firName = str_replace(' ', '_', $input['title']);

                    $imgName = $post->id . '_' . $firName . '_' . 'image';

                    $request->file('image')->move(storage_path('upload/image_profile'), $imgName);

                    $current_image_path = storage_path('upload/image_profile') . '/' . $post->image;
                    if (file_exists($current_image_path)) {
                        unlink($current_image_path);
                    }

                    $post->image = $imgName;
                }
                $post->save();

                return response()->json($post, 200);
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

            if (Gate::denies('show-post', $post)) {
                return response()->json([
                    'success' => false,
                    'status' => 403,
                    'message' => 'You are not authorized to do show by ID '
                ], 403);
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

                if (Gate::denies('update-post', $post)) {
                    return response()->json([
                        'success' => false,
                        'status' => 403,
                        'message' => 'You are not authorized to update the post'
                    ], 403);
                }

                $validationRules = [
                    'title' => 'required|min:5',
                    'status' => 'required|in:draft,published',
                    'content' => 'required|min:10',
                    'user_id' => 'required|numeric',
                    'author_name' => 'required|min:5',
                    'is_featured' => 'required|boolean'
                ];
                $validator = Validator::make($input, $validationRules);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 400);
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

            if (Gate::denies('delete-post', $post)) {
                return response()->json([
                    'success' => false,
                    'status' => 403,
                    'message' => 'You are unauthorized to delete post'
                ], 403);
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

    public function image($imageName) {
        $imagePath = storage_path('upload/image_profile') . '/' . $imageName;


        if (file_exists($imagePath)) {
            $file = file_get_contents($imagePath);
            return response($file, 200)->header('Content-Type', 'image/jpeg');
        }

        return response('Not Found', 404);
    }
}