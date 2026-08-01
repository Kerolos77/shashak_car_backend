<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends BaseController
{
    public function __construct(Setting $model)
    {
        parent::__construct($model);
    }
    public function update(Request $request)
    {
        $row = $this->model->firstOrFail();
        $data = $request->except(['group-a']);
        $data['increase'] = $request->increase ?? [];
        $data['percentage_increase'] = $request->percentage_increase ?? [];
        $data['active_type'] = $request->active_type;
        $data['shipping_enabled'] = $request->has('shipping_enabled') ? true : false;
        $data['ride_enabled'] = $request->has('ride_enabled') ? true : false;
        $data['travel_enabled'] = $request->has('travel_enabled') ? true : false;
        $data['intercity_enabled'] = $request->has('intercity_enabled') ? true : false;

        \Illuminate\Support\Facades\Log::info('--- SETTINGS UPDATE DEBUG ---');
        \Illuminate\Support\Facades\Log::info('Request active_type: ' . json_encode($request->active_type));
        \Illuminate\Support\Facades\Log::info('Data array to save: ', $data);

        $row->update($data);

        \Illuminate\Support\Facades\Log::info('Row active_type after update: ' . $row->active_type);

        return redirect()->route('admin.settings.index', 1);

    }

}
