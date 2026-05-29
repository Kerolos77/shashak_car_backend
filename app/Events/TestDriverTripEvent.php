<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use App\Http\Resources\OrderResource;
use App\Services\EligibleDriverService;

class TestDriverTripEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $trip_id;
    public $driver_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($trip_id, $driver_id)
    {
        $this->trip_id = $trip_id;
        $this->driver_id = $driver_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('driver.' . $this->driver_id);
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'TripCreated';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        // Try to load the real trip from database
        $trip = Order::with(['driver', 'user', 'service'])->find($this->trip_id);
        
        if (!$trip) {
            // Try to get the first order as fallback to ensure a valid OrderResource output
            $trip = Order::with(['driver', 'user', 'service'])->first();
            if ($trip) {
                // Dynamically override ID to match the test trip_id
                $trip->id = $this->trip_id;
            }
        }

        if ($trip) {
            $eligibleDriverIds = EligibleDriverService::getEligibleDriverIds($trip);
            if (!in_array((int)$this->driver_id, $eligibleDriverIds)) {
                $eligibleDriverIds[] = (int)$this->driver_id;
            }

            return [
                'order' => (new OrderResource($trip))->resolve(),
                'eligible_driver_ids' => $eligibleDriverIds,
            ];
        }

        // Complete fallback if the database has absolutely no orders
        return [
            'order' => [
                'id' => $this->trip_id,
                'destination_lat' => '30.0444',
                'destination_long' => '31.2357',
                'destination_address' => 'Cairo Airport',
                'source_lat' => '30.0130',
                'source_long' => '31.2082',
                'source_address' => 'Tahrir Square, Cairo',
                'amount' => '150',
                'final_rate' => '150',
                'distance' => '18.5',
                'distance_type' => 'km',
                'status' => 'searching',
                'offerdriver' => '',
                'is_offer' => '',
                'created_at' => now()->toIso8601String(),
                'driver' => '',
                'user' => [
                    'id' => 1,
                    'name' => 'Test User',
                    'phone' => '+201001234567',
                ],
                'when_date' => '',
                'inter_city' => 0,
                'user_service_id' => 1,
                'paid' => 0,
                'payment_type' => 'cash',
                'commission' => '0',
                'destination_City' => '',
                'source_city' => '',
                'parcel_dimension' => '',
                'parcel_image' => '',
                'parcel_weight' => '',
                'number_of_passenger' => '',
                'is_placed' => '',
                'is_started' => '',
                'is_accept' => '',
                'is_complete' => '',
                'is_canceled' => '',
                'assigned_at' => '',
                'arrived_at' => '',
                'on_trip_at' => '',
                'completed_at' => '',
                'canceled_at' => '',
                'canceled_by' => '',
                'comment' => '',
                'service_type' => 'taxi',
                'reviews_count' => 0,
                'has_review' => false,
                'payment_details' => [
                    'type' => 'cash',
                    'total_amount' => 150.0,
                    'wallet_paid' => 0.0,
                    'card_paid' => 0.0,
                    'cash_paid' => 150.0,
                    'is_escrow' => false,
                    'payment_status' => 'pending',
                ],
                'offers' => [],
            ],
            'eligible_driver_ids' => [(int)$this->driver_id]
        ];
    }
}
