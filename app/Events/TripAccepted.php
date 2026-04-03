<?php

namespace App\Events;

use App\Models\Order;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TripAccepted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $trip,$status;


    public function __construct(Order $trip,$status)
    {
        $this->trip = $trip;

        $this->status = $status;
    }


    public function broadcastOn(): array
    {
        return [
            new Channel('trip-'.$this->trip->id)
        ];
    }
    public function broadcastWith()
    {
        return [
            'status' => $this->status['status'],
            'order' => (new \App\Http\Resources\OrderResource($this->trip))->resolve(),
        ];
    }
    public function broadcastAs()
    {
        return  'TripAccepted';
    }
}
