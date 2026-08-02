<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\Expense;
use App\Models\SmsLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SmsBillingService
{
    /**
     * Calculate monthly SMS expenses from sms_logs and record them in expenses table.
     */
    public function syncSmsExpenses(): array
    {
        $setting = Setting::first();
        $costPerSms = floatval($setting->sms_cost_per_message ?? 0.25);
        if ($costPerSms <= 0) {
            $costPerSms = 0.25;
        }

        try {
            // Group sms_logs by year and month
            $logsByMonth = SmsLog::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total_sms')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

            $importedCount = 0;

            foreach ($logsByMonth as $item) {
                $year = $item->year;
                $month = str_pad($item->month, 2, '0', STR_PAD_LEFT);
                $monthKey = "{$year}-{$month}";
                $totalSms = intval($item->total_sms);

                if ($totalSms <= 0) continue;

                $totalCostEgp = $totalSms * $costPerSms;
                $expenseDate = Carbon::createFromDate($year, $item->month, 1)->endOfMonth()->toDateString();
                $refId = "sms_cost_{$year}_{$month}";
                $description = "تكلفة إرسال رسائل SMS لشهـر {$monthKey} (إجمالي الرسائل: {$totalSms} رسالة بسعر {$costPerSms} ج.م/رسالة)";

                $expense = Expense::where('category', 'sms')
                    ->where('reference_id', $refId)
                    ->first();

                if (!$expense) {
                    Expense::create([
                        'category' => 'sms',
                        'reference_id' => $refId,
                        'amount' => $totalCostEgp,
                        'currency' => 'EGP',
                        'exchange_rate' => 1.00,
                        'amount_egp' => $totalCostEgp,
                        'description' => $description,
                        'expense_date' => $expenseDate,
                        'created_at' => $expenseDate . ' 23:59:59',
                        'is_automated' => true,
                    ]);
                    $importedCount++;
                } else {
                    // Preserve existing historical SMS cost! Only update date if needed.
                    $expense->expense_date = $expenseDate;
                    if ($expense->created_at->format('Y-m-d') === now()->format('Y-m-d')) {
                        $expense->created_at = $expenseDate . ' 23:59:59';
                    }
                    $expense->save();
                }
            }

            return [
                'success' => true,
                'message' => "Successfully calculated SMS expenses. Processed {$importedCount} new entries.",
                'imported_count' => $importedCount
            ];

        } catch (\Exception $e) {
            Log::error('Exception in SmsBillingService: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }
}
