<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\Expense;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DigitalOceanBillingService
{
    /**
     * Fetch invoices from DigitalOcean API and store them in the expenses table.
     */
    public function fetchInvoices(): array
    {
        $setting = Setting::first();
        if (!$setting || empty($setting->digitalocean_api_token)) {
            Log::warning('DigitalOcean Billing Service: API Token is not configured in settings.');
            return ['success' => false, 'message' => 'API Token is missing.'];
        }

        $token = $setting->digitalocean_api_token;
        $exchangeRate = $setting->usd_to_egp_exchange_rate ?? 50.00;

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ])->timeout(30)->get('https://api.digitalocean.com/v2/customers/my/invoices');

            if (!$response->successful()) {
                Log::error('DigitalOcean API call failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return ['success' => false, 'message' => 'API responded with status: ' . $response->status()];
            }

            $data = $response->json();
            $invoices = $data['invoices'] ?? [];
            $importedCount = 0;

            foreach ($invoices as $invoice) {
                $invoiceId = $invoice['invoice_id'] ?? $invoice['invoice_uuid'] ?? null;
                if (!$invoiceId) continue;

                $amountUsd = floatval($invoice['amount'] ?? 0);
                if ($amountUsd <= 0) continue; // Skip $0 or unpaid/empty invoices

                $period = $invoice['invoice_period'] ?? '';
                $invoiceDate = null;

                if (!empty($period)) {
                    $periodParts = explode('-', $period);
                    if (count($periodParts) == 2) {
                        $year = intval($periodParts[0]);
                        $month = intval($periodParts[1]);
                        $invoiceDate = \Carbon\Carbon::createFromDate($year, $month, 1)->endOfMonth()->toDateString();
                    }
                }

                if (!$invoiceDate) {
                    $rawDate = $invoice['invoice_date'] ?? $invoice['updated_at'] ?? $invoice['created_at'] ?? null;
                    if ($rawDate) {
                        $invoiceDate = date('Y-m-d', strtotime($rawDate));
                    } else {
                        $invoiceDate = now()->toDateString();
                    }
                }

                $description = "فاتورة سيرفر ديجيتال أوشن للفترة {$period} (رقم الفاتورة: #{$invoiceId}، القيمة: \${$amountUsd})";

                // Unique key: category + reference_id
                $expense = Expense::where('category', 'digitalocean')
                    ->where('reference_id', $invoiceId)
                    ->first();

                if (!$expense) {
                    Expense::create([
                        'category' => 'digitalocean',
                        'reference_id' => $invoiceId,
                        'amount' => $amountUsd,
                        'currency' => 'USD',
                        'exchange_rate' => $exchangeRate,
                        'amount_egp' => $amountUsd * $exchangeRate,
                        'description' => $description,
                        'expense_date' => $invoiceDate,
                        'created_at' => $invoiceDate . ' 12:00:00',
                        'is_automated' => true,
                    ]);
                    $importedCount++;
                } else {
                    // Preserve existing historical exchange_rate & amount_egp! Only update date if needed.
                    $expense->expense_date = $invoiceDate;
                    if ($expense->created_at->format('Y-m-d') === now()->format('Y-m-d')) {
                        $expense->created_at = $invoiceDate . ' 12:00:00';
                    }
                    $expense->save();
                }
            }

            return [
                'success' => true,
                'message' => "Successfully processed invoices. Imported {$importedCount} new invoices.",
                'imported_count' => $importedCount
            ];

        } catch (\Exception $e) {
            Log::error('Exception in DigitalOceanBillingService: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }
}
