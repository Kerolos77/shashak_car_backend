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
        'commission_percentage' => 'decimal:2'
    ];
}
