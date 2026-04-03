<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverCarsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" =>  $this->id,
            "driver_profile_id" =>  $this->driver_profile_id,
            "car_brand" =>  $this->brand,
            "car_model" =>  $this->model,
            "color" =>  $this->color,
            'car_number'=> $this->car_number,
            "release_year" =>  $this->release_year,
            "created_at" =>  $this->created_at,
        ];
    }
}
