<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use App\Http\Resources\OrderResource;

class TripStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    /**
     * Create a new event instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [
            new Channel('trip-' . $this->order->id),
        ];

        // Broadcast to each eligible driver's specific channel
        if ($this->order->status === Order::STATUS_PENDING) {
            $eligibleDriverIds = \App\Services\EligibleDriverService::getEligibleDriverIds($this->order);
            foreach ($eligibleDriverIds as $driverId) {
                if ($this->order->is_shipping_order) {
                    $channels[] = new PrivateChannel('driver-shipping.' . $driverId);
                } else {
                    $channels[] = new PrivateChannel('driver.' . $driverId);
                }
            }
        }

        return $channels;
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'status' => $this->order->status,
            'order' => (new OrderResource($this->order))->resolve(),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'TripStatusUpdated';
    }
}
