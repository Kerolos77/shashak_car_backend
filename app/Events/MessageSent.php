<?php

namespace App\Events;

use App\Http\Resources\ChatResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSent implements ShouldBroadcast
{
    public $message;
    private $trip_id;
    public function __construct($message, $trip_id)
    {
        $this->message = $message;
        $this->trip_id = $trip_id;

    }

    public function broadcastOn()
    {
        return [
            new Channel('trip-' . $this->trip_id)
        ];
    }
    public function broadcastWith()
    {
        // return  $this->message;
        return (new ChatResource($this->message))->toArray(request());
    }
    public function broadcastAs()
    {
        return  'chat';
    }
}
