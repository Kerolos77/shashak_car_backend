<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Using custom broadcasting auth route to debug the issue deeply on the server.
        \Illuminate\Support\Facades\Route::match(['get', 'post'], '/api/broadcasting/auth', function (\Illuminate\Http\Request $request) {
            $user = auth('sanctum')->user();
            
            // Get channel name from request
            $channelName = $request->channel_name;
            $normalizedChannelName = \Illuminate\Support\Str::replaceFirst('private-', '', $channelName);

            \Illuminate\Support\Facades\Log::info('Custom Broadcast Debug', [
                'has_user' => $user ? true : false,
                'user_id' => $user ? $user->id : null,
                'channel' => $channelName,
                'normalized' => $normalizedChannelName,
                'socket_id' => $request->socket_id,
            ]);

            if (!$user) {
                return response()->json(['message' => 'Unauthorized user'], 403);
            }

            // Force bind user to request so PusherBroadcaster sees it
            $request->setUserResolver(function ($guard = null) use ($user) {
                return $user;
            });
            auth()->setUser($user);
            
            try {
                return Broadcast::auth($request);
            } catch (\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException $e) {
                \Illuminate\Support\Facades\Log::error('AccessDenied thrown by Broadcaster!', [
                    'request_user' => $request->user() ? $request->user()->id : null,
                    'request_user_sanctum' => $request->user('sanctum') ? $request->user('sanctum')->id : null,
                ]);
                
                // FALLBACK: manually authenticate if Broadcast::auth() fails
                $broadcaster = Broadcast::driver();
                if ($broadcaster instanceof \Illuminate\Broadcasting\Broadcasters\PusherBroadcaster) {
                    $pusher = $broadcaster->getPusher();
                    $encodedUser = json_encode(['id' => $user->id, 'user_info' => $user]);
                    $auth = $pusher->socket_auth($channelName, $request->socket_id);
                    return response($auth);
                }
                
                throw $e;
            }
        })
        ->middleware([
            'api',
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ])
        ->name('broadcasting.auth');

        require base_path('routes/channels.php');
    }
}

