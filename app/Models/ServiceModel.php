<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceModel extends Model
{
    use HasFactory;

    protected $fillable = ['service_id', 'model_name', 'min_year'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
