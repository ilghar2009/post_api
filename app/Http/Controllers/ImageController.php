<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\UserToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function index(Request $request)
    {
        $access_token = $request->bearerToken();
        $token = UserToken::where('access_token', $access_token)->first();

        $user = $token->user;
        $images = $user->images;

        return response()->json(['data' => $images], 200);
    }

    public function store(Request $request)
    {
        //validate
            $request->validate([
               'file' => ['required', 'max:1024', 'mimes:jpg,jpeg,png'],
            ]);

        //get user
            $access_token = $request->bearerToken();
            $token = UserToken::where('access_token', $access_token)->first();

        //upload image
            $path = $request->file('file')->store('image', 'public');

        //get image info
            $url = Storage::disk('public')->url($path);
            $size = $request->file('file')->getSize();

        //create recorde

        $s = (int) $size / 1024;

            Image::create([
                'user_id' => $token->user->user_id,
                'address' => $url,
                'size' => $s,
            ]);

            return response()->json(['data' => $url, 'size' => $s], 201);
    }
}
