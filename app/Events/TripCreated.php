<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use App\Http\Resources\OrderResource;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Services\EligibleDriverService;

class TripCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $trip;
    public $user_service_id;
    public $eligibleDriverIds;

    public function __construct(Order $trip)
    {
        $this->trip = $trip;
        $this->user_service_id = $trip->user_service_id;
        
        // Get eligible driver IDs based on service, car model, and year matching
        $this->eligibleDriverIds = EligibleDriverService::getEligibleDriverIds($trip);
    }

    public function broadcastOn(): array
    {
        $channels = [];
        
        // Broadcast to each eligible driver's private channel
        foreach ($this->eligibleDriverIds as $driverId) {
            $channels[] = new PrivateChannel('driver.' . $driverId);
        }
        
        // Also broadcast to the general drivers channel for backwards compatibility
        $channels[] = new Channel('drivers');
        
        return $channels;
    }

    public function broadcastWith()
    {
        $this->trip->user_service_id = $this->user_service_id;
        return [
            'order' => (new OrderResource($this->trip))->toArray(request()),
            'eligible_driver_ids' => $this->eligibleDriverIds,
        ];
    }

    public function broadcastAs()
    {
        return 'drivers1';
    }
}

