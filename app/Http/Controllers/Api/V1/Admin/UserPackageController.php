<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserPackageController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $packages = DB::table('driver_packages')
            ->where('is_active', true)
            ->where('user_type', 'user')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'points' => $user->points,
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
        $package = DB::table('driver_packages')
            ->where('id', $request->package_id)
            ->where('user_type', 'user')
            ->first();

        if (!$package || !$package->is_active) {
            return response()->json(['success' => false, 'message' => 'Package not available.'], 400);
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

        DB::table('driver_purchases')->insert([
            'driver_id' => $user->id, // driver_id column in table refers to user_id in this context
            'package_id' => $package->id,
            'expires_at' => now()->addHours($package->duration_hours),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true, 
            'message' => 'Package purchased successfully.', 
            'points' => $user->points, 
            'wallet' => $user->wallet
        ]);
    }
}
