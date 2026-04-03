<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\PushNotification;
use Illuminate\Support\Facades\Notification;

class AdminNotificationController extends Controller
{
    /**
     * Send a notification to specific targets (users, drivers, or specific user)
     */
    public function sendNotification(Request $request)
    {
        $request->validate([
            'target' => 'required|in:all_users,all_drivers,specific_user',
            'user_id' => 'required_if:target,specific_user|exists:users,id',
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image_url' => 'nullable|url'
        ]);

        $users = collect();

        if ($request->target === 'all_users') {
            // Get all normal users who have an FCM token
            $users = User::whereHas('roles', fn($q) => $q->where('title', 'User'))
                         ->whereNotNull('fcm_token')->get();
        } elseif ($request->target === 'all_drivers') {
            // Get all drivers who have an FCM token
            $users = User::whereHas('roles', fn($q) => $q->where('title', 'Driver'))
                         ->whereNotNull('fcm_token')->get();
        } elseif ($request->target === 'specific_user') {
            // Get specific user
            $user = User::find($request->user_id);
            if ($user && $user->fcm_token) {
                $users->push($user);
            }
        }

        if ($users->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No eligible users found with FCM tokens for the selected target.',
            ], 404);
        }

        // Send the notification using Laravel's Notification Facade (Saves to Database)
        Notification::send($users, new PushNotification(
            $request->title,
            $request->body,
            $request->image_url
        ));

        // Prepare FCM tokens
        $tokens = $users->pluck('fcm_token')->filter()->toArray();

        if (!empty($tokens)) {
            $messaging = app('firebase.messaging');
            $message = \Kreait\Firebase\Messaging\CloudMessage::new()
                ->withNotification([
                    'title' => $request->title,
                    'body' => $request->body,
                    'image' => $request->image_url,
                ]);

            // Send multicast to all valid tokens
            try {
                $messaging->sendMulticast($message, $tokens);
            } catch (\Exception $e) {
                // Log or handle firebase specific errors silently so we don't break the response
                \Log::error("FCM Send Error: " . $e->getMessage());
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Notification sent successfully to ' . $users->count() . ' user(s).',
            'data' => [
                'sent_count' => $users->count()
            ]
        ], 200);
    }
}
