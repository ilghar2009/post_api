<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserToken;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function Auth(Request $request){

    //validate request
        $request->validate([
           'username' => ['required', 'min:3'],
           'password' => ['required', Password::min(8)->symbols()->numbers()->letters()],
        ]);

    //check if user exist login else create

        $check_user = $request->only('username', 'password');

        if($check_user){
            if(!Auth::attempt($check_user)){
                $user = new User();
                $user->username = $request->username;
                $user->password = $request->password;
                $user->save();

                return response()->json([
                    'message' => 'User registered successfully',
                    'user' => $user
                ],201);

            }else {

                $user = Auth::user();

                if($user->usertoken)
                    $user->usertoken->delete();

                $token = Str::uuid();

                UserToken::create([
                    'user_id' => $user->user_id,
                    'access_token' => $token,
                    'expires' => Carbon::now()->addMinute(30),
                ]);

                return response()->json([
                    'message' => 'login successful',
                    'token_access' => $token,
                    'token_type' => 'Bearer',
                ], 201);

            }
        }
    }

    public function login(Request $request){
        $check_user = $request->only('username', 'password');

        if(!$check_user || !Auth::attempt($check_user)){
            return response()->json(['error' => 'invalid credentials'], 401);
        }

        $user = Auth::user();

        $token = Str::uuid();

        UserToken::create([
           'user_id' => $user->user_id,
           'access_token' => $token,
           'expires' => Carbon::now()->addMinute(30),
        ]);

        return response()->json([
            'message' => 'login successful',
            'token_access' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }
}
