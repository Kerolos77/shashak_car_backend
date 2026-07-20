<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;

class PublicTrackingController extends Controller
{
    /**
     * Show the public order tracking page.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function trackOrderPublic($id)
    {
        // Find the shipping order
        $order = Order::with(['driver', 'user'])
            ->where('id', $id)
            ->first();

        // If order doesn't exist, we can still show the page but with an error message or just links to download the app
        $settings = Setting::first();
        
        $playStoreUrl = $settings->play_store_url ?? 'https://play.google.com/store';
        $appStoreUrl = $settings->app_store_url ?? 'https://apps.apple.com';

        return view('public_tracking', [
            'order' => $order,
            'playStoreUrl' => $playStoreUrl,
            'appStoreUrl' => $appStoreUrl
        ]);
    }
}
