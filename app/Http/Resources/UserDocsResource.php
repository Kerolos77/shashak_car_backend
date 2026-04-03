<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserDocsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'criminal_record_image'=> $this->profile->criminal_record_image != null ? url('files/DriverLicense/'.$this->id.'/'. $this->profile->criminal_record_image) : '',
            'driver_cars'=> new DriverCarsResource($this->profile->driver_cars),
            'car_licenses'=> new CarLicensesResource($this->profile->car_licenses),
            'identity'=> new IdentityResource($this->profile->identity),
            'driver_licenses'=> new DriverLicensesResource($this->profile->driver_licenses),
            'service_id'=> $this->profile->service_id
        ];
    }
}
