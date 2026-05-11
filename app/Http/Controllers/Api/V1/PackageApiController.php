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
        $userType = $user->roles()->where('title', 'Driver')->exists() ? 'driver' : 'user';

        $packages = Package::where('is_active', true)
            ->where('user_type', $userType)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'points' => $user->points,
                'wallet' => $user->wallet,
                'user_type' => $userType,
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
        $userType = $user->roles()->where('title', 'Driver')->exists() ? 'driver' : 'user';

        $package = Package::where('id', $request->package_id)
            ->where('user_type', $userType)
            ->first();

        if (!$package || !$package->is_active) {
            return response()->json(['success' => false, 'message' => 'Package not available for your user type.'], 400);
        }

        if ($request->payment_method === 'points') {
            if ($user->points < $package->price_points) {
                return response()->json(['success' => false, 'message' => 'Not enough points.'], 400);
            }
            $user->points -= $package->price_points;
        } else {
            if ($user->wallet < $package->price_cash) {
                return response()->json(['success' => false, 'message' => 'Not enough balance in wallet.'], 400);
            }
            $user->wallet -= $package->price_cash;
        }

        $user->save();

        Purchase::create([
            'driver_id' => $user->id,
            'package_id' => $package->id,
            'expires_at' => now()->addHours($package->duration_hours),
        ]);

        return response()->json([
            'success' => true, 
            'message' => 'Package purchased successfully.', 
            'points' => $user->points, 
            'wallet' => $user->wallet
        ]);
    }
}
