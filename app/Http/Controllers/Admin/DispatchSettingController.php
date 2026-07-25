<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Marketopia\MarketopiaCity;
use Illuminate\Http\Request;

class DispatchSettingController extends Controller
{
    public function index()
    {
        $setting = Setting::firstOrCreate([], [
            'max_cash_pickup_distance_km' => 10.0,
            'max_card_pickup_distance_km' => 15.0,
            'destination_mode_tolerance_km' => 5.0,
            'auto_cash_ban_enabled' => true,
            'max_driver_cash_debt_limit' => 200.00,
            'cash_restriction_duration_minutes' => 60,
            'max_consecutive_cancellations_before_ban' => 3,
            'min_driver_rating_for_cash' => 4.00,
            'dispatch_priority_strategy' => 'distance',
            'city_override_settings' => [],
        ]);

        $cities = MarketopiaCity::where('is_active', 1)->get();
        if ($cities->isEmpty()) {
            $cities = MarketopiaCity::all();
        }

        return view('admin.dispatch-settings.index', compact('setting', 'cities'));
    }

    public function update(Request $request)
    {
        $setting = Setting::first();
        if (!$setting) {
            $setting = new Setting();
        }

        $request->validate([
            'max_cash_pickup_distance_km' => 'required|numeric|min:0.5|max:200',
            'max_card_pickup_distance_km' => 'required|numeric|min:0.5|max:200',
            'destination_mode_tolerance_km' => 'required|numeric|min:0.5|max:50',
            'max_driver_cash_debt_limit' => 'required|numeric|min:0',
            'cash_restriction_duration_minutes' => 'required|integer|min:1',
            'max_consecutive_cancellations_before_ban' => 'required|integer|min:1',
            'min_driver_rating_for_cash' => 'required|numeric|min:1|max:5',
            'dispatch_priority_strategy' => 'required|string|in:distance,rating,fair_share',
        ]);

        $data = [
            'max_cash_pickup_distance_km' => (float)$request->max_cash_pickup_distance_km,
            'max_card_pickup_distance_km' => (float)$request->max_card_pickup_distance_km,
            'destination_mode_tolerance_km' => (float)$request->destination_mode_tolerance_km,
            'auto_cash_ban_enabled' => $request->has('auto_cash_ban_enabled') ? true : false,
            'max_driver_cash_debt_limit' => (float)$request->max_driver_cash_debt_limit,
            'cash_restriction_duration_minutes' => (int)$request->cash_restriction_duration_minutes,
            'max_consecutive_cancellations_before_ban' => (int)$request->max_consecutive_cancellations_before_ban,
            'min_driver_rating_for_cash' => (float)$request->min_driver_rating_for_cash,
            'dispatch_priority_strategy' => $request->dispatch_priority_strategy,
            'city_override_settings' => $request->city_override_settings ?? [],
        ];

        $setting->update($data);

        return redirect()->route('admin.dispatch-settings.index')->with('success', __('تم حفظ إعدادات التوزيع والحظر بنجاح.'));
    }
}
