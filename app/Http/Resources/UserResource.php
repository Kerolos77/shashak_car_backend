<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id'            => $this->id,
            'name'          => $this->name,
            'phone'         => $this->phone_number,
            'image'         => $this->imageurl??'',
            'country_id'    => $this->country_id??'',
            'city'          => $this->city_id??'',
            'email'         => $this->email ?? '',
            'wallet_amount' => number_format($this->wallet_amount,2) ?? '',
            'pending_wallet'=> $this->pending_wallet ?? 0,
            'driver_status' => $this->profile->status  ?? '',
            'is_driver'     => $this->profile != null  ?1:0 ,
            'is_online'     => $this->is_online  ,
            'service_id'    => $this->profile != null ? $this->profile->service_id : 0 ,
            'reward_points' => $this->points ?? 0,
            'cash_restriction_seconds_remaining' => $this->cash_restriction_seconds_remaining ?? 0,
            'active_package' => $this->active_package ? [
                'id' => $this->active_package->id,
                'name' => $this->active_package->name,
                'image' => $this->active_package->photo,
                'expires_at' => $this->purchases()->where('package_id', $this->active_package->id)->where('expires_at', '>', now())->latest()->value('expires_at'),
            ] : null,

        ];

        if ($this->token) {
            $data['token'] = $this->token;
        }

        // if ($this->credit) {
        //     $data['creadit'] = $this->creadit;
        // }

        return $data;
    }
}
