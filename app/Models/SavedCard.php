<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'card_token',
        'card_subtype',
        'masked_pan',
        'is_default',
        'card_holder_name',
        'expiry_month',
        'expiry_year',
        'paymob_order_id',
        'paymob_transaction_id',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    protected $hidden = [
        'card_token', // Never expose the token in API responses
    ];

    /**
     * Get the user that owns the saved card.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get cards for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get the default card for a user.
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Get formatted expiry date.
     */
    public function getExpiryDateAttribute()
    {
        if ($this->expiry_month && $this->expiry_year) {
            return $this->expiry_month . '/' . substr($this->expiry_year, -2);
        }
        return null;
    }

    /**
     * Set this card as the default and unset other defaults.
     */
    public function setAsDefault()
    {
        // Unset all other default cards for this user
        static::where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->update(['is_default' => false]);

        // Set this card as default
        $this->update(['is_default' => true]);
    }
}
