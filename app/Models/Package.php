<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Package extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'driver_packages';

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'price_points' => 'integer',
        'price_cash' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
    ];

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
            ->width(100)
            ->height(100);
            
        $this->addMediaConversion('preview')
            ->width(300)
            ->height(300);
    }

    public function getPhotoAttribute()
    {
        $file = $this->getMedia('package_photo')->last();
        if ($file) {
            return $file->getUrl();
        }
        return asset('assets/media/svg/files/blank-image.svg');
    }
}
