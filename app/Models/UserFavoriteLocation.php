<?php

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserFavoriteLocation extends Model
{
    use HasFactory, HasAdvancedFilter, SoftDeletes;

    public $table = 'user_favorite_locations';

    protected $guarded = ['id'];

    public $orderable = [
        'id',
        'label',
        'address',
        'created_at',
    ];

    public $filterable = [
        'id',
        'user_id',
        'label',
        'address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
