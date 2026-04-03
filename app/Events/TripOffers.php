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
    public function __construct( $trip)
    {
        $trip = $trip->toArray();
        $this->trip = $trip;


    }
    public function broadcastOn(): array
    {
        return [
            new Channel('trip-'.$this->trip['id'])
        ];
    }
    public function broadcastWith()
    {

        return (new OrderWithDriverResource((object )$this->trip))->toArray(request());
        // return (new OrderWithDriverResource($this->trip))->toArray(request());
    }
    public function broadcastAs()
    {
        return  'offer';
    }
}
