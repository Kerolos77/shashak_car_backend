<?php

namespace App\Events;

use App\Models\PaymentTransaction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PaymentStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $transaction;

    /**
     * Create a new event instance.
     *
     * @param PaymentTransaction $transaction
     */
    public function __construct(PaymentTransaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // Broadcast to the specific user's channel
        return new Channel('user-' . $this->transaction->userID);
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'transaction_id' => $this->transaction->id,
            'payment_id' => $this->transaction->payment_id,
            'amount' => $this->transaction->amount,
            'status' => $this->transaction->status,
            'success' => (bool) $this->transaction->success,
            'payment_method' => $this->transaction->payment_method,
            'created_at' => $this->transaction->created_at ? $this->transaction->created_at->toIso8601String() : null,
            'updated_at' => $this->transaction->updated_at ? $this->transaction->updated_at->toIso8601String() : null,
        ];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'payment.status_updated';
    }
}
