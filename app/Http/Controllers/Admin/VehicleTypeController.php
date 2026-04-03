<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VehicleType;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VehicleTypeController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('vehicle_type_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $pageTitle = __('app.vehicle_types');
        // Get All Vehicle Types
        $vehicleTypes = VehicleType::get();
        return view('admin.vehicle-type.index', compact('pageTitle', 'vehicleTypes'));
    }

    public function store(Request $request)
    {
        // validate Vehicle Type name
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        // Create Vehicle Type
        VehicleType::create([
            'name' => $request->name,
            // Check if enable is checked
            'enable' => $request->enable == 'on' ? true : false,
        ]);
        return redirect()->route('admin.vehicle-types.index');
    }

    public function edit(VehicleType $vehicleType)
    {
        abort_if(Gate::denies('vehicle_type_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.vehicle-type.edit', compact('vehicleType'));
    }

    public function show(VehicleType $vehicleType)
    {
        abort_if(Gate::denies('vehicle_type_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.vehicle-type.show', compact('vehicleType'));
    }
    public function deactivate($id)
    {
        $country = VehicleType::findOrFail($id);
        $country->enable = 0;
        $country->save();
    
        return redirect()->route('admin.vehicle-types.index');
    }
    
    public function activate($id)
    {
        $country = VehicleType::findOrFail($id);
        $country->enable = 1;
        $country->save();
    
        return redirect()->route('admin.vehicle-types.index');
    }
    // Create destroy Method to delete Vehicle Type
    public function destroy($id)
    {
        $vehicleType = VehicleType::findOrFail($id);
        $vehicleType->delete();
        return redirect()->route('admin.vehicle-types.index');
    }

}
