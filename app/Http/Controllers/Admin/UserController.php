<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.user.index');
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.user.create');
    }

    public function edit(User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.user.edit', compact('user'));
    }

    public function show(User $user)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->load(['roles', 'referrals', 'referrer', 'identity']);

        $orderStats = [
            'total' => \App\Models\Order::where('user_id', $user->id)->count(),
            'completed' => \App\Models\Order::where('user_id', $user->id)->where('status', \App\Models\Order::STATUS_COMPLETED)->count(),
            'canceled' => \App\Models\Order::where('user_id', $user->id)->where('status', \App\Models\Order::STATUS_CANCELED)->count(),
            'total_spent' => \App\Models\Order::where('user_id', $user->id)->where('status', \App\Models\Order::STATUS_COMPLETED)->sum('final_rate'),
        ];

        $reviews = \App\Models\Review::where('to_user_id', $user->id)->with('fromUser')->orderBy('id', 'desc')->get();
        $auditLogs = \App\Models\AdminUserAuditLog::where('user_id', $user->id)->with('admin')->orderBy('id', 'desc')->get();

        return view('admin.user.show', compact('user', 'orderStats', 'reviews', 'auditLogs'));
    }

    public function toggleVip($id)
    {
        $user = User::findOrFail($id);
        $user->is_vip = !$user->is_vip;
        $user->save();

        $statusText = $user->is_vip ? 'تفعيل شارة العميل المميز (VIP)' : 'إلغاء شارة VIP';

        \App\Models\AdminUserAuditLog::create([
            'admin_id' => \Illuminate\Support\Facades\Auth::guard('admin')->id() ?? \Illuminate\Support\Facades\Auth::id(),
            'user_id'  => $user->id,
            'action'   => 'toggle_vip',
            'notes'    => "تم {$statusText} للعميل.",
        ]);

        return redirect()->back()->with('success', __("تم {$statusText} بنجاح."));
    }

    public function addWalletBalance(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'notes' => 'nullable|string|max:255',
        ]);

        $user = User::findOrFail($id);

        $amount = (float)$request->amount;
        $user->wallet_amount = ($user->wallet_amount ?? 0) + $amount;
        $user->save();

        \App\Models\WalletTransaction::create([
            'user_id' => $user->id,
            'amount' => abs($amount),
            'type' => $amount >= 0 ? 'deposit' : 'withdraw',
            'status' => 'completed',
            'notes' => $request->notes ?? 'إضافة/خصم رصيد يدوي من الإدارة',
        ]);

        \App\Models\AdminUserAuditLog::create([
            'admin_id' => \Illuminate\Support\Facades\Auth::guard('admin')->id() ?? \Illuminate\Support\Facades\Auth::id(),
            'user_id'  => $user->id,
            'action'   => 'add_wallet',
            'notes'    => "تم تغيير رصيد المحفظة بمبلغ ({$amount} ج.م) - السبب: " . ($request->notes ?? 'إضافة يدوية'),
        ]);

        return redirect()->back()->with('success', __('تم تعديل رصيد محفظة العميل وتسجيل المعاملة بنجاح.'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();
        return redirect()->back();
    }
}
