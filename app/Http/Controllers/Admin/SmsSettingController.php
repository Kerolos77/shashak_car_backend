<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\SmsHelper;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\SmsLog;
use Illuminate\Http\Request;

class SmsSettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first() ?? new Setting();
        $logs = SmsLog::latest()->paginate(15);

        return view('admin.sms_settings.index', compact('setting', 'logs'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'sms_base_url' => 'required|string',
            'sms_sender' => 'required|string',
        ]);

        $setting = Setting::first();
        if (!$setting) {
            $setting = new Setting();
        }

        $setting->fill([
            'sms_enabled' => $request->has('sms_enabled') ? true : false,
            'sms_base_url' => $request->sms_base_url,
            'sms_username' => $request->sms_username,
            'sms_password' => $request->sms_password,
            'sms_sender' => $request->sms_sender,
            'sms_message_template' => $request->sms_message_template,
            'sms_shipping_template' => $request->sms_shipping_template,
            'sms_shipping_verification_template' => $request->sms_shipping_verification_template,
            'sms_cost_per_message' => $request->filled('sms_cost_per_message') ? floatval($request->sms_cost_per_message) : 0.2500,
        ]);
        $setting->save();

        return redirect()->back()->with('success', 'تم حفظ إعدادات وقوالب نظام الرسائل SMS النصية بنجاح!');
    }

    public function sendTest(Request $request)
    {
        $request->validate([
            'mobile' => 'required|string',
            'message' => 'required|string',
        ]);

        $smsHelper = new SmsHelper();
        $response = $smsHelper->sendCustomSms($request->mobile, $request->message, 'test');

        return redirect()->back()->with('test_result', $response);
    }
}
