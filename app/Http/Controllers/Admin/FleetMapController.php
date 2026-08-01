<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DriverProfile;
use App\Models\Service;
use Illuminate\Http\Request;

class FleetMapController extends Controller
{
    public function index()
    {
        $services = Service::where('enable', true)->get();
        return view('admin.fleet_map.index', compact('services'));
    }

    public function getDriversLocations(Request $request)
    {
        $query = DriverProfile::with(['user', 'service']);

        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $drivers = $query->get()->map(function ($driver) {
            return [
                'id' => $driver->id,
                'name' => $driver->user->name ?? 'سائق ' . $driver->id,
                'phone' => $driver->user->phone ?? '',
                'lat' => floatval($driver->lat ?? 30.0444),
                'lng' => floatval($driver->long ?? 31.2357),
                'status' => $driver->status,
                'is_online' => (bool)$driver->is_online,
                'service_title' => $driver->service->title ?? 'خدمة عامة',
                'service_type' => $driver->service->service_type ?? 'ride',
            ];
        });

        return response()->json([
            'success' => true,
            'drivers' => $drivers
        ]);
    }
}
