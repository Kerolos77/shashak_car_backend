<?php

namespace App\Http\Resources;

use App\Models\OrderOffer;
use Illuminate\Http\Request;
use App\Traits\MapsProcessing;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderWithDriverResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        if(isset($this->offers) && count($this->offers) > 0) {
            $offers = $this->offers->transform(function (OrderOffer $offer) {
                return (new OutCityOffersResource($offer));
            });
        } else {
            $offers = [];
        }




        $data =   [
            'id'                  => $this->id,
            'destination_lat'     => $this->destination_lat,
            'destination_long'    => $this->destination_long ?? '',
            'destination_address' => $this->destination_address ?? '',
            'source_lat'          => $this->source_lat ?? '',
            'source_long'         => $this->destination_long ?? '',
            'source_address'      => $this->source_address ?? '',
            'amount'              => $this->offer_rate ?? '',
            'final_rate'          => $this->final_rate ?? '',
            'is_offer'            => $this->service->offer_rate ?? '',
            'distance'            => $this->distance ?? '',
            'status'              => $this->status ?? '',
            'user_name'           => $this->user->full_name ?? '',
            'user_image'          => $this->user->profile_pic ?? '',
            'user_phone'          => $this->user->phone_number ?? '',
            'offer_rate'          => $this->service->offer_rate ?? '0',
            'offerdriver'         => $this->offerdriver ?? '',
            'created_at'          => $this->created_at ?? '',
            'offers'              => (isset($this->offers) && count($this->offers) > 0 ? new OutCityOffersCollection($offers) : []),
        ];
        $data['driver_id']           = $this->driver_id ?? '';
        $data['driver_name']         = $this->driver_name ?? '';
        $data['driver_phone']        = $this->driver_phone ?? '';
        $data['driver_image']        = $this->user->profile_pic ?? '';
        $data['car_color']           = $this->car_color ?? '';
        $data['car_number']          = $this->car_number ?? '';
        $data['car_brand']           = $this->car_brand ?? '';
        $data['car_model']           = $this->car_model ?? '';
        return $data;
    }
}
