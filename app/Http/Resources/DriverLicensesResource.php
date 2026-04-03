<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverLicensesResource extends JsonResource
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
            "front_license_image" => url('files/DriverLicense/'.$this->driver_profile->user_id.'/'. $this->front_license_image),
            "back_license_image" => url('files/DriverLicense/'.$this->driver_profile->user_id.'/'. $this->back_license_image),
            "driver_with_license_image" => url('files/DriverLicense/'.$this->driver_profile->user_id.'/'. $this->driver_with_license_image),
            "expiry_date" => $this->expiry_date,

        ];
    }
}
