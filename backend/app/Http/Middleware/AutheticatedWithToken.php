<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class AutheticatedWithToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        $cookie = $request->cookie('sanctum_token');



        // No token
        if ($token) {
              // There is a encryptd bearer token
              $decryptedToken = Crypt::decryptString($token);
              $decryptedTokenDelimiter = "|";
              $decryptedTokenSub = strstr($decryptedToken, $decryptedTokenDelimiter);

              if($decryptedTokenSub) {
                  $decryptedTokenSub = substr($decryptedTokenSub, 1);
              }

              $token = PersonalAccessToken::findToken($decryptedTokenSub);

              $user = $request->user();

              if ($user && $token->expires_at->isPast()) {
                  // Token has expired
                  Auth::logout();
                  return response()->json(['message' => 'Your session has expired. Please log in again.'], 401);
              } else {
                  // Token is still valid
                  $user = User::where('id', $token->tokenable_id)->first();
                  Auth::login($user);
              }
        } elseif($cookie) {
            // There is a encryptd bearer token
            $decryptedToken = Crypt::decryptString($cookie);
            $decryptedTokenDelimiter = "|";
            $decryptedTokenSub = strstr($decryptedToken, $decryptedTokenDelimiter);

            if($decryptedTokenSub) {
                $decryptedTokenSub = substr($decryptedTokenSub, 1);
            }

            $token = PersonalAccessToken::findToken($decryptedTokenSub);

            $user = $request->user();

            if ($user && $token->expires_at->isPast()) {
                // Token has expired
                Auth::logout();
                return response()->json(['message' => 'Your session has expired. Please log in again.'], 401);
            } else {
                // Token is still valid
                $user = User::where('id', $token->tokenable_id)->first();
                Auth::login($user);
            }

        }
        // else {
        //     return response()->json(['error' => 'Please log in'], 401);
        // }

        return $next($request);
    }
}
