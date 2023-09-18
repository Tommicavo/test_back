<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::orderBy('updated_at', 'DESC')->paginate(4);
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
                'title' => 'required | string | unique:posts',
                'description' => 'string'
            ],
            [
                'title.required' => 'Title is mandatory',
                'title.unique' => 'A post with this title already exists, choose another one'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $post = new Post();
        $post->fill($data);
        $post->save();

        return response()->json(null, 204);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::find($id);
        if (!$post) $post = Post::onlyTrashed()->find($id);
        if (!$post) return response(null, 404);

        if (!$post->deleted_at) {
            $prevPost = Post::where('id', '<', $id)->orderBy('id', 'DESC')->first();
            if (!$prevPost) $prevPost = Post::orderBy('id', 'DESC')->first();

            $nextPost = Post::where('id', '>', $id)->first();
            if (!$nextPost) $nextPost = Post::orderBy('id')->first();

            $post->prevId = $prevPost->id;
            $post->nextId = $nextPost->id;
        } else {
            $prevPost = Post::onlyTrashed()->where('id', '<', $id)->orderBy('id', 'DESC')->first();
            if (!$prevPost) $prevPost = Post::onlyTrashed()->orderBy('id', 'DESC')->first();

            $nextPost = Post::onlyTrashed()->where('id', '>', $id)->first();
            if (!$nextPost) $nextPost = Post::onlyTrashed()->orderBy('id')->first();

            $post->prevId = $prevPost->id;
            $post->nextId = $nextPost->id;
        }

        return response()->json($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();

        $validator = Validator::make(
            $data,
            [
                'title' => ['required', 'string', Rule::unique('posts')->ignore($id)],
                'description' => 'string'
            ],
            [
                'title.required' => 'Title is mandatory',
                'title.unique' => 'A post with this title already exists, choose another one'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $post = Post::find($id);
        if (!$post) return response(null, 404);

        $post->update($data);

        return response()->json(null, 204);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);
        if (!$post) return response(null, 404);

        $post->delete();
        return response()->json(null, 204);
    }

    public function trash()
    {
        $posts = Post::onlyTrashed()->get();
        return response()->json($posts);
    }

    public function restore(string $id)
    {
        $post = Post::onlyTrashed()->find($id);
        if (!$post) return response(null, 404);

        $post->restore();
        return response()->json(null, 204);
    }

    public function drop(string $id)
    {
        $post = Post::onlyTrashed()->find($id);
        if (!$post) return response(null, 404);

        $post->forceDelete();
        return response()->json(null, 204);
    }
}
