<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Traits\MapsProcessing;
use Illuminate\Http\Resources\Json\JsonResource;

class ServicesResource extends JsonResource
{


    public function toArray(Request $request): array
    {

        return [
            'id'            => $this->id,
            'name'          => $this->title,
'image' => isset($this->thumbnail[0]) ? $this->thumbnail[0]['url'] : null,
            'offer_rate'    => $this->offer_rate,
            'service_type'  => $this->service_type,
            'vehicle_type'  => $this->vehicle_type ?? 'car',
            'weight'        => $this->weight,
            'height'        => $this->height,
            'width'         => $this->width,
            'length'        => $this->length,
        ];
    }
}
