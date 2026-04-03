<?php

namespace App\Events;

use App\Models\OrderOffer;
use App\Http\Resources\OrderOfferResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OfferSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $offer;
    public $targetType;
    public $targetId;

    /**
     * Create a new event instance.
     *
     * @param OrderOffer $offer
     * @param string $targetType 'user' or 'driver'
     * @param int $targetId User ID or Driver ID to notify
     */
    public function __construct(OrderOffer $offer, string $targetType, int $targetId)
    {
        $this->offer = $offer->load(['order', 'driver', 'user']);
        $this->targetType = $targetType;
        $this->targetId = $targetId;
    }

    public function broadcastOn(): array
    {
        // Broadcast to specific user or driver channel
        return [
            new Channel($this->targetType . '-' . $this->targetId)
        ];
    }

    public function broadcastWith()
    {
        return [
            'offer_id' => $this->offer->id,
            'order_id' => $this->offer->order_id,
            'driver_id' => $this->offer->driver_id,
            'user_id' => $this->offer->user_id,
            'sender_type' => $this->offer->sender_type,
            'offer_rate' => $this->offer->offer_rate,
            'user_counter_offer' => $this->offer->user_counter_offer,
            'status' => $this->offer->status,
            'car_color' => $this->offer->car_color,
            'car_number' => $this->offer->car_number,
            'car_brand' => $this->offer->car_brand,
            'car_model' => $this->offer->car_model,
            'driver' => $this->offer->driver ? [
                'id' => $this->offer->driver->id,
                'name' => $this->offer->driver->name,
                'phone_number' => $this->offer->driver->phone_number,
                'profile_pic' => $this->offer->driver->imageurl,
            ] : null,
            'order' => $this->offer->order ? [
                'id' => $this->offer->order->id,
                'source_address' => $this->offer->order->source_address,
                'destination_address' => $this->offer->order->destination_address,
            ] : null,
            'created_at' => $this->offer->created_at,
        ];
    }

    public function broadcastAs()
    {
        return 'offer.sent';
    }
}
