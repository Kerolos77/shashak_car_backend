<?php

namespace App\Events;

use App\Models\OrderOffer;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OfferStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $offer;
    public $action; // 'accepted' or 'denied'
    public $actorType; // 'user' or 'driver'
    public $actorId;

    /**
     * Create a new event instance.
     *
     * @param OrderOffer $offer
     * @param string $action 'accepted' or 'denied'
     * @param string $actorType Who performed the action
     * @param int $actorId ID of the actor
     */
    public function __construct(OrderOffer $offer, string $action, string $actorType, int $actorId)
    {
        $this->offer = $offer->load(['order', 'driver', 'user']);
        $this->action = $action;
        $this->actorType = $actorType;
        $this->actorId = $actorId;
    }

    public function broadcastOn(): array
    {
        // Broadcast to the order channel so both user and driver can listen
        return [
            new Channel('trip-offers-' . $this->offer->order_id)
        ];
    }

    public function broadcastWith()
    {
        return [
            'offer_id' => $this->offer->id,
            'order_id' => $this->offer->order_id,
            'driver_id' => $this->offer->driver_id,
            'user_id' => $this->offer->user_id,
            'status' => $this->offer->status,
            'action' => $this->action,
            'actor_type' => $this->actorType,
            'actor_id' => $this->actorId,
            'offer_rate' => $this->offer->offer_rate,
            'user_counter_offer' => $this->offer->user_counter_offer,
            'driver' => $this->offer->driver ? [
                'id' => $this->offer->driver->id,
                'name' => $this->offer->driver->name,
                'phone_number' => $this->offer->driver->phone_number,
            ] : null,
        ];
    }

    public function broadcastAs()
    {
        return 'offer.status_changed';
    }
}
