<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request){

        $request->validate([
           'username' => ['required', 'min:3'],
           'password' => ['required', Password::min(8)->symbols()->numbers()->letters()],
        ]);

        $user = new User();
        $user->username = $request->username;
        $user->password = $request->password;
        $user->save();

        return true;
    }

    public function login(){

    }
}
