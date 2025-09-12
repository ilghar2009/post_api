<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $post = Post::all();

        return response()->json(compact($post), 200);
    }

    public function store(Request $request)
    {

        $token = $request->bearerToken();

        $request->validate([
            'title' => ['required'],
            'description' => ['required'],
        ]);

        $post = Post::create([
            'user_id' => $token->user->user_id,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return response()->json($post, 201);
    }

    public function update(Request $request, Post $post)
    {
        $token = $request->bearerToken();

        $request->validate([
            'title' => ['sometimes'],
            'description' => ['sometimes'],
        ]);

        if($post->user->user_id === $token->user->user_id) {

            $post->update([
                'title' => $request->title ?? $post->title,
                'description' => $request->description ?? $post->description,
            ]);

            return response()->json($post, 201);

        }else
            return response()->json([
                'error' => 'you cant change this post',
            ], 401);

    }

    public function destroy(Request $request, Post $post)
    {
        $token = $request->bearerToken();

        if(!$post->user->user_id === $token->user->user_id)
            return response()->json([
               'error' => 'you cant delete this post',
            ], 401);

        else
        {
            $post->delete();
            return response()->json([
               'message' => 'successful delete post'
            ], 201);
        }
    }
}
