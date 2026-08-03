<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponApiController extends Controller
{
    public function index(Request $request)
    {
        // Auto-create user_coupons table if missing
        if (!\Illuminate\Support\Facades\Schema::hasTable('user_coupons')) {
            try {
                \Illuminate\Support\Facades\Schema::create('user_coupons', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->foreignId('coupon_id')->constrained('coupons')->onDelete('cascade');
                    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                    $table->boolean('is_used')->default(false);
                    $table->timestamps();

                    $table->unique(['coupon_id', 'user_id']);
                });
            } catch (\Exception $e) {
                \Log::error("Failed to auto-create user_coupons table: " . $e->getMessage());
            }
        }

        $user = auth()->user();
        $userId = $user ? $user->id : null;

        $coupons = Coupon::where(function ($q) {
                $q->where('is_active', 1)
                  ->orWhere('is_active', true)
                  ->orWhere('is_active', '1');
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->where(function ($q) {
                $q->whereNull('usage_limit')
                  ->orWhereColumn('used_count', '<', 'usage_limit');
            })
            ->where(function ($q) use ($userId) {
                $q->where('is_public', 1)
                  ->orWhere('is_public', true)
                  ->orWhereNull('is_public');
                if ($userId) {
                    $q->orWhereHas('userCoupons', function ($uq) use ($userId) {
                        $uq->where('user_id', $userId)->where('is_used', false);
                    });
                }
            })
            ->with('service:id,name')
            ->orderBy('id', 'desc')
            ->get()
            ->filter(function ($coupon) use ($user) {
                if (!$user) return true;
                $userUsageCount = $coupon->usages()->where('user_id', $user->id)->count();
                return $userUsageCount < ($coupon->user_limit ?? 1);
            })
            ->values()
            ->map(function ($coupon) {
                return [
                    'id' => $coupon->id,
                    'code' => $coupon->code,
                    'title' => $coupon->title ?? 'كوبون خصم',
                    'type' => $coupon->type ?? 'percentage',
                    'value' => floatval($coupon->value),
                    'max_discount' => floatval($coupon->max_discount ?? 0),
                    'min_order' => floatval($coupon->min_order ?? 0),
                    'user_limit' => intval($coupon->user_limit ?? 1),
                    'expires_at' => $coupon->expires_at ? $coupon->expires_at->toDateTimeString() : null,
                    'service_id' => $coupon->service_id,
                    'service_name' => $coupon->service->name ?? null,
                    'is_public' => (bool) ($coupon->is_public ?? true),
                ];
            });

        return Resp($coupons, 'تم جلب الكوبونات المتاحة بنجاح');
    }

    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code'         => 'required|string',
            'service_id'   => 'nullable|integer',
            // Accept both 'amount' (Flutter) and 'order_amount' (legacy) field names
            'amount'       => 'nullable|numeric|min:0',
            'order_amount' => 'nullable|numeric|min:0',
        ]);

        $user        = auth()->user();
        $code        = trim($request->code);
        $orderAmount = floatval($request->amount ?? $request->order_amount ?? 0);

        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return Resp(null, 'كود الخصم غير صحيح أو غير موجود', 404, false);
        }

        $check = $coupon->isValidForUser($user->id, $request->service_id, $orderAmount);

        if (!$check['valid']) {
            return Resp(null, $check['message'], 400, false);
        }

        $discountAmount = $coupon->calculateDiscount($orderAmount);
        $finalAmount    = max(0, $orderAmount - $discountAmount);

        $data = [
            'coupon_id'       => $coupon->id,
            'code'            => $coupon->code,
            'type'            => $coupon->type,
            'value'           => floatval($coupon->value),
            'discount_amount' => round($discountAmount, 2),
            'original_amount' => $orderAmount,
            'final_amount'    => round($finalAmount, 2),
            'message'         => 'تم تطبيق كود الخصم بنجاح',
        ];

        return Resp($data, 'تم تطبيق كود الخصم بنجاح');
    }
}
