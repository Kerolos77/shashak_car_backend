<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetSanctumGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Try all guards to find the authenticated user (Sanctum first)
        $user = auth('sanctum')->user() ?? auth('api')->user() ?? auth('web')->user();

        \Illuminate\Support\Facades\Log::info('SetSanctumGuard executed', [
            'has_user' => $user ? true : false,
            'user_id' => $user ? $user->id : null,
            'bearer_token_exists' => $request->bearerToken() ? true : false,
        ]);

        if ($user) {
            // Explicitly bind the resolved user to the request so $request->user() works
            $request->setUserResolver(fn () => $user);
            auth()->setUser($user);
            auth()->shouldUse('sanctum');
        }

        return $next($request);
    }
}
