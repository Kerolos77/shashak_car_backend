<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register a custom broadcasting auth route that correctly resolves
        // the Sanctum-authenticated user. The default Broadcast::routes()
        // relies on $request->user() which uses the default guard ('admin'),
        // causing AccessDeniedHttpException for API (Sanctum) users.
        Route::match(['get', 'post'], '/api/broadcasting/auth', function (\Illuminate\Http\Request $request) {
            // Resolve user via Sanctum guard explicitly
            $user = auth('sanctum')->user();

            \Illuminate\Support\Facades\Log::info('Custom Broadcasting Auth', [
                'has_user' => $user ? true : false,
                'user_id' => $user ? $user->id : null,
                'channel' => $request->input('channel_name'),
            ]);

            if (!$user) {
                abort(403, 'Unauthorized: No authenticated user found via Sanctum.');
            }

            // Bind the Sanctum user to the request so PusherBroadcaster can find it
            $request->setUserResolver(fn () => $user);

            return Broadcast::auth($request);
        })
        ->middleware([
            'api',
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ])
        ->name('broadcasting.auth');

        require base_path('routes/channels.php');
    }
}
