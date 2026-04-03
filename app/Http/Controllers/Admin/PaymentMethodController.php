<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{

    public function index()
    {
        $methods = PaymentMethod::get();
        $configs = config('nafezly-payments');
        $payment_methods = [];
        foreach ($methods as $method) {
            $configArray = [];
            $configArray['enabled'] = $method->enabled;
            $configArray['name'] = $method->name;
            $configArray['logo'] = $method->logo;
            foreach ($configs as $config => $value) {
                if (str_starts_with($config, $method->name)) {
                    $key = strtolower(str_replace($method->name . '_', "", $config));
                    if ($method->name == 'PERFECTMONEY' && $key == 'id') {
                        $key = 'api_key';
                    }
                    $configArray[$key] = $method->$key;
                }
            }
            $payment_methods[$method->name] = $configArray;
        }
        $pageTitle = __('app.payment_methods');
        return view('admin.payment-methods.index', compact('payment_methods', 'pageTitle'));
    }
    public function update(Request $request)
    {
        foreach ($request->all() as $method => $inputs) {
            $paymentMethod = PaymentMethod::where('name', $method)->first();
            if ($paymentMethod) {
                if (isset($inputs['enabled'])) {
                    $inputs['enabled'] = $inputs['enabled'] === 'on' ? 1 : 0;
                }    
                if (isset($inputs['logo'])) {
                    $logo = $inputs['logo'];
                    $filename = $method.'_'.time() . '.' . $logo->getClientOriginalExtension();
                    $logo->storeAs('public/logos', $filename);
                    // Get the full URL to the logo
                    $logoUrl = url('storage/logos/' . $filename);
                    $inputs['logo'] = $logoUrl;
                }    

                $paymentMethod->update($inputs);
            }
        }
        return redirect()->back()->with('success', 'Updated');
    }


    /*
        if ($request->hasFile('logo')) {
        $logo = $request->file('logo');
        $filename = time() . '.' . $logo->getClientOriginalExtension();
        $path = $logo->storeAs('public/logos', $filename);

        // Get the full URL to the logo
        $logoUrl = url('storage/logos/' . $filename);

        // Update the logo in the database
        $paymentMethod = PaymentMethod::where('name', $request->input('name'))->first();
        if ($paymentMethod) {
            $paymentMethod->logo = $logoUrl;
            $paymentMethod->save();
        }
    }
    */

}
