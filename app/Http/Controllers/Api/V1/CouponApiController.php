<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponApiController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $coupons = Coupon::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->where(function ($q) {
                $q->whereNull('usage_limit')
                  ->orWhereColumn('used_count', '<', 'usage_limit');
            })
            ->with('service:id,name')
            ->orderBy('id', 'desc')
            ->get()
            ->filter(function ($coupon) use ($user) {
                if (!$user) return true;
                $userUsageCount = $coupon->usages()->where('user_id', $user->id)->count();
                return $userUsageCount < $coupon->user_limit;
            })
            ->values()
            ->map(function ($coupon) {
                return [
                    'id' => $coupon->id,
                    'code' => $coupon->code,
                    'title' => $coupon->title ?? 'خصم مميز',
                    'type' => $coupon->type,
                    'value' => floatval($coupon->value),
                    'max_discount' => floatval($coupon->max_discount ?? 0),
                    'min_order' => floatval($coupon->min_order ?? 0),
                    'user_limit' => intval($coupon->user_limit),
                    'expires_at' => $coupon->expires_at ? $coupon->expires_at->toDateTimeString() : null,
                    'service_id' => $coupon->service_id,
                    'service_name' => $coupon->service->name ?? null,
                    'is_public' => (bool) $coupon->is_public,
                ];
            });

        return Resp($coupons, 'تم جلب الكوبونات المتاحة بنجاح');
    }

    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'service_id' => 'nullable|integer',
            'order_amount' => 'required|numeric|min:0',
        ]);

        $user = auth()->user();
        $code = trim($request->code);

        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return Resp(null, 'كود الخصم غير صحيح أو غير موجود', 404, false);
        }

        $check = $coupon->isValidForUser($user->id, $request->service_id, floatval($request->order_amount));

        if (!$check['valid']) {
            return Resp(null, $check['message'], 400, false);
        }

        $discountAmount = $coupon->calculateDiscount(floatval($request->order_amount));
        $finalAmount = max(0, floatval($request->order_amount) - $discountAmount);

        $data = [
            'coupon_id' => $coupon->id,
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => floatval($coupon->value),
            'discount_amount' => round($discountAmount, 2),
            'original_amount' => floatval($request->order_amount),
            'final_amount' => round($finalAmount, 2),
        ];

        return Resp($data, 'تم تطبيق كود الخصم بنجاح');
    }
}
