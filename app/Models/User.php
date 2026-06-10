<?php

namespace App\Models;

use Hash;
use Carbon\Carbon;
use DateTimeInterface;
use App\Models\Reviews;
use Laravel\Sanctum\HasApiTokens;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Purchase;
use Illuminate\Contracts\Translation\HasLocalePreference;

class User extends Authenticatable implements HasLocalePreference
{
    use HasFactory, HasAdvancedFilter, Notifiable, SoftDeletes, HasApiTokens;

    public $table = 'users';
    protected $guarded = [];
    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'remember_token',
        'password',
    ];

    protected $dates = [
        'email_verified_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    public $orderable = [
        'id',
        'name',
        'email',
        'email_verified_at',
        'locale',
        'country_code',
        'fcm_token',
        'is_active',
        'login_type',
        'phone_number',
        'profile_pic',
        'reviews_count',
        'reviews_sum',
        'wallet_amount',
        'gender',
    ];

    public $filterable = [
        'id',
        'name',
        'email',
        'email_verified_at',
        'roles.title',
        'locale',
        'country_code',
        'fcm_token',
        'login_type',
        'phone_number',
        'profile_pic',
        'reviews_count',
        'reviews_sum',
        'wallet_amount',
        'gender',
    ];

    public function getFullNameAttribute()
    {
        return $this->name;
    }

    public function setFullNameAttribute($value)
    {
        $this->attributes['name'] = $value;
    }

    public function getImageurlAttribute()
    {
        if ($this->profile_pic == null) {

            return '';
        }
        return path($this->id, 'users')  . $this->profile_pic;
    }
    public static function findToken($token)
    {
        $token = str_replace('Bearer ', '', $token); // Remove 'Bearer ' prefix if it exists
        $accessToken = PersonalAccessToken::findToken($token);

        if ($accessToken) {
            return $accessToken->tokenable; // Assuming tokenable is the User model
        }

        return null;
    }
    public function getIsAdminAttribute()
    {
        return $this->roles()->where('title', 'Admin')->exists();
    }
    public function review()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }
    public function scopeAdmins()
    {
        return $this->whereHas('roles', fn ($q) => $q->where('title', 'Admin'));
    }

    public function scopeDrivers($query)
    {
        return $query->whereHas('roles', fn ($q) => $q->where('title', 'Driver'));
    }

    public function scopeAvailableDrivers($query)
    {
        return $query->whereHas('roles', fn ($q) => $q->where('title', 'Driver'))
            ->where('is_active', true)
            ->whereDoesntHave('driverOrders', function ($q) {
                $q->whereIn('status', [
                    Order::STATUS_USER_ACCEPT_OFFER,
                    Order::STATUS_PAYMENT_PENDING,
                    Order::STATUS_PAYMENT_PAID,
                    Order::STATUS_ASSIGNED,
                    Order::STATUS_DRIVER_ON_A_WAY,
                    Order::STATUS_ARRIVED,
                    Order::STATUS_ON_TRIP
                ]);
            });
    }

    public function driverOrders()
    {
        return $this->hasMany(Order::class, 'driver_id');
    }

    public function isAvailableDriver()
    {
        if (!$this->is_active) return false;

        return !$this->driverOrders()
            ->whereIn('status', [
                Order::STATUS_USER_ACCEPT_OFFER,
                Order::STATUS_PAYMENT_PENDING,
                Order::STATUS_PAYMENT_PAID,
                Order::STATUS_ASSIGNED,
                Order::STATUS_DRIVER_ON_A_WAY,
                Order::STATUS_ARRIVED,
                Order::STATUS_ON_TRIP
            ])->exists();
    }

    public function isCompatibleWithService($serviceId)
    {
        return $this->profile && $this->profile->service_id == $serviceId;
    }

    public function preferredLocale()
    {
        return $this->locale;
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getEmailVerifiedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('project.datetime_format')) : null;
    }

    public function setEmailVerifiedAtAttribute($value)
    {
        $this->attributes['email_verified_at'] = $value ? Carbon::createFromFormat(config('project.datetime_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = Hash::needsRehash($input) ? Hash::make($input) : $input;
        }
    }
    public function otp()
    {
        return $this->hasMany(Otp::class);
    }
    public function profile()
    {
        return $this->hasOne(DriverProfile::class);
    }
    public function identity()
    {
        return $this->hasOne(UserIdentity::class);
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }
    
    public function country()
    {
        return $this->belongsTo(\App\Models\Marketopia\MarketopiaCountry::class, 'country_id');
    }
    
    public function city()
    {
        return $this->belongsTo(\App\Models\Marketopia\MarketopiaCity::class, 'city_id');
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
    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function savedCards()
    {
        return $this->hasMany(SavedCard::class);
    }

    public function purchases()
    {
        return $this->hasMany(\App\Models\Purchase::class, 'driver_id');
    }

    public function getActivePurchaseAttribute()
    {
        return Purchase::where('driver_id', $this->id)
            ->with('package')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();
    }

    public function getActivePackageAttribute()
    {
        return $this->active_purchase ? $this->active_purchase->package : null;
    }

    public function pointTransactions()
    {
        return $this->hasMany(PointTransaction::class);
    }

    public function getReferralBonusVal(User $referee)
    {
        // Matching based on note pattern used in AuthenticationController (after fix)
        return $this->walletTransactions()
                    ->where('note', 'Referral Bonus (Invited: ' . $referee->name . ')')
                    ->value('amount') ?? 0;
    }

    public function getTotalReferralEarningsAttribute()
    {
        return $this->walletTransactions()
                    ->where('note', 'LIKE', 'Referral Bonus (Invited: %')
                    ->sum('amount');
    }

    /**
     * Update user location and broadcast the event
     * 
     * @param float $latitude
     * @param float $longitude
     * @return bool
     */
    public function updateLocation($latitude, $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $saved = $this->save();
        
        if ($saved) {
            broadcast(new \App\Events\LocationUpdated($this))->toOthers();
        }
        
        return $saved;
    }

    public function sendPushNotification($title, $body, $data = [])
    {
        if (isset($data['order_id']) && !isset($data['order_data'])) {
            $order = \App\Models\Order::with(['driver', 'user', 'service', 'offers', 'reviews'])->find($data['order_id']);
            if ($order) {
                $orderData = (new \App\Http\Resources\OrderResource($order))->resolve();
                $data['order_data'] = json_encode($orderData);
            }
        }

        // Save to Database
        \Illuminate\Support\Facades\Notification::send($this, new \App\Notifications\PushNotification(
            $title,
            $body,
            $data['image_url'] ?? null
        ));

        // Push to Firebase if token exists
        if ($this->fcm_token) {
            try {
                $fcmData = [];
                foreach ($data as $key => $value) {
                    $fcmData[(string)$key] = is_string($value) ? $value : json_encode($value);
                }

                $messaging = app('firebase.messaging');
                $message = \Kreait\Firebase\Messaging\CloudMessage::withTarget('token', $this->fcm_token)
                    ->withNotification([
                        'title' => $title,
                        'body' => $body,
                    ])
                    ->withData($fcmData);

                $messaging->send($message);
            } catch (\Exception $e) {
                \Log::error("FCM User Notification Error for {$this->id}: " . $e->getMessage());
            }
        }
    }

    /**
     * Check if this user has an active order with another user
     * 
     * @param int $userId
     * @return bool
     */
    public function hasActiveOrderWith($userId)
    {
        return Order::where(function($query) use ($userId) {
            $query->where('user_id', $this->id)
                  ->where('driver_id', $userId);
        })
        ->orWhere(function($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->where('driver_id', $this->id);
        })
        ->whereIn('status', ['pending', 'accepted', 'started'])
        ->exists();
    }
     
}
