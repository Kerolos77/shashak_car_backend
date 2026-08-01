<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'increase' => 'array',
        'percentage_increase' => 'array',
        'active_type' => 'string',
        'referral_bonus' => 'decimal:2',
        'commission_percentage' => 'decimal:2',
        'min_driver_wallet_for_shipping' => 'decimal:2',
        'play_store_url' => 'string',
        'app_store_url' => 'string',
        'max_cash_pickup_distance_km' => 'float',
        'max_card_pickup_distance_km' => 'float',
        'destination_mode_tolerance_km' => 'float',
        'auto_cash_ban_enabled' => 'boolean',
        'max_driver_cash_debt_limit' => 'decimal:2',
        'cash_restriction_duration_minutes' => 'integer',
        'max_consecutive_cancellations_before_ban' => 'integer',
        'min_driver_rating_for_cash' => 'decimal:2',
        'dispatch_priority_strategy' => 'string',
        'sms_enabled' => 'boolean',
        'shipping_enabled' => 'boolean',
        'ride_enabled' => 'boolean',
        'travel_enabled' => 'boolean',
        'intercity_enabled' => 'boolean',
    ];
}
