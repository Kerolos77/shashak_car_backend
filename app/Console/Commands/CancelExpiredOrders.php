<?php

namespace App\Console\Commands;

use App\Events\TripStatusUpdated;
use App\Models\Order;
use App\Models\User;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CancelExpiredOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:cancel-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically cancel orders that haven\'t been assigned within 30 minutes';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Cancel orders created more than 30 minutes ago that are still in searching/negotiating/pending
        $expiredTime = Carbon::now()->subMinutes(30);

        // We target only searching, negotiating, and pending as requested
        $orders = Order::whereIn('status', [
            Order::STATUS_PENDING,
            Order::STATUS_SEARCHING,
            Order::STATUS_NEGOTIATING
        ])
        ->where('created_at', '<', $expiredTime)
        ->get();

        if ($orders->isEmpty()) {
            return 0;
        }

        foreach ($orders as $order) {
            try {
                // 1. Refund Escrow funds if any (similar to OrderApiController::cancelorder)
                if ($order->hasEscrowFunds()) {
                    $user = User::find($order->user_id);
                    $totalElectronicRefund = (float)($order->wallet_paid + $order->card_paid);

                    if ($totalElectronicRefund > 0 && $user) {
                        $user->update([
                            'wallet_amount' => $user->wallet_amount + $totalElectronicRefund
                        ]);
                        WalletTransaction::create([
                            'user_id' => $user->id,
                            'amount' => $totalElectronicRefund,
                            'type' => 'deposit',
                            'description' => 'Refund (Wallet+Card) for auto-canceled order #' . $order->id . ' (unassigned after 30 mins)'
                        ]);
                    }
                    
                    $order->update(['is_escrow' => false]);
                }

                // 2. Update order status to canceled
                $order->update([
                    'canceled_at' => Carbon::now(),
                    'status' => Order::STATUS_CANCELED,
                    'canceled_by' => null // System auto-cancel
                ]);

                // 3. Notify the user
                if ($order->user) {
                    $order->user->sendPushNotification(
                        "تم إلغاء الرحلة تلقائياً", 
                        "نعتذر، تم إلغاء طلبك لعدم توفر سائق خلال الوقت المحدد.", 
                        ['order_id' => $order->id, 'type' => 'trip_cancelled_auto']
                    );
                }

                // 4. Broadcast the status update
                TripStatusUpdated::dispatch($order->fresh());

                Log::info("Order #{$order->id} automatically canceled due to timeout (30 minutes unassigned).");

            } catch (\Exception $e) {
                Log::error("Error auto-canceling order {$order->id}: " . $e->getMessage());
            }
        }

        $count = $orders->count();
        $this->info("Processed {$count} expired orders.");
        
        return 0;
    }
}
