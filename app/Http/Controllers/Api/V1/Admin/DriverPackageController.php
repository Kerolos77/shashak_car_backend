<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DriverPackageController extends Controller
{
    public function index(Request $request)
    {
        $driver = $request->user();
        $packages = DB::table('driver_packages')
            ->where('is_active', true)
            ->where('user_type', 'driver')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'points' => $driver->points,
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

        $driver = $request->user();
        $package = DB::table('driver_packages')
            ->where('id', $request->package_id)
            ->where('user_type', 'driver')
            ->first();

        if (!$package || !$package->is_active) {
            return response()->json(['success' => false, 'message' => 'Package not available.'], 400);
        }

        if ($request->payment_method === 'points') {
            if ($driver->points < $package->price_points) {
                return response()->json(['success' => false, 'message' => 'Not enough points.'], 400);
            }
            $driver->points -= $package->price_points;
        } else {
            // Assuming driver wallet is stored in 'wallet' column of users or profile. 
            // In Shakshak Car, wallet is usually on the user table or transactions table.
            if ($driver->wallet < $package->price_cash) {
                return response()->json(['success' => false, 'message' => 'Not enough balance in wallet.'], 400);
            }
            $driver->wallet -= $package->price_cash;
        }

        $driver->save();

        DB::table('driver_purchases')->insert([
            'driver_id' => $driver->id,
            'package_id' => $package->id,
            'expires_at' => now()->addHours($package->duration_hours),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json(['success' => true, 'message' => 'Package purchased successfully.', 'points' => $driver->points, 'wallet' => $driver->wallet]);
    }
}
