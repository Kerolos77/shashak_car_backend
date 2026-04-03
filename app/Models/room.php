<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class room extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function chat()
    {
        return $this->hasMany(Chat::class, 'room_id');
    }
    public function latest_message()
    {
        return $this->hasMany(Chat::class, 'room_id')->orderBy('id',  'DESC');
    }
    public function trip()
    {
        return $this->belongsTo(Order::class, 'trip_id');
    }
    
}
