<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'value' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'min_order' => 'decimal:2',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function calculateDiscount($orderAmount)
    {
        if ($this->type === 'fixed') {
            return min($this->value, $orderAmount);
        }

        $discount = ($orderAmount * $this->value) / 100;
        if ($this->max_discount && $this->max_discount > 0) {
            $discount = min($discount, $this->max_discount);
        }

        return min($discount, $orderAmount);
    }

    public function isValidForUser($userId, $serviceId = null, $orderAmount = 0)
    {
        if (!$this->is_active) {
            return ['valid' => false, 'message' => 'هذا الكوبون غير مفعّل'];
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return ['valid' => false, 'message' => 'لقد انتهت صلاحية هذا الكوبون'];
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return ['valid' => false, 'message' => 'لقد استنفذ هذا الكوبون الحد الأقصى للاستخدام'];
        }

        if ($this->service_id && $serviceId && $this->service_id != $serviceId) {
            return ['valid' => false, 'message' => 'هذا الكوبون غير مخصص لهذه الخدمة'];
        }

        if ($orderAmount < $this->min_order) {
            return ['valid' => false, 'message' => 'الحد الأدنى لاستخدام الكوبون هو ' . number_format($this->min_order, 2) . ' ج.م'];
        }

        $userUsageCount = $this->usages()->where('user_id', $userId)->count();
        if ($userUsageCount >= $this->user_limit) {
            return ['valid' => false, 'message' => 'لقد استخدمت هذا الكوبون بالحد الأقصى المسموح'];
        }

        return ['valid' => true, 'message' => 'كوبون صالح'];
    }
}
