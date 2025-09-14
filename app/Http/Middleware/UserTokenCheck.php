<?php

namespace App\Http\Middleware;

use App\Models\UserToken;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserTokenCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token_access = $request->bearerToken();

        $token = UserToken::where('access_token', $token_access)->first();

    if(!$token || $token->user->name and $token->expires > Carbon::now()){
            return \response()->json([
               'error' => 'invalid token',
            ], 403);
        }

        return $next($request);

    }
}
