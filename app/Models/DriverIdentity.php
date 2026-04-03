<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverIdentity extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function driver_profile()
    {
        return $this->belongsTo(DriverProfile::class);
    }
}
