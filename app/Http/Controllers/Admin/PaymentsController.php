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
        if ($user->wallet_amount < $request->amount) {
            return back()->with('error', 'Insufficient wallet balance for this driver.');
        }

        // --- Paymob Payout/Accept Disbursements API Call Placeholder ---
        // By default, Paymob Accept requires a separate Payout account/contract.
        // $paymob = new \App\Services\PaymobService();
        // $response = $paymob->transferToWallet($request->amount, $user->phone_number);
        // if (!$response['success']) { return back()->with('error', 'Paymob Transfer Failed: ' . $response['message']); }

        $user->update([
            'wallet_amount' => $user->wallet_amount - $request->amount
        ]);

        \App\Models\WalletTransaction::create([
            'user_id'     => $user->id,
            'amount'      => $request->amount,
            'type'        => 'withdraw',
            'description' => 'Withdrawal Request Approved (Wallet Transfer)'
        ]);

        $request->update([
            'success' => 1,
            'status'  => 'success'
        ]);

        return back()->with('success', 'Withdrawal approved and processed successfully.');
    }

    public function reject($id)
    {
        $request = WithdrawRequest::findOrFail($id);
        if ($request->status !== 'pending') {
            return back()->with('error', 'Request already processed.');
        }

        $request->update([
            'status'  => 'rejected'
        ]);

        return back()->with('success', 'Withdrawal request rejected.');
    }
}
