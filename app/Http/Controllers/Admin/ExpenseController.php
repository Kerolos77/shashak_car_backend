<?php

namespace App\Http\Controllers\Admin;

use App\Models\Expense;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends BaseController
{
    public function __construct(Expense $model)
    {
        parent::__construct($model);
    }

    public function index(Request $request = null)
    {
        $request = $request ?? request();
        $query = Expense::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('month')) {
            $parts = explode('-', $request->month);
            if (count($parts) == 2) {
                $query->whereYear('expense_date', $parts[0])
                      ->whereMonth('expense_date', $parts[1]);
            }
        }

        if ($request->filled('keyword')) {
            $query->where('description', 'like', '%' . $request->keyword . '%');
        }

        $rows = $query->orderBy('expense_date', 'DESC')->paginate(15)->appends($request->all());

        // Stats
        $filteredTotalEgp = (clone $query)->sum('amount_egp');

        $todayExpenses = Expense::whereDate('expense_date', today())->sum('amount_egp');

        $monthExpenses = Expense::whereBetween('expense_date', [
            now()->startOfMonth()->toDateString(), 
            now()->endOfMonth()->toDateString()
        ])->sum('amount_egp');

        $yearExpenses = Expense::whereBetween('expense_date', [
            now()->startOfYear()->toDateString(), 
            now()->endOfYear()->toDateString()
        ])->sum('amount_egp');

        // Net income for this month
        $monthIncomes = DB::table('incomes')
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('amount');

        $netProfit = $monthIncomes - $monthExpenses;

        $pageTitle = 'إدارة المصروفات';
        $pageDes = 'تصفية وعرض المصروفات التشغيلية للمشروع وإدخال المصروفات اليدوية';

        return view('admin.expenses.index', compact(
            'rows',
            'todayExpenses',
            'monthExpenses',
            'yearExpenses',
            'monthIncomes',
            'netProfit',
            'filteredTotalEgp',
            'pageTitle',
            'pageDes'
        ));
    }

    public function create()
    {
        $pageTitle = 'إضافة مصروف جديد';
        $pageDes = 'أدخل تفاصيل المصروف يدوياً وقم برفع الفاتورة المرفقة';
        return view('admin.expenses.create', compact('pageTitle', 'pageDes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|in:digitalocean,google_cloud,domain,other',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|in:EGP,USD',
            'description' => 'required|string',
            'expense_date' => 'required|date',
            'invoice_file' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120', // max 5MB
        ]);

        $setting = Setting::first();
        $exchangeRate = 1.00;

        if ($request->currency === 'USD') {
            $exchangeRate = $setting->usd_to_egp_exchange_rate ?? 50.00;
        }

        $amountEgp = $request->amount * $exchangeRate;

        $invoicePath = null;
        if ($request->hasFile('invoice_file')) {
            $invoicePath = $request->file('invoice_file')->store('invoices', 'public');
        }

        Expense::create([
            'category' => $request->category,
            'amount' => $request->amount,
            'currency' => $request->currency,
            'exchange_rate' => $exchangeRate,
            'amount_egp' => $amountEgp,
            'description' => $request->description,
            'expense_date' => $request->expense_date,
            'is_automated' => false,
            'invoice_path' => $invoicePath,
        ]);

        return redirect()->route('admin.expenses.index')->with('success', 'تم حفظ المصروف بنجاح.');
    }

    public function sync()
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('expenses:sync');
            return redirect()->route('admin.expenses.index')->with('success', 'تم تحديث وجلب المصروفات التلقائية بنجاح.');
        } catch (\Exception $e) {
            return redirect()->route('admin.expenses.index')->with('error', 'فشل تحديث المصروفات: ' . $e->getMessage());
        }
    }
}
