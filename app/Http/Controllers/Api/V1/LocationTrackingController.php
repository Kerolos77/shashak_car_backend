<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\LocationUpdated;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocationTrackingController extends Controller
{
    /**
     * Update user/driver location and broadcast to listeners
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateLocation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        // Decrement cash restriction time based on online duration
        if ($user->cash_restriction_seconds_remaining > 0) {
            $now = now();
            // Prevent huge jumps if they were offline for a long time. Max 60 seconds decrement per ping.
            // If they ping every 10 seconds, this will accurately deduct 10 seconds.
            // If they close the app for an hour and ping, it will only deduct max 60 seconds.
            $secondsDiff = 0;
            if ($user->updated_at) {
                $rawUpdatedAt = $user->getRawOriginal('updated_at');
                if ($rawUpdatedAt) {
                    $secondsDiff = \Carbon\Carbon::parse($rawUpdatedAt)->diffInSeconds($now);
                }
            }
            if ($secondsDiff > 0 && $secondsDiff <= 120) {
                $user->cash_restriction_seconds_remaining = max(0, $user->cash_restriction_seconds_remaining - $secondsDiff);
            }
        }

        // Update user location
        $user->latitude = $request->latitude;
        $user->longitude = $request->longitude;
        $user->save();

        // Broadcast location update event
        broadcast(new LocationUpdated($user))->toOthers();

        return response()->json([
            'success' => true,
            'message' => 'Location updated successfully',
            'data' => [
                'user_id' => $user->id,
                'latitude' => $user->latitude,
                'longitude' => $user->longitude,
                'updated_at' => $user->updated_at
            ]
        ]);
    }

    /**
     * Get current location of a user/driver
     * 
     * @param Request $request
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLocation(Request $request, $userId)
    {
        $currentUser = $request->user();
        $targetUser = User::find($userId);

        if (!$targetUser) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Check authorization
        // Users can view their own location or locations of users they have active orders with
        if ($currentUser->id !== $targetUser->id && !$this->hasActiveOrderWith($currentUser, $targetUser)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to view this location'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'user_id' => $targetUser->id,
                'latitude' => $targetUser->latitude,
                'longitude' => $targetUser->longitude,
                'updated_at' => $targetUser->updated_at
            ]
        ]);
    }

    /**
     * Get nearby drivers within 2km radius
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNearbyDrivers(Request $request)
    {
        $user = $request->user();

        // Use provided coordinates or user's last known location
        $latitude = $request->latitude ?? $user->latitude;
        $longitude = $request->longitude ?? $user->longitude;

        if (!$latitude || !$longitude) {
            return response()->json([
                'success' => false,
                'message' => 'Location coordinates required'
            ], 400);
        }

        $radius = 2; // 2km as requested

        // Haversine formula to find drivers within radius
        $drivers = User::drivers()
            ->where('id', '!=', $user->id) // Exclude self if user is also a driver
            ->where('is_active', 1)
            ->where('is_online', 1) // Only show online drivers
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->select('users.*')
            ->selectRaw(
                "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance",
                [$latitude, $longitude, $latitude]
            )
            ->having('distance', '<=', $radius)
            ->orderBy('distance')
            ->get();

        // Format response
        $data = $drivers->map(function ($driver) {
            return [
                'id' => $driver->id,
                'name' => $driver->name,
                'latitude' => $driver->latitude,
                'longitude' => $driver->longitude,
                'distance' => round($driver->distance, 2) . ' km',
                // Add car details if available
                'car' => $driver->profile && $driver->profile->driver_cars ? [
                    'brand' => $driver->profile->driver_cars->brand,
                    'model' => $driver->profile->driver_cars->model,
                    'color' => $driver->profile->driver_cars->color,
                    'plate' => $driver->profile->driver_cars->car_number,
                ] : null
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Check if two users have an active order together
     * 
     * @param User $user1
     * @param User $user2
     * @return bool
     */
    private function hasActiveOrderWith(User $user1, User $user2)
    {
        // Check if there's an active order where user1 is the customer and user2 is the driver
        // or vice versa
        $activeOrder = Order::where(function ($query) use ($user1, $user2) {
            $query->where('user_id', $user1->id)
                ->where('driver_id', $user2->id);
        })
            ->orWhere(function ($query) use ($user1, $user2) {
                $query->where('user_id', $user2->id)
                    ->where('driver_id', $user1->id);
            })
            ->whereIn('status', [Order::STATUS_PENDING, Order::STATUS_NEGOTIATING, Order::STATUS_ASSIGNED, Order::STATUS_ARRIVED, Order::STATUS_ON_TRIP]) // Active order statuses
            ->exists();

        return $activeOrder;
    }
}
