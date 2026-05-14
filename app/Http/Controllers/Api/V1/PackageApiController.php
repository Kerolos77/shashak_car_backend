<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Purchase;

class PackageApiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Determine user type (driver or user)
        $isDriver = $user->roles()->where('title', 'Driver')->exists() || 
                    $user->roles()->where('title', 'driver')->exists() ||
                    $user->profile()->exists();

        $userType = $isDriver ? 'driver' : 'user';

        $packages = Package::where('is_active', true)
            ->where('user_type', $userType)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'points' => $user->points,
                'wallet' => $user->wallet_amount,
                'user_type' => $userType,
                'active_package' => $user->active_package,
                'packages' => $packages
            ]
        ]);
    }

    public function buy(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:driver_packages,id',
            'payment_method' => 'required|in:points,cash'
        ]);

        $user = $request->user();
        
        // Determine user type
        $isDriver = $user->roles()->where('title', 'Driver')->exists() || 
                    $user->roles()->where('title', 'driver')->exists() ||
                    $user->profile()->exists();

        $userType = $isDriver ? 'driver' : 'user';

        $package = Package::where('id', $request->package_id)
            ->where('user_type', $userType)
            ->first();

        if (!$package || !$package->is_active) {
            return response()->json(['success' => false, 'message' => 'Package not available for your user type.'], 400);
        }

        // Check if user already has an active package
        if ($user->active_package) {
            return response()->json(['success' => false, 'message' => 'You already have an active package. Cannot subscribe to a new one.'], 400);
        }

        if ($request->payment_method === 'points') {
            if ($user->points < $package->price_points) {
                return response()->json(['success' => false, 'message' => 'Not enough points.'], 400);
            }
            $user->points -= $package->price_points;
        } else {
            if ($user->wallet_amount < $package->price_cash) {
                return response()->json(['success' => false, 'message' => 'Not enough balance in wallet.'], 400);
            }
            $user->wallet_amount -= $package->price_cash;
        }

        $user->save();

        Purchase::create([
            'driver_id' => $user->id,
            'package_id' => $package->id,
            'expires_at' => now()->addHours($package->duration_hours > 0 ? $package->duration_hours : 24),
        ]);

        return response()->json([
            'success' => true, 
            'message' => 'Package purchased successfully.', 
            'points' => $user->points, 
            'wallet' => $user->wallet_amount
        ]);
    }

    public function status(Request $request)
    {
        $user = $request->user();
        
        $purchase = Purchase::where('driver_id', $user->id)
            ->with('package')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$purchase) {
            return response()->json([
                'success' => true,
                'data' => [
                    'is_subscribed' => false,
                    'points' => $user->points,
                    'wallet' => $user->wallet_amount,
                    'message' => 'No active subscription found.'
                ]
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'is_subscribed' => true,
                'points' => $user->points,
                'wallet' => $user->wallet_amount,
                'subscription' => [
                    'purchase_id' => $purchase->id,
                    'expires_at' => $purchase->expires_at,
                    'days_remaining' => now()->diffInDays($purchase->expires_at),
                    'hours_remaining' => now()->diffInHours($purchase->expires_at),
                    'package' => $purchase->package
                ]
            ]
        ]);
    }
}
