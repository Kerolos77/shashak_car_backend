<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OutCityOffersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'driver_offer' => $this->offer_rate,
            'car_color' => $this->car_color,
            'car_number' => $this->car_number,
            'car_brand' => $this->car_brand,
            'car_model' => $this->car_model,
            'driver_name' => $this->driver->full_name,
            'driver_image' => $this->driver->profile_pic ?? '',
            'phone_number' => $this->driver->phone_number ?? '',
            'sender_type' => $this->sender_type ?? 'driver',
            'status' => $this->status ?? 'pending',
            'user_counter_offer' => $this->user_counter_offer ?? '',
        ];
    }
}
