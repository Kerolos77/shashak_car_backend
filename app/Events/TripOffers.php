<?php

namespace App\Events;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Broadcasting\Channel;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use App\Http\Resources\OrderWithDriverResource;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Eloquent\Model;

class TripOffers implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $trip;
    public function __construct(Order $trip)
    {
        $this->trip = $trip;
    }
    public function broadcastOn(): array
    {
        return [
            new Channel('trip-offers-'.$this->trip->id)
        ];
    }
    public function broadcastWith()
    {
        // Reload trip with all necessary relations to ensure data is fresh (especially if queued)
        $freshTrip = $this->trip->fresh([
            'offers', 
            'user', 
            'service', 
            'driver.profile.driver_cars.brand', 
            'driver.profile.driver_cars.model', 
            'driver.profile.car_licenses'
        ]);

        if ($freshTrip) {
            $freshTrip->offerdriver = $this->trip->offerdriver;
        }
        
        return (new OrderWithDriverResource($freshTrip ?? $this->trip))->toArray(request());
    }
    public function broadcastAs()
    {
        return  'offer';
    }
}
