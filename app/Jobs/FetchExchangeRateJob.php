<?php

namespace App\Jobs;

use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchExchangeRateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $response = Http::timeout(15)->get('https://open.er-api.com/v6/latest/USD');

            if ($response->successful()) {
                $rate = floatval($response->json('rates.EGP'));
                
                if ($rate > 10) { // Safety check to prevent saving invalid values
                    $setting = Setting::first();
                    if ($setting) {
                        $setting->update([
                            'usd_to_egp_exchange_rate' => $rate
                        ]);
                        Log::info("USD to EGP Exchange Rate updated successfully: " . $rate);
                    }
                }
            } else {
                Log::warning("Failed to fetch exchange rate: API error.");
            }
        } catch (\Exception $e) {
            Log::error("Error fetching exchange rate: " . $e->getMessage());
        }
    }
}
