<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverCar extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function brand()
    {
        return $this->belongsTo(CarBrand::class,'car_brand_id');
    }
    public function model()
    {
        return $this->belongsTo(CarModel::class,'car_model_id');
    }
    public function driver_profile() 
    {
        return $this->belongsTo(DriverProfile::class);
    }
}
