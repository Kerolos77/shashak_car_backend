<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Update the FCM token for the authenticated user.
     */
    public function updateFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user = Auth::user();
        $user->update(['fcm_token' => $request->fcm_token]);

        return response()->json([
            'status' => 'success',
            'message' => 'FCM Token updated successfully.',
            'data' => null
        ], 200);
    }

    /**
     * Get the list of notifications for the authenticated user.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $notifications = $user->notifications()->paginate(15);

        return response()->json([
            'status' => 'success',
            'message' => 'Notifications retrieved successfully.',
            'data' => $notifications
        ], 200);
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
            return response()->json([
                'status' => 'success',
                'message' => 'Notification marked as read.',
                'data' => null
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Notification not found.',
            'data' => null
        ], 404);
    }

    /**
     * Get the count of unread notifications.
     */
    public function unreadCount()
    {
        $user = Auth::user();
        $count = $user->unreadNotifications()->count();

        return response()->json([
            'status' => 'success',
            'message' => 'Unread notifications count retrieved.',
            'data' => ['unread_count' => $count]
        ], 200);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();

        return response()->json([
            'status' => 'success',
            'message' => 'All notifications marked as read.',
            'data' => null
        ], 200);
    }
}
