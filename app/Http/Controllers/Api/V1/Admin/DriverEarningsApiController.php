<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Income;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DriverEarningsApiController extends Controller
{
    /**
     * Get earnings summary for a driver
     */
    public function summary(Request $request)
    {
        $driverId = Auth::id();
        $period = $request->query('period', 'today'); // today, week, month, custom
        
        $query = Order::where('driver_id', $driverId)
            ->where('status', Order::STATUS_COMPLETED);

        $this->applyDateFilter($query, $period, $request);

        $orders = $query->get();
        $orderIds = $orders->pluck('id');

        // 1. Gross and Payment Type Breakdown
        $grossEarnings = (float) $orders->sum('offer_rate');
        $cashCollected = (float) $orders->sum('cash_paid');
        $digitalEarnings = (float) ($orders->sum('wallet_paid') + $orders->sum('card_paid'));

        // 2. Commission calculation using Income model
        $totalCommission = (float) Income::whereIn('order_id', $orderIds)->sum('amount');
        
        $netEarnings = $grossEarnings - $totalCommission;

        // 3. Chart Data (Grouped by Day)
        $chartData = $this->getChartData($driverId, $period, $request);

        return response()->json([
            'success' => true,
            'data' => [
                'summary' => [
                    'gross_earnings' => round($grossEarnings, 2),
                    'net_earnings' => round($netEarnings, 2),
                    'total_commission' => round($totalCommission, 2),
                    'cash_collected' => round($cashCollected, 2),
                    'digital_earnings' => round($digitalEarnings, 2),
                    'completed_trips_count' => $orders->count(),
                ],
                'chart_data' => $chartData,
                'period' => $period
            ]
        ]);
    }

    /**
     * Get detailed earnings history (trips)
     */
    public function history(Request $request)
    {
        $driverId = Auth::id();
        $period = $request->query('period', 'today');

        $query = Order::with(['user', 'service'])
            ->where('driver_id', $driverId)
            ->where('status', Order::STATUS_COMPLETED);

        $this->applyDateFilter($query, $period, $request);

        $orders = $query->latest('completed_at')->paginate(15);

        // Map data to include commission per trip
        $items = $orders->getCollection()->map(function ($order) {
            $income = Income::where('order_id', $order->id)->first();
            $commission = $income ? (float) $income->amount : 0;
            
            return [
                'id' => $order->id,
                'completed_at' => $order->completed_at ? $order->completed_at->format('Y-m-d H:i') : null,
                'user_name' => $order->user ? $order->user->full_name : 'N/A',
                'service_title' => $order->service ? $order->service->title : 'N/A',
                'gross_earnings' => (float) $order->offer_rate,
                'net_earnings' => (float) $order->offer_rate - $commission,
                'commission' => $commission,
                'payment_breakdown' => [
                    'cash' => (float) $order->cash_paid,
                    'digital' => (float) ($order->wallet_paid + $order->card_paid),
                    'payment_type' => $order->payment_type
                ]
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $items,
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'total' => $orders->total()
            ]
        ]);
    }

    /**
     * Helper to apply date filters
     */
    private function applyDateFilter($query, $period, $request)
    {
        switch ($period) {
            case 'today':
                $query->whereDate('completed_at', Carbon::today());
                break;
            case 'week':
                $query->whereBetween('completed_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('completed_at', Carbon::now()->month)
                      ->whereYear('completed_at', Carbon::now()->year);
                break;
            case 'custom':
                if ($request->has('start_date') && $request->has('end_date')) {
                    $query->whereBetween('completed_at', [
                        Carbon::parse($request->start_date)->startOfDay(),
                        Carbon::parse($request->end_date)->endOfDay()
                    ]);
                }
                break;
        }
    }

    /**
     * Generate chart data points
     */
    private function getChartData($driverId, $period, $request)
    {
        $query = DB::table('orders')
            ->select(
                DB::raw('DATE(completed_at) as date'),
                DB::raw('SUM(offer_rate) as gross'),
                DB::raw('COUNT(*) as count')
            )
            ->where('driver_id', $driverId)
            ->where('status', Order::STATUS_COMPLETED)
            ->groupBy('date')
            ->orderBy('date', 'ASC');

        // Apply same filters
        if ($period == 'today') {
            $query->whereDate('completed_at', Carbon::today());
        } elseif ($period == 'week') {
            $query->whereBetween('completed_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($period == 'month') {
            $query->whereMonth('completed_at', Carbon::now()->month)
                  ->whereYear('completed_at', Carbon::now()->year);
        } elseif ($period == 'custom') {
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('completed_at', [
                    Carbon::parse($request->start_date)->startOfDay(),
                    Carbon::parse($request->end_date)->endOfDay()
                ]);
            }
        }

        $results = $query->get();

        // Calculate net for each point (fetching incomes for these orders)
        return $results->map(function($point) use ($driverId) {
            $orderIds = DB::table('orders')
                ->where('driver_id', $driverId)
                ->whereDate('completed_at', $point->date)
                ->where('status', Order::STATUS_COMPLETED)
                ->pluck('id');
            
            $commission = DB::table('incomes')
                ->whereIn('order_id', $orderIds)
                ->sum('amount');

            return [
                'date' => $point->date,
                'gross' => (float) $point->gross,
                'net' => (float) $point->gross - (float) $commission,
                'trips' => $point->count
            ];
        });
    }
}
