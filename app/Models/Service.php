<?php

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
class Service extends Model implements HasMedia
{
    use HasFactory, HasAdvancedFilter, SoftDeletes, InteractsWithMedia;

    public function models()
    {
        return $this->hasMany(ServiceModel::class);
    }


    public $table = 'services';
    public const COMISSION_TYPE= [
        'flex'       => 'flex',
        'percentage' => 'percentage',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'enable'         => 'boolean',
        'intercity_type' => 'boolean',
    ];

    public $filterable = [
        'id',
        'admin_commission',
        'image',
        'km_charge',
        'offer_rate',
        'title',
        'weight',
        'height',
        'width',
        'length',
    ];

    protected $fillable = [
        'admin_commission',
        'enable',
        'image',
        'intercity_type',
        'km_charge',
        'offer_rate',
        'title',
        'service_type',
        'commission_type',
        'weight',
        'height',
        'width',
        'length',
    ];

    public $orderable = [
        'id',
        'admin_commission',
        'enable',
        'image',
        'intercity_type',
        'km_charge',
        'offer_rate',
        'title',
        'weight',
        'height',
        'width',
        'length',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('project.datetime_format')) : null;
    }

    public function getUpdatedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('project.datetime_format')) : null;
    }

    public function getDeletedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('project.datetime_format')) : null;
    }
    public function ScopeType($q,$value)
    {
        return $q->where('intercity_type',$value);
    }
    public function scopeServiceType($q, $value)
    {
        return $q->where('service_type', $value);
    }
    public function registerMediaConversions(?Media $media = null): void
    {
        $thumbnailWidth  = 50;
        $thumbnailHeight = 50;

        $thumbnailPreviewWidth  = 120;
        $thumbnailPreviewHeight = 120;

        $this->addMediaConversion('thumbnail')
            ->width($thumbnailWidth)
            ->height($thumbnailHeight);
            //->fit('crop', $thumbnailWidth, $thumbnailHeight);
        $this->addMediaConversion('preview_thumbnail')
            ->width($thumbnailPreviewWidth)
            ->height($thumbnailPreviewHeight);
            //->fit('crop', $thumbnailPreviewWidth, $thumbnailPreviewHeight);
    }
    public function getThumbnailAttribute()
    {
        return $this->getMedia('service_images')->map(function ($item) {
            $media                      = $item->toArray();
            $media['url']               = $item->getUrl();
            $media['thumbnail']         = $item->getUrl('thumbnail');
            $media['preview_thumbnail'] = $item->getUrl('preview_thumbnail');

            return $media;
        });
    }

    public function getImagesAttribute()
    {
        return $this->getMedia('service_images')->map(function ($item) {
            $media                      = $item->toArray();
            $media['url']               = $item->getUrl();
            $media['thumbnail']         = $item->getUrl('thumbnail');
            $media['preview_thumbnail'] = $item->getUrl('preview_thumbnail');

            return $media;
        });
    }

}
