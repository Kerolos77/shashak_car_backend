<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderOffer extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_COUNTERED = 'countered';
    const STATUS_USER_ACCEPTED = 'user_accepted';
    const STATUS_DRIVER_ACCEPTED = 'driver_accepted';
    const STATUS_USER_DENIED = 'user_denied';
    const STATUS_DRIVER_CANCELED = 'driver_canceled';

    // Legacy aliases (kept for backward compat)
    const STATUS_ACCEPTED = 'driver_accepted'; // old generic 'accepted' maps to driver
    const STATUS_DENIED = 'user_denied';     // old generic 'denied' maps to user

    // Sender type constants
    const SENDER_DRIVER = 'driver';
    const SENDER_USER = 'user';

    protected $casts = [
        'offer_rate' => 'decimal:3',
        'user_counter_offer' => 'decimal:3',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeCountered($query)
    {
        return $query->where('status', self::STATUS_COUNTERED);
    }

    public function scopeAccepted($query)
    {
        return $query->whereIn('status', [self::STATUS_USER_ACCEPTED, self::STATUS_DRIVER_ACCEPTED]);
    }

    public function scopeDenied($query)
    {
        return $query->whereIn('status', [self::STATUS_USER_DENIED, self::STATUS_DRIVER_CANCELED]);
    }

    public function scopeByDriver($query)
    {
        return $query->where('sender_type', self::SENDER_DRIVER);
    }

    public function scopeByUser($query)
    {
        return $query->where('sender_type', self::SENDER_USER);
    }

    // Helper methods
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isCountered()
    {
        return $this->status === self::STATUS_COUNTERED;
    }

    public function isAccepted(): bool
    {
        return in_array($this->status, [self::STATUS_USER_ACCEPTED, self::STATUS_DRIVER_ACCEPTED]);
    }

    public function isDenied(): bool
    {
        return in_array($this->status, [self::STATUS_USER_DENIED, self::STATUS_DRIVER_CANCELED]);
    }

    /**
     * Accept the offer — stores WHO accepted.
     * @param string $actorType 'user' | 'driver'
     */
    public function accept(string $actorType = 'driver')
    {
        $status = $actorType === 'user'
            ? self::STATUS_USER_ACCEPTED
            : self::STATUS_DRIVER_ACCEPTED;

        $this->update(['status' => $status]);
    }

    /**
     * Deny / cancel the offer — stores WHO denied.
     * @param string $actorType 'user' | 'driver'
     */
    public function deny(string $actorType = 'user')
    {
        $status = $actorType === 'driver'
            ? self::STATUS_DRIVER_CANCELED
            : self::STATUS_USER_DENIED;

        $this->update(['status' => $status]);
    }
}
