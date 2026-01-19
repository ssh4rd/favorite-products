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
        $userId = $request->cookie('user_id');

        if (!$userId || !User::find($userId)) {
            $user = User::create([
                'name' => 'Anonymous User',
                'email' => 'anon_' . Str::random(10) . '@example.com',
                'password' => Hash::make(Str::random(32)), // Generate secure random password
            ]);
            $userId = $user->id;

            // Set cookie for future requests
            cookie()->queue('user_id', $userId, 60 * 24 * 365); // 1 year
        }

        // Set user on request
        $request->merge(['user' => User::find($userId)]);

        return $next($request);
    }
}
