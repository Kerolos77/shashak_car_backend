<?php

namespace App\Http\Controllers\Admin;

use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncomeController extends BaseController
{
    public function __construct(Income $model)
    {
        parent::__construct($model);
    }
    public function index()
    {
        $rows = $this->model->with('order', 'order.user', 'order.driver')->orderBy('created_at', 'DESC')->get();

        $todayIncome = DB::table('incomes')->whereDate('created_at', today())->sum('amount');

        $weekIncome = DB::table('incomes')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('amount');

        $monthIncome = DB::table('incomes')->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('amount');
        
        $yearIncome = DB::table('incomes')->whereBetween('created_at', [now()->startOfYear(), now()->endOfYear()])->sum('amount');

        return view('admin.incomes.index', compact('rows', 'todayIncome', 'weekIncome', 'monthIncome', 'yearIncome'));
    }
        
}
