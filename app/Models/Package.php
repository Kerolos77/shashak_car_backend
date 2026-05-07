<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $table = 'driver_packages';

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'price_points' => 'integer',
        'price_cash' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
    ];
}
