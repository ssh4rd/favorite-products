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
            }
        }

        // Fall back to cookie-based authentication
        if (!$user) {
            $userId = $request->cookie('user_id');

            if ($userId && ($user = User::find($userId))) {
                // Valid user from cookie
            } else {
                // No valid authentication - return 401
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        }

        // Set user on request
        $request->merge(['user' => $user]);

        return $next($request);
    }
}
