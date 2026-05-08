<?php

namespace App\Http\Controllers\Admin;

use App\Models\Package;
use Illuminate\Http\Request;
use App\Helpers\FileUploader;
use Illuminate\Support\Facades\Log;

class PackageController extends BaseController
{
    public function __construct(Package $model)
    {
        parent::__construct($model);
    }

    public function store(Request $request)
    {
        Log::info('Package Store Request', [
            'has_file' => $request->hasFile('photo'),
            'all' => $request->all()
        ]);
        $request->validate([
            'name' => 'required|string|max:255',
            'user_type' => 'required|in:driver,user',
            'description' => 'nullable|string',
            'duration_hours' => 'required|integer|min:1',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'price_points' => 'required|integer|min:0',
            'price_cash' => 'required|numeric|min:0',
            'is_active' => 'boolean',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('photo');
        $data['is_active'] = $request->has('is_active') ? 1 : 1; // Default to 1 if not provided in create

        $package = $this->model->create($data);

        if ($request->hasFile('photo')) {
            FileUploader::upload($package, $request->file('photo'), 'package_photo', 'single_image');
        }

        return redirect()->route('admin.packages.index')->with('success', trans('global.create_success') ?? 'Created successfully');
    }

    public function update(Request $request, $id)
    {
        $row = $this->model->findOrFail($id);
        
        Log::info('Package Update Request', [
            'id' => $id,
            'has_file' => $request->hasFile('photo'),
            'all' => $request->all()
        ]);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'user_type' => 'required|in:driver,user',
            'description' => 'nullable|string',
            'duration_hours' => 'required|integer|min:1',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'price_points' => 'required|integer|min:0',
            'price_cash' => 'required|numeric|min:0',
            'is_active' => 'boolean',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('photo');
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        $row->update($data);

        if ($request->hasFile('photo')) {
            $row->clearMediaCollection('package_photo');
            FileUploader::upload($row, $request->file('photo'), 'package_photo', 'single_image');
        }

        return redirect()->route('admin.packages.index')->with('success', trans('global.update_success') ?? 'Updated successfully');
    }
}
