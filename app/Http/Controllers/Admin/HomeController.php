<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\DriverProfile;
use App\Models\Order;
use App\Models\Income;
use App\Models\Service;
use App\Models\PaymentTransaction;
use App\Models\WalletTransaction;
use App\Models\room;
use App\Models\Admin;
use App\Models\Faq;
use App\Models\Page;
use App\Models\Airport;
use App\Models\Caption;
use App\Models\PaymentMethod;
use App\Models\WithdrawRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController
{
    public function index()
    {
        // Get current date ranges
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $thisWeek = Carbon::now()->startOfWeek();
        $lastWeek = Carbon::now()->subWeek()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $thisYear = Carbon::now()->startOfYear();
        $lastYear = Carbon::now()->subYear()->startOfYear();

        // Basic Counts
        $totalUsers = User::count();
        $totalDrivers = DriverProfile::count();
        $totalOrders = Order::count();
        $totalServices = Service::count();
        $totalAdmins = Admin::count();
        $totalFaqs = Faq::count();
        $totalPages = Page::count();
        $totalAirports = Airport::count();
        $totalCaptions = Caption::count();
        $totalPaymentMethods = PaymentMethod::count();

        // Today's Statistics
        $todayUsers = User::whereDate('created_at', $today)->count();
        $todayDrivers = DriverProfile::whereDate('created_at', $today)->count();
        $todayOrders = Order::whereDate('created_at', $today)->count();
        $todayIncome = Income::whereDate('created_at', $today)->sum('amount');
        $todayChats = room::whereDate('created_at', $today)->count();

        // This Week's Statistics
        $weekUsers = User::whereBetween('created_at', [$thisWeek, Carbon::now()])->count();
        $weekDrivers = DriverProfile::whereBetween('created_at', [$thisWeek, Carbon::now()])->count();
        $weekOrders = Order::whereBetween('created_at', [$thisWeek, Carbon::now()])->count();
        $weekIncome = Income::whereBetween('created_at', [$thisWeek, Carbon::now()])->sum('amount');

        // This Month's Statistics
        $monthUsers = User::whereBetween('created_at', [$thisMonth, Carbon::now()])->count();
        $monthDrivers = DriverProfile::whereBetween('created_at', [$thisMonth, Carbon::now()])->count();
        $monthOrders = Order::whereBetween('created_at', [$thisMonth, Carbon::now()])->count();
        $monthIncome = Income::whereBetween('created_at', [$thisMonth, Carbon::now()])->sum('amount');

        // This Year's Statistics
        $yearUsers = User::whereBetween('created_at', [$thisYear, Carbon::now()])->count();
        $yearDrivers = DriverProfile::whereBetween('created_at', [$thisYear, Carbon::now()])->count();
        $yearOrders = Order::whereBetween('created_at', [$thisYear, Carbon::now()])->count();
        $yearIncome = Income::whereBetween('created_at', [$thisYear, Carbon::now()])->sum('amount');

        // Driver Status Statistics
        $activeDrivers = DriverProfile::where('status', 'active')->count();
        $pendingDrivers = DriverProfile::where('status', 'pending')->count();
        $blockedDrivers = DriverProfile::where('status', 'blocked')->count();

        // Order Status Statistics
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();

        // Financial Statistics
        $totalIncome = Income::sum('amount');
        $totalWalletTransactions = WalletTransaction::count();
        $totalPaymentTransactions = PaymentTransaction::count();
        $totalWithdrawRequests = WithdrawRequest::count();
        $pendingWithdrawRequests = WithdrawRequest::where('status', 'pending')->count();

        // Recent Activity (Last 7 days)
        $recentUsers = User::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        $recentDrivers = DriverProfile::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        $recentOrders = Order::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        $recentIncome = Income::where('created_at', '>=', Carbon::now()->subDays(7))->sum('amount');

        // Growth Calculations
        $userGrowth = $this->calculateGrowth($monthUsers, $lastMonth ? User::whereBetween('created_at', [$lastMonth, $thisMonth->subDay()])->count() : 0);
        $driverGrowth = $this->calculateGrowth($monthDrivers, $lastMonth ? DriverProfile::whereBetween('created_at', [$lastMonth, $thisMonth->subDay()])->count() : 0);
        $orderGrowth = $this->calculateGrowth($monthOrders, $lastMonth ? Order::whereBetween('created_at', [$lastMonth, $thisMonth->subDay()])->count() : 0);
        $incomeGrowth = $this->calculateGrowth($monthIncome, $lastMonth ? Income::whereBetween('created_at', [$lastMonth, $thisMonth->subDay()])->sum('amount') : 0);

        // Chart Data (Last 30 days)
        $chartData = $this->getChartData();

        // Recent Detailed Records for V2 Dashboard Lists
        $recentOrdersList = Order::with(['user', 'driver', 'service'])->orderBy('id', 'desc')->limit(5)->get();
        $recentDriversList = DriverProfile::with(['user', 'service'])->orderBy('id', 'desc')->limit(5)->get();
        $recentWithdrawalsList = WithdrawRequest::with(['user'])->orderBy('id', 'desc')->limit(5)->get();

        return view('admin.home', compact(
            // Basic Counts
            'totalUsers', 'totalDrivers', 'totalOrders', 'totalServices',
            'totalAdmins', 'totalFaqs', 'totalPages', 'totalAirports', 'totalCaptions',
            'totalPaymentMethods',
            
            // Today's Statistics
            'todayUsers', 'todayDrivers', 'todayOrders', 'todayIncome', 'todayChats',
            
            // Week's Statistics
            'weekUsers', 'weekDrivers', 'weekOrders', 'weekIncome',
            
            // Month's Statistics
            'monthUsers', 'monthDrivers', 'monthOrders', 'monthIncome',
            
            // Year's Statistics
            'yearUsers', 'yearDrivers', 'yearOrders', 'yearIncome',
            
            // Status Statistics
            'activeDrivers', 'pendingDrivers', 'blockedDrivers',
            'pendingOrders', 'completedOrders', 'cancelledOrders',
            
            // Financial Statistics
            'totalIncome', 'totalWalletTransactions', 'totalPaymentTransactions',
            'totalWithdrawRequests', 'pendingWithdrawRequests',
            
            // Recent Activity
            'recentUsers', 'recentDrivers', 'recentOrders', 'recentIncome',
            
            // Growth
            'userGrowth', 'driverGrowth', 'orderGrowth', 'incomeGrowth',
            
            // Chart Data
            'chartData',

            // Recent Detailed Lists
            'recentOrdersList', 'recentDriversList', 'recentWithdrawalsList'
        ));
    }

    private function calculateGrowth($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        return round((($current - $previous) / $previous) * 100, 2);
    }

    private function getChartData()
    {
        $data = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            // Get actual data or use 0 if no data exists
            $users = User::whereDate('created_at', $date)->count();
            $drivers = DriverProfile::whereDate('created_at', $date)->count();
            $orders = Order::whereDate('created_at', $date)->count();
            $income = Income::whereDate('created_at', $date)->sum('amount') ?? 0;
            
            $data[] = [
                'date' => $date->format('M d'),
                'users' => $users,
                'drivers' => $drivers,
                'orders' => $orders,
                'income' => $income,
            ];
        }
        return $data;
    }
}
