<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\UserToken;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {

        //get user_token for get user
            $access_token = $request->bearerToken();
            $token = UserToken::where('access_token', $access_token)->first();

        //get user for get him posts
            $user = $token->user;

        $posts = $user->posts;

        return response()->json([
            'data' => $posts
        ], 200);

    }

    public function store(Request $request)
    {

        $access_token = $request->bearerToken();

        $token = UserToken::where('access_token', $access_token)->first();

        $request->validate([
            'title' => ['required', 'unique:posts,title'],
            'description' => ['required'],
        ]);

        $post = Post::create([
            'user_id' => $token->user->user_id,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return response()->json(["data" => $post], 201);
    }

    public function update(Request $request, Post $post)
    {
        $access_token = $request->bearerToken();

        $token = UserToken::where('access_token', $access_token)->first();

        $request->validate([
            'title' => ['sometimes', 'unique:posts,title'],
            'description' => ['sometimes'],
        ]);

        if($post->user->user_id === $token->user->user_id) {

            $post->update($request->all());

            return response()->json(['data' => $post->makeHidden('user')], 201);

        }else
            return response()->json([
                'error' => 'you cant change this post',
            ], 403);

    }

    public function destroy(Request $request, Post $post)
    {

        $access_token = $request->bearerToken();

        $token = UserToken::where('access_token', $access_token)->first();

        if(!$post->user->user_id === $token->user->user_id)
            return response()->json([
               'error' => 'you cant delete this post',
            ], 403);

        else
        {
            $post->delete();
            return response()->json([
               'message' => 'successful delete post'
            ], 204);
        }

    }
}