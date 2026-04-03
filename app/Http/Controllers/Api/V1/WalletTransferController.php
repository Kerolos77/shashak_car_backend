<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class WalletTransferController extends Controller
{
    /**
     * Transfer money from driver wallet to user wallet
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function transferToUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'note' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $driver = $request->user();
        $userId = $request->user_id;
        $amount = $request->amount;

        // Check if the driver is not trying to transfer to themselves
        if ($driver->id == $userId) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot transfer to yourself'
            ], 400);
        }

        // Check if driver has sufficient balance
        if ($driver->wallet_amount < $amount) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient wallet balance',
                'data' => [
                    'current_balance' => $driver->wallet_amount,
                    'requested_amount' => $amount
                ]
            ], 400);
        }

        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Use database transaction to ensure atomicity
        DB::beginTransaction();

        try {
            // Deduct from driver wallet
            $driver->update([
                'wallet_amount' => $driver->wallet_amount - $amount
            ]);

            // Create transaction record for driver (debit)
            WalletTransaction::create([
                'user_id' => $driver->id,
                'amount' => -$amount,
                'type' => 'transfer_out',
                'note' => $request->note ?? 'Transfer to user: ' . $user->name
            ]);

            // Add to user wallet
            $user->update([
                'wallet_amount' => $user->wallet_amount + $amount
            ]);

            // Create transaction record for user (credit)
            WalletTransaction::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'type' => 'transfer_in',
                'note' => $request->note ?? 'Transfer from driver: ' . $driver->name
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transfer completed successfully',
                'data' => [
                    'transfer_amount' => $amount,
                    'driver_new_balance' => $driver->fresh()->wallet_amount,
                    'user_id' => $user->id,
                    'user_name' => $user->name
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Transfer failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get wallet balance and recent transactions
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWalletInfo(Request $request)
    {
        $user = $request->user();

        $transactions = WalletTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'balance' => $user->wallet_amount,
                'recent_transactions' => $transactions
            ]
        ]);
    }
}
