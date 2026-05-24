<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('drivers', function ($user) {
    return true;
});
Broadcast::channel('my-channel', function ($user) {
    return true; // or any condition to authorize the channel
});

// Location tracking channel authorization
Broadcast::channel('location.{id}', function ($user, $id) {
    // Users can subscribe to their own location channel
    if ((int) $user->id === (int) $id) {
        return true;
    }
    
    // Users can subscribe to another user's location if they have an active order together
    $targetUser = \App\Models\User::find($id);
    if (!$targetUser) {
        return false;
    }
    
    // Check if there's an active order between the users
    $hasActiveOrder = \App\Models\Order::where(function($query) use ($user, $id) {
        $query->where('user_id', $user->id)
              ->where('driver_id', $id);
    })
    ->orWhere(function($query) use ($user, $id) {
        $query->where('user_id', $id)
              ->where('driver_id', $user->id);
    })
    ->whereIn('status', ['pending', 'accepted', 'started'])
    ->exists();
    
    return $hasActiveOrder;
});

// Driver channel for receiving order notifications
// Drivers can only subscribe to their own channel
Broadcast::channel('driver.{id}', function ($user, $id) {
    \Illuminate\Support\Facades\Log::info('Broadcasting Auth Attempt', [
        'user_id' => $user ? $user->id : null,
        'user_class' => $user ? get_class($user) : null,
        'requested_channel_id' => $id,
        'is_match' => $user ? ((int) $user->id === (int) $id) : false
    ]);
    return $user && (int) $user->id === (int) $id;
});
