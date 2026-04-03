<?php

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, HasAdvancedFilter, SoftDeletes;

    public $table = 'orders';
    protected $guarded = ['id'];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_SEARCHING = 'searching';
    const STATUS_NEGOTIATING = 'negotiating';
    const STATUS_USER_ACCEPT_OFFER = 'user_accept_offer';
    const STATUS_PAYMENT_PENDING = 'payment_pending';
    const STATUS_PAYMENT_PAID = 'payment_paid';
    const STATUS_PAYMENT_FAILED = 'payment_failed';
    const STATUS_PAYMENT_UPDATED = 'payment_updated';
    const STATUS_ASSIGNED = 'assigned';
    const STATUS_DRIVER_ON_A_WAY = 'driver_on_a_way';
    const STATUS_ARRIVED = 'arrived';
    const STATUS_ON_TRIP = 'on_trip';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELED = 'canceled';
    const STATUS_PAYMENT_REQUIRED = 'payment_required';

    // Payment status constants
    const PAYMENT_PENDING = 'pending';  // default — cash or not yet charged
    const PAYMENT_PAID = 'paid';     // card charged successfully
    const PAYMENT_FAILED = 'failed';   // card charge failed

    protected $casts = [
        'payment_status' => 'string',  // pending | paid | failed
        'is_female_only' => 'boolean',
        'is_escrow' => 'boolean',
        'wallet_paid' => 'decimal:2',
        'card_paid' => 'decimal:2',
        'cash_paid' => 'decimal:2',
        'assigned_at' => 'datetime',
        'arrived_at' => 'datetime',
        'on_trip_at' => 'datetime',
        'completed_at' => 'datetime',
        'canceled_at' => 'datetime',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function offers()
    {
        return $this->hasMany(OrderOffer::class);
    }
    public function service()
    {
        return $this->belongsTo(\App\Models\Service::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
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

    // Helper methods for status
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }
    public function isNegotiating()
    {
        return $this->status === self::STATUS_NEGOTIATING;
    }
    public function isUserAcceptOffer()
    {
        return $this->status === self::STATUS_USER_ACCEPT_OFFER;
    }
    public function isPaymentPending()
    {
        return $this->status === self::STATUS_PAYMENT_PENDING;
    }
    public function isPaymentPaid()
    {
        return $this->status === self::STATUS_PAYMENT_PAID;
    }
    public function isPaymentFailed()
    {
        return $this->status === self::STATUS_PAYMENT_FAILED;
    }
    public function isPaymentUpdated()
    {
        return $this->status === self::STATUS_PAYMENT_UPDATED;
    }
    public function isAssigned()
    {
        return $this->status === self::STATUS_ASSIGNED;
    }
    public function isDriverOnAWay()
    {
        return $this->status === self::STATUS_DRIVER_ON_A_WAY;
    }
    public function isArrived()
    {
        return $this->status === self::STATUS_ARRIVED;
    }
    public function isOnTrip()
    {
        return $this->status === self::STATUS_ON_TRIP;
    }
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }
    public function isCanceled()
    {
        return $this->status === self::STATUS_CANCELED;
    }

    public function isPaid(): bool
    {
        return $this->payment_status === self::PAYMENT_PAID;
    }

    public function needsPayment(): bool
    {
        return in_array($this->payment_type, ['cash', 'wallet', 'card', 'saved_card', 'wallet_card', 'wallet_cash'])
            && $this->payment_status !== self::PAYMENT_PAID;
    }

    public function hasEscrowFunds(): bool
    {
        return $this->is_escrow && ($this->wallet_paid > 0 || $this->card_paid > 0);
    }

    public function isPaymentRequired()
    {
        return $this->status === self::STATUS_PAYMENT_REQUIRED;
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
    public function scopeNegotiating($query)
    {
        return $query->where('status', self::STATUS_NEGOTIATING);
    }
    public function scopeAssigned($query)
    {
        return $query->where('status', self::STATUS_ASSIGNED);
    }
    public function scopeArrived($query)
    {
        return $query->where('status', self::STATUS_ARRIVED);
    }
    public function scopeOnTrip($query)
    {
        return $query->where('status', self::STATUS_ON_TRIP);
    }
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }
    public function scopeCanceled($query)
    {
        return $query->where('status', self::STATUS_CANCELED);
    }

    public function scopePaymentRequired($query)
    {
        return $query->where('status', self::STATUS_PAYMENT_REQUIRED);
    }
    public function scopeUserAcceptOffer($query)
    {
        return $query->where('status', self::STATUS_USER_ACCEPT_OFFER);
    }
    public function scopePaymentPending($query)
    {
        return $query->where('status', self::STATUS_PAYMENT_PENDING);
    }
    public function scopePaymentPaid($query)
    {
        return $query->where('status', self::STATUS_PAYMENT_PAID);
    }
    public function scopePaymentFailed($query)
    {
        return $query->where('status', self::STATUS_PAYMENT_FAILED);
    }
    public function scopeDriverOnAWay($query)
    {
        return $query->where('status', self::STATUS_DRIVER_ON_A_WAY);
    }
}
