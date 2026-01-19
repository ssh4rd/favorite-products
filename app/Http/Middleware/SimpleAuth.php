<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class SimpleAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = null;

        // Check for Bearer token first (for testing)
        $authHeader = $request->header('Authorization');
        if ($authHeader && str_starts_with($authHeader, 'Bearer ')) {
            $token = substr($authHeader, 7);
            // For testing, we'll use a simple token format: 'test-token-{userId}'
            if (str_starts_with($token, 'test-token-')) {
                $userId = (int) str_replace('test-token-', '', $token);
                $user = User::find($userId);
                if (!$user) {
                    return response()->json(['message' => 'Unauthorized'], 401);
                }
            } else {
                // Invalid Bearer token
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        } else {
            // No Bearer token - use cookie-based authentication
            $token = $request->cookie('auth_token');

            if ($token && ($user = User::where('remember_token', $token)->first())) {
                // Valid user from cookie
            } else {
                // No cookie - create a new user and set cookie
                $token = Str::random(60);
                $user = User::create([
                    'name' => 'Anonymous User',
                    'email' => 'anonymous-' . Str::random(10) . '@example.com',
                    'password' => Hash::make(Str::random(10)),
                    'remember_token' => $token,
                ]);

                // Return response with cookie set
                $response = $next($request->merge(['user' => $user]));
                return $response->withCookie(cookie('auth_token', $token, 60 * 24 * 30)); // 30 days
            }
        }

        // Set user on request
        $request->merge(['user' => $user]);

        return $next($request);
    }
}
