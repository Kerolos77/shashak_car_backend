<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'rating' => 'integer',
    ];

    // العلاقة مع المستخدم اللي عمل التقييم
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    // العلاقة مع المستخدم اللي اتقيّم
    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    // العلاقة مع الطلب
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
