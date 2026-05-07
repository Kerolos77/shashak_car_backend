<?php

namespace App\Http\Controllers\Admin;

use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends BaseController
{
    public function __construct(Package $model)
    {
        parent::__construct($model);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'user_type' => 'required|in:driver,user',
            'description' => 'nullable|string',
            'duration_hours' => 'required|integer|min:1',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'price_points' => 'required|integer|min:0',
            'price_cash' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        if (!$request->has('is_active')) {
            $data['is_active'] = 1;
        }

        $this->model->create($data);

        return redirect()->route('admin.packages.index')->with('success', trans('global.create_success') ?? 'Created successfully');
    }

    public function update(Request $request, $id)
    {
        $row = $this->model->findOrFail($id);
        
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'user_type' => 'required|in:driver,user',
            'description' => 'nullable|string',
            'duration_hours' => 'required|integer|min:1',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'price_points' => 'required|integer|min:0',
            'price_cash' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        if (!$request->has('is_active')) {
            $data['is_active'] = 0;
        }

        $row->update($data);

        return redirect()->route('admin.packages.index')->with('success', trans('global.update_success') ?? 'Updated successfully');
    }
}
