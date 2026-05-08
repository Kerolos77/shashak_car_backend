<?php

namespace App\Http\Controllers\Admin;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PackageController extends BaseController
{
    public function __construct(Package $model)
    {
        parent::__construct($model);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'user_type' => 'required|in:driver,user',
            'description' => 'nullable|string',
            'duration_hours' => 'required|integer|min:1',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'price_points' => 'required|integer|min:0',
            'price_cash' => 'required|numeric|min:0',
            'is_active' => 'boolean',
            'photo' => 'nullable|image|max:5120',
        ]);

        $data = $request->except('photo');
        $data['is_active'] = $request->has('is_active') ? 1 : 1;

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('packages', $filename, 'public');
            $data['image_url'] = $path;
        }

        $this->model->create($data);

        return redirect()->route('admin.packages.index')->with('success', trans('global.create_success') ?? 'Created successfully');
    }

    public function update(Request $request, $id)
    {
        $row = $this->model->findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'user_type' => 'required|in:driver,user',
            'description' => 'nullable|string',
            'duration_hours' => 'required|integer|min:1',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'price_points' => 'required|integer|min:0',
            'price_cash' => 'required|numeric|min:0',
            'is_active' => 'boolean',
            'photo' => 'nullable|image|max:5120',
        ]);

        $data = $request->except('photo');
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('photo')) {
            // Optional: delete old file if it exists
            if ($row->image_url && Storage::disk('public')->exists($row->image_url)) {
                Storage::disk('public')->delete($row->image_url);
            }

            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('packages', $filename, 'public');
            $data['image_url'] = $path;
        }

        $row->update($data);

        return redirect()->route('admin.packages.index')->with('success', trans('global.update_success') ?? 'Updated successfully');
    }
}
