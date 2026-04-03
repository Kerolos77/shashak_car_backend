<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVehicleTypeRequest;
use App\Http\Requests\UpdateVehicleTypeRequest;
use App\Http\Resources\Admin\VehicleTypeResource;
use App\Models\VehicleType;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VehicleTypeApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('vehicle_type_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new VehicleTypeResource(VehicleType::all());
    }

    public function store(StoreVehicleTypeRequest $request)
    {
        $vehicleType = VehicleType::create($request->validated());

        return (new VehicleTypeResource($vehicleType))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(VehicleType $vehicleType)
    {
        abort_if(Gate::denies('vehicle_type_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new VehicleTypeResource($vehicleType);
    }

    public function update(UpdateVehicleTypeRequest $request, VehicleType $vehicleType)
    {
        $vehicleType->update($request->validated());

        return (new VehicleTypeResource($vehicleType))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(VehicleType $vehicleType)
    {
        abort_if(Gate::denies('vehicle_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $vehicleType->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
