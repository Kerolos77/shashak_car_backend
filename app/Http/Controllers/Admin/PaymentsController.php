<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use App\Models\User;
use App\Models\WithdrawRequest;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    function index() 
    {
        $rows       = PaymentTransaction::with('user')->orderBy('id', 'desc')->paginate(20);
        $pageTitle = __('app.transactions');

        return view('admin.payments.index', compact('rows', 'pageTitle'));
    }
    function requests() 
    {
        $rows       = WithdrawRequest::with('user')->orderBy('created_at', 'desc')->paginate(20);
        $pageTitle = trans('global.withdraw_requests');

        return view('admin.payments.requests', compact('rows', 'pageTitle'));

    }
    
    public function accept($id)
    {
        $request = WithdrawRequest::findOrFail($id);
        if ($request->status !== 'pending') {
            return back()->with('error', 'Request already processed.');
        }

        $user = User::find($request->userID);
        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $user) {
            // Find the pending WalletTransaction and update its description
            $walletTx = \App\Models\WalletTransaction::where('user_id', $user->id)
                ->where('amount', $request->amount)
                ->where('type', 'withdraw')
                ->where('description', 'like', '%Pending%')
                ->latest()
                ->first();

            if ($walletTx) {
                $walletTx->update([
                    'description' => 'تمت الموافقة على طلب السحب (Withdrawal Request Approved)'
                ]);
            }

            $request->update([
                'success' => 1,
                'status'  => 'success'
            ]);
        });

        return back()->with('success', 'Withdrawal approved and processed successfully.');
    }

    public function reject($id)
    {
        $request = WithdrawRequest::findOrFail($id);
        if ($request->status !== 'pending') {
            return back()->with('error', 'Request already processed.');
        }

        $user = User::find($request->userID);
        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $user) {
            // 1. Refund the amount back to user's wallet
            $user->update([
                'wallet_amount' => $user->wallet_amount + $request->amount
            ]);

            // 2. Log a refund WalletTransaction of type 'deposit'
            \App\Models\WalletTransaction::create([
                'user_id'     => $user->id,
                'amount'      => $request->amount,
                'type'        => 'deposit',
                'description' => 'إرجاع رصيد لرفض طلب السحب (Withdrawal Request Rejected - Refunded)'
            ]);

            // 3. Find the original pending WalletTransaction and mark it as rejected
            $walletTx = \App\Models\WalletTransaction::where('user_id', $user->id)
                ->where('amount', $request->amount)
                ->where('type', 'withdraw')
                ->where('description', 'like', '%Pending%')
                ->latest()
                ->first();

            if ($walletTx) {
                $walletTx->update([
                    'description' => 'طلب سحب مرفوض (Withdrawal Request Rejected)'
                ]);
            }

            // 4. Update the request status
            $request->update([
                'status'  => 'rejected',
                'success' => 0
            ]);
        });

        return back()->with('success', 'Withdrawal request rejected.');
    }
}
