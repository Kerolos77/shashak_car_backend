<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IdentityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "id_number" => $this->id_number,
            "front_identity_image" => url('files/DriverLicense/'.$this->driver_profile->user_id.'/'. $this->front_identity_image),
            "back_identity_image" => url('files/DriverLicense/'.$this->driver_profile->user_id.'/'. $this->back_identity_image),
            "driver_image_with_id" => url('files/DriverLicense/'.$this->driver_profile->user_id.'/'. $this->driver_image_with_id),
            "expiry_date" => $this->expiry_date,
            "status" => $this->status,
            "driver_profile_id" => $this->driver_profile->id,
            "created_at" => $this->id,

        ];
    }
}
