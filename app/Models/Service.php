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

    public const COMISSION_TYPE = [
        'flex'       => 'flex',
        'percentage' => 'percentage',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'enable'            => 'boolean',
        'price_tiers'       => 'array',
        'tier_pricing_type' => 'string',
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
        'km_charge',
        'price_tiers',
        'tier_pricing_type',
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

    /**
     * Calculate trip price based on distance (Km) and defined price tiers.
     * 
     * @param float|int $distanceKm
     * @return float
     */
    public function calculatePrice($distanceKm)
    {
        $distanceKm = floatval($distanceKm);
        $tiers = $this->price_tiers;

        if (!empty($tiers) && is_array($tiers) && count($tiers) > 0) {
            // Filter and sort tiers by from_km ascending
            $sortedTiers = collect($tiers)->filter(function ($tier) {
                return isset($tier['price_per_km']) && $tier['price_per_km'] !== '';
            })->sortBy(function ($tier) {
                return floatval($tier['from_km'] ?? 0);
            })->values()->all();

            if (count($sortedTiers) > 0) {
                $type = $this->tier_pricing_type ?? 'flat';

                if ($type === 'cumulative') {
                    // Cumulative / Bracketed Tier Calculation (حساب تراكمي بالشرائح)
                    $totalPrice = 0;
                    foreach ($sortedTiers as $tier) {
                        $from = floatval($tier['from_km'] ?? 0);
                        $to = (isset($tier['to_km']) && $tier['to_km'] !== '' && $tier['to_km'] !== null) ? floatval($tier['to_km']) : null;
                        $rate = floatval($tier['price_per_km']);

                        if ($distanceKm > $from) {
                            $segmentKm = ($to !== null) ? min($distanceKm, $to) - $from : $distanceKm - $from;
                            if ($segmentKm > 0) {
                                $totalPrice += $segmentKm * $rate;
                            }
                        }
                    }
                    return max(0, $totalPrice);
                } else {
                    // Flat Rate Tier Lookup (سعر الشريحة المطبقة على كامل المسافة)
                    foreach ($sortedTiers as $tier) {
                        $from = floatval($tier['from_km'] ?? 0);
                        $to = (isset($tier['to_km']) && $tier['to_km'] !== '' && $tier['to_km'] !== null) ? floatval($tier['to_km']) : null;
                        $rate = floatval($tier['price_per_km']);

                        if ($distanceKm >= $from && ($to === null || $distanceKm <= $to)) {
                            return $distanceKm * $rate;
                        }
                    }

                    // Fallback to last tier rate if distance exceeds all defined upper bounds
                    $lastTier = end($sortedTiers);
                    return $distanceKm * floatval($lastTier['price_per_km']);
                }
            }
        }

        // Fallback to default km_charge if no valid tiers exist
        return $distanceKm * floatval($this->km_charge ?? 0);
    }

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

    public function ScopeType($q, $value)
    {
        return $q->where('intercity_type', $value);
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

        $this->addMediaConversion('preview_thumbnail')
            ->width($thumbnailPreviewWidth)
            ->height($thumbnailPreviewHeight);
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
