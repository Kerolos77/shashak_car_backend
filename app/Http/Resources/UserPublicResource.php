<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPublicResource extends JsonResource
{
    /**
     * Public user details safe to share in order responses.
     * Excludes private financial data (wallet_amount), emails, and national IDs.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if (!$this->resource) {
            return [];
        }

        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'phone'         => $this->phone_number,
            'image'         => $this->imageurl ?? '',
            'country_id'    => $this->country_id ?? '',
            'city'          => $this->city_id ?? '',
            'driver_status' => $this->profile->status ?? '',
            'is_driver'     => $this->profile != null ? 1 : 0,
            'is_online'     => (int) ($this->is_online ?? 0),
            'service_id'    => $this->profile != null ? $this->profile->service_id : 0,
            'cash_restriction_seconds_remaining' => 0,
            'is_vip'         => (bool) ($this->is_vip ?? false),
            'vip_theme'     => [
                'is_vip'          => (bool) ($this->is_vip ?? false),
                'badge_title'     => ($this->is_vip ?? false) ? ($this->profile ? '⭐ كابتن VIP مميز' : '⭐ عميل VIP مميز') : null,
                'primary_color'   => '#D4AF37',
                'gradient_colors' => ['#1A1A1A', '#D4AF37'],
                'show_gold_frame' => (bool) ($this->is_vip ?? false),
            ],
            'active_package' => $this->active_purchase ? [
                'id'         => $this->active_purchase->package->id,
                'name'       => $this->active_purchase->package->name,
                'image'      => $this->active_purchase->package->photo,
                'expires_at' => $this->active_purchase->expires_at->toDateTimeString(),
            ] : null,
        ];
    }
}
