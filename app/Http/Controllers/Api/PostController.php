<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::orderBy('updated_at')->paginate(4);
        return response()->json($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        // return response()->json($data);

        $validator = Validator::make(
            $data,
            [
                'title' => 'required | string',
                'description' => 'string'
            ],
            [
                'title.required' => 'Title is mandatory'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $post = new Post();
        $post->title = $data['title'];
        $post->description = $data['description'];
        // $post->fill($data);
        $post->save();

        // return response()->json($post);
        return response()->json(null, 204);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::find($id);
        if (!$post) return response(null, 404);

        $prevPost = Post::where('id', '<', $id)->orderBy('id', 'DESC')->first();
        if (!$prevPost) $prevPost = Post::orderBy('id', 'DESC')->first();

        $nextPost = Post::where('id', '>', $id)->first();
        if (!$nextPost) $nextPost = Post::orderBy('id')->first();

        $post->prevId = $prevPost->id;
        $post->nextId = $nextPost->id;

        return response()->json($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}