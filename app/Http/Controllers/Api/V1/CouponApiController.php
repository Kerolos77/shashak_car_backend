<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponApiController extends Controller
{
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
