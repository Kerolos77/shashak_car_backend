<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserIdentity extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'ai_verification_report' => 'array',
        'ai_raw_response' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
