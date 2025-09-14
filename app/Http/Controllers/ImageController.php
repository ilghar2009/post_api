<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\UserToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validate
            $request->validate([
               'file' => ['required', 'max:1024', 'mime:jpg,jpeg,png'],
            ]);

        //get user
            $access_token = $request->bearerToken();
            $token = UserToken::where('access_token', $access_token)->first();

        //upload image
            $path = $request->file('file')->store('image', 'public');

        //get image info
            $url = Storage::disk('public')->url($path);
            $size = $request->file('image')->getSize();

        //create recorde
            Image::create([
                'user_id' => $token->user->user_id,
                'address' => $url,
                'size' => $size,
            ]);

            return response()->json(['data' => $url, 'size' => $size], 201);
    }

    public function show(Image $image)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Image $image)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Image $image)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Image $image)
    {
        //
    }
}
