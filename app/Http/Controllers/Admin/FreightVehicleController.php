<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FileUploader;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFreightVehicleRequest;
use App\Http\Requests\UpdateFreightVehicleRequest;
use App\Models\FreightVehicle;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FreightVehicleController extends BaseController
{
    
    public function __construct(FreightVehicle $model)
    {
        parent::__construct($model);
    }
    public function dataHandler($request) {
        return [
            'name' => $request->name,
            'km_charge' => $request->km_charge ?? 0,
            'enable' => $request->has('enable') ? 1 : 0,
            'description' => $request->description,
            'height' => $request->height ?? 0,
            'width' => $request->width ?? 0,
        ];
    }
    public function store(StoreFreightVehicleRequest $request) 
    {
        abort_if(Gate::denies('freight_vehicle_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        try {
            $freightVehicle = $this->model->create($this->dataHandler($request));

            if ($request->hasFile('images')) {
                FileUploader::upload($freightVehicle, $request->images, 'freight_vehicle_images', 'multiple_image');
            }

            return redirect()->route('admin.freight-vehicles.index')->with('success', 'Freight vehicle created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error creating freight vehicle: ' . $e->getMessage());
        }
    }

    public function update($id, UpdateFreightVehicleRequest $request)
    {
        abort_if(Gate::denies('freight_vehicle_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        try {
            $freightVehicle = $this->model->findOrFail($id);
            $freightVehicle->update($this->dataHandler($request));

            if ($request->hasFile('images')) {
                FileUploader::upload($freightVehicle, $request->images, 'freight_vehicle_images', 'multiple_image');
            }

            return redirect()->route('admin.freight-vehicles.index')->with('success', 'Freight vehicle updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error updating freight vehicle: ' . $e->getMessage());
        }
    }
    
} 
