<?php

namespace App\Events;

use App\Models\OrderOffer;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OfferUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $offer;
    public $actorType;  // 'driver' | 'user' | 'system'
    public $actorId;

    /**
     * @param OrderOffer $offer     The updated offer
     * @param string     $actorType Who triggered the change: 'driver' | 'user' | 'system'
     * @param int|null   $actorId   ID of the actor
     */
    public function __construct(OrderOffer $offer, string $actorType = 'system', ?int $actorId = null)
    {
        $this->offer = $offer->loadMissing(['order', 'driver', 'user']);
        $this->actorType = $actorType;
        $this->actorId = $actorId;
    }

    /**
     * Dedicated channel per offer — same pattern as trip-{order_id}
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('offer-' . $this->offer->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'OfferUpdated';
    }

    public function broadcastWith(): array
    {
        return [
            // ─── Offer identity ───────────────────────────────────────
            'offer_id' => $this->offer->id,
            'order_id' => $this->offer->order_id,

            // ─── Current state ────────────────────────────────────────
            'status' => $this->offer->status,
            // status values:
            //   pending   → offer sent, waiting for response
            //   countered → receiver replied with a different price
            //   accepted  → one side accepted
            //   denied    → one side denied

            // ─── Who made this change ─────────────────────────────────
            'actor_type' => $this->actorType,   // 'driver' | 'user'
            'actor_id' => $this->actorId,

            // ─── Who sent the original offer ──────────────────────────
            'sender_type' => $this->offer->sender_type, // 'driver' | 'user'

            // ─── Prices ───────────────────────────────────────────────
            'offer_rate' => $this->offer->offer_rate,
            'user_counter_offer' => $this->offer->user_counter_offer,

            // ─── Car info ─────────────────────────────────────────────
            'car_color' => $this->offer->car_color,
            'car_number' => $this->offer->car_number,
            'car_brand' => $this->offer->car_brand,
            'car_model' => $this->offer->car_model,

            // ─── Driver snapshot ──────────────────────────────────────
            'driver' => $this->offer->driver ? [
                'id' => $this->offer->driver->id,
                'name' => $this->offer->driver->name,
                'phone_number' => $this->offer->driver->phone_number,
                'profile_pic' => $this->offer->driver->imageurl ?? null,
            ] : null,

            // ─── Order snapshot ───────────────────────────────────────
            'order' => $this->offer->order ? [
                'id' => $this->offer->order->id,
                'source_address' => $this->offer->order->source_address,
                'destination_address' => $this->offer->order->destination_address,
                'status' => $this->offer->order->status,
            ] : null,

            'updated_at' => $this->offer->updated_at,
        ];
    }
}
