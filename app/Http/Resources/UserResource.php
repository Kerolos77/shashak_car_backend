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
            'is_vip' => (bool)($this->is_vip ?? false),
            'vip_theme' => [
                'is_vip' => (bool)($this->is_vip ?? false),
                'badge_title' => ($this->is_vip ?? false) ? ($this->profile ? '⭐ كابتن VIP مميز' : '⭐ عميل VIP مميز') : null,
                'primary_color' => '#D4AF37',
                'gradient_colors' => ['#1A1A1A', '#D4AF37'],
                'show_gold_frame' => (bool)($this->is_vip ?? false),
            ],
            'active_package' => $this->active_purchase ? [
                'id' => $this->active_purchase->package->id,
                'name' => $this->active_purchase->package->name,
                'image' => $this->active_purchase->package->photo,
                'expires_at' => $this->active_purchase->expires_at->toDateTimeString(),
            ] : null,
            'national_id' => $this->national_id ?? '',
            'national_id_front' => $this->national_id_front ? (path($this->id, 'users') . $this->national_id_front) : '',
            'national_id_back' => $this->national_id_back ? (path($this->id, 'users') . $this->national_id_back) : '',
            'national_id_selfie' => $this->national_id_selfie ? (path($this->id, 'users') . $this->national_id_selfie) : '',
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
