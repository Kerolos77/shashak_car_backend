<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverCarLicense extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function car_model() 
    {
        return $this->belongsTo(CarModel::class);    
    }
    public function car_brand() 
    {
        return $this->belongsTo(CarBrand::class);
    }
    public function driver_profile() 
    {
        return $this->belongsTo(DriverProfile::class);
    }
}
