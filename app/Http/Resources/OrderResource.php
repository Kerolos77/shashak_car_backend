<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Traits\MapsProcessing;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{


    public function toArray(Request $request): array
    {
        if (!$this->resource) {
            return [];
        }

        $data = [
            'id' => $this->id,
            'destination_lat' => $this->destination_lat,
            'destination_long' => $this->destination_long ?? '',
            'destination_address' => $this->destination_address ?? '',
            'source_lat' => $this->source_lat ?? '',
            'source_long' => $this->source_long ?? '',
            'source_address' => $this->source_address ?? '',
            'amount' => $this->offer_rate ?? '',
            'final_rate' => $this->final_rate ?? '',
            'distance' => $this->distance ?? '',
            'distance_type' => $this->distance_type ?? '',
            'status' => $this->status,
            'offerdriver' => $this->offerdriver ?? '',
            'is_offer' => $this->service->offer_rate ?? '',
            'created_at' => $this->created_at ?? '',
            'driver' => ($this->driver != null ? new UserResource((object) $this->driver) : ''),
            'user' => ($this->user != null ? new UserResource((object) $this->user) : ''),
            'when_date' => $this->when_date ?? '',
            'inter_city' => $this->inter_city ?? '',
            'user_service_id' => $this->user_service_id ?? '',
            'paid' => $this->paid ?? '',
            'payment_type' => $this->payment_type ?? '',
            'commission' => $this->commission ?? '',
            'destination_City' => $this->destination_City ?? '',
            'source_city' => $this->source_city ?? '',
            'parcel_dimension' => $this->parcel_dimension ?? '',
            'parcel_image' => $this->parcel_image ?? '',
            'parcel_weight' => $this->parcel_weight ?? '',
            'number_of_passenger' => $this->number_of_passenger ?? '',
            'is_placed' => $this->is_placed ?? '',
            'is_started' => $this->is_started ?? '',
            'is_accept' => $this->is_accept ?? '',
            'is_complete' => $this->is_complete ?? '',
            'is_canceled' => $this->is_canceled ?? '',
            'assigned_at' => $this->assigned_at ?? '',
            'arrived_at' => $this->arrived_at ?? '',
            'on_trip_at' => $this->on_trip_at ?? '',
            'completed_at' => $this->completed_at ?? '',
            'canceled_at' => $this->canceled_at ?? '',
            'canceled_by' => $this->canceled_by ?? '',
            'comment' => $this->comment ?? '',
            'service_type' => $this->service != null ? $this->service->service_type : '',
            'reviews_count' => $this->reviews ? $this->reviews->count() : 0,
            'has_review' => $this->reviews ? $this->reviews->count() > 0 : false,
            'payment_details' => [
                'type' => $this->payment_type,
                'total_amount' => (float) $this->offer_rate,
                'wallet_paid' => (float) $this->wallet_paid,
                'card_paid' => (float) $this->card_paid,
                'cash_paid' => (float) $this->cash_paid,
                'is_escrow' => (bool) $this->is_escrow,
                'payment_status' => $this->payment_status,
            ],
            'offers' => $this->offers ? new OutCityOffersCollection($this->offers) : [],
            'is_shipping_order' => $this->is_shipping_order ? 1 : 0,
            'pickup_otp' => (auth()->id() == $this->user_id) ? ($this->pickup_otp ?? '') : '',
            'delivery_otp' => (auth()->id() == $this->user_id || (auth()->user() && auth()->user()->phone_number === $this->receiver_phone)) ? ($this->delivery_otp ?? '') : '',
            'receiver_name' => $this->receiver_name ?? '',
            'receiver_phone' => $this->receiver_phone ?? '',
            'receiver_verification_otp' => (auth()->id() == $this->user_id || (auth()->user() && auth()->user()->phone_number === $this->receiver_phone)) ? ($this->receiver_verification_otp ?? '') : '',
            'is_receiver_verified' => $this->is_receiver_verified ? 1 : 0,
            'driver_arrived_at_sender_at' => $this->driver_arrived_at_sender_at ? $this->driver_arrived_at_sender_at->toDateTimeString() : '',
            'sender_confirmed_handover_at' => $this->sender_confirmed_handover_at ? $this->sender_confirmed_handover_at->toDateTimeString() : '',
            'driver_confirmed_pickup_at' => $this->driver_confirmed_pickup_at ? $this->driver_confirmed_pickup_at->toDateTimeString() : '',
            'driver_confirmed_cash_at' => $this->driver_confirmed_cash_at ? $this->driver_confirmed_cash_at->toDateTimeString() : '',
            'driver_arrived_at_receiver_at' => $this->driver_arrived_at_receiver_at ? $this->driver_arrived_at_receiver_at->toDateTimeString() : '',
            'driver_confirmed_delivery_at' => $this->driver_confirmed_delivery_at ? $this->driver_confirmed_delivery_at->toDateTimeString() : '',
            'receiver_confirmed_delivery_at' => $this->receiver_confirmed_delivery_at ? $this->receiver_confirmed_delivery_at->toDateTimeString() : '',
        ];

        return $data;
    }
}
