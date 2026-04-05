<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;

class ReviewApiController extends Controller
{
    /**
     * Get user ID from bearer token
     */
    private function getUserIDByToken($hashedToken)
    {
        $token = PersonalAccessToken::findToken($hashedToken);
        if ($token != null) {
            return $token->tokenable_id;
        } else {
            return false;
        }
    }

    /**
     * إضافة تقييم للطلب
     * POST /api/v1/order/{order_id}/review
     */
    public function store(Request $request, $order_id)
    {
        try {
            // التحقق من صحة البيانات
            $validated = $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string|max:1000',
            ]);

            $currentUserId = $this->getUserIDByToken($request->bearerToken());

            if (!$currentUserId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 401);
            }

            // جلب الطلب
            $order = Order::find($order_id);

            if (!$order) {
                return Resp(null, 'الطلب غير موجود', 404, false);
            }

            // التحقق من أن الطلب مكتمل
            if ($order->status != 'completed') {
                return Resp(null, 'لا يمكن التقييم إلا بعد اكتمال الطلب', 200, false);
            }

            // التحقق من أن المستخدم الحالي هو صاحب الطلب أو السواق
            if ($order->user_id != $currentUserId && $order->driver_id != $currentUserId) {
                return Resp(null, 'غير مصرح لك بالتقييم على هذا الطلب', 403, false);
            }

            // تحديد نوع المقيّم والمُقيَّم
            $reviewer = $order->user_id == $currentUserId ? 'user' : 'driver';
            $toUserId = $order->user_id == $currentUserId ? $order->driver_id : $order->user_id;

            // التحقق من عدم وجود تقييم سابق
            $existingReview = Review::where('order_id', $order_id)
                ->where('from_user_id', $currentUserId)
                ->first();

            if ($existingReview) {
                return Resp(null, 'لقد قمت بالتقييم على هذا الطلب مسبقاً', 200, false);
            }

            // إنشاء التقييم
            $review = Review::create([
                'from_user_id' => $currentUserId,
                'to_user_id' => $toUserId,
                'order_id' => $order_id,
                'reviewer' => $reviewer,
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
            ]);

            // تحديث إحصائيات التقييم للمستخدم المُقيَّم
            $this->updateUserRatingStats($toUserId);

            return Resp(new ReviewResource($review), 'تم إضافة التقييم بنجاح', 201, true);

        } catch (\Exception $e) {
            return Resp(null, 'حدث خطأ: ' . $e->getMessage(), 500, false);
        }
    }

    /**
     * جلب تقييمات السواق
     * GET /api/v1/driver/{driver_id}/reviews
     */
    public function getDriverReviews($driver_id)
    {
        try {
            $driver = User::find($driver_id);

            if (!$driver) {
                return Resp(null, 'السواق غير موجود', 404, false);
            }

            // جلب التقييمات
            $reviews = Review::where('to_user_id', $driver_id)
                ->where('reviewer', 'user') // التقييمات من المستخدمين فقط
                ->with(['fromUser', 'order'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            // حساب متوسط التقييم
            $averageRating = Review::where('to_user_id', $driver_id)
                ->where('reviewer', 'user')
                ->avg('rating');

            $totalReviews = Review::where('to_user_id', $driver_id)
                ->where('reviewer', 'user')
                ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'driver' => [
                        'id' => $driver->id,
                        'name' => $driver->full_name ?? $driver->name,
                        'avatar' => $driver->avatar ?? '',
                        'average_rating' => round($averageRating, 1) ?? 0,
                        'total_reviews' => $totalReviews,
                    ],
                    'reviews' => ReviewResource::collection($reviews->items()),
                    'pagination' => [
                        'current_page' => $reviews->currentPage(),
                        'total_pages' => $reviews->lastPage(),
                        'total_reviews' => $reviews->total(),
                        'per_page' => $reviews->perPage(),
                    ],
                ],
                'message' => 'success',
            ], 200);

        } catch (\Exception $e) {
            return Resp(null, 'حدث خطأ: ' . $e->getMessage(), 500, false);
        }
    }

    /**
     * جلب تقييمات المستخدم
     * GET /api/v1/user/{user_id}/reviews
     */
    public function getUserReviews($user_id)
    {
        try {
            $user = User::find($user_id);

            if (!$user) {
                return Resp(null, 'المستخدم غير موجود', 404, false);
            }

            // جلب التقييمات
            $reviews = Review::where('to_user_id', $user_id)
                ->where('reviewer', 'driver') // التقييمات من السواقين فقط
                ->with(['fromUser', 'order'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            // حساب متوسط التقييم
            $averageRating = Review::where('to_user_id', $user_id)
                ->where('reviewer', 'driver')
                ->avg('rating');

            $totalReviews = Review::where('to_user_id', $user_id)
                ->where('reviewer', 'driver')
                ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->full_name ?? $user->name,
                        'avatar' => $user->avatar ?? '',
                        'average_rating' => round($averageRating, 1) ?? 0,
                        'total_reviews' => $totalReviews,
                    ],
                    'reviews' => ReviewResource::collection($reviews->items()),
                    'pagination' => [
                        'current_page' => $reviews->currentPage(),
                        'total_pages' => $reviews->lastPage(),
                        'total_reviews' => $reviews->total(),
                        'per_page' => $reviews->perPage(),
                    ],
                ],
                'message' => 'success',
            ], 200);

        } catch (\Exception $e) {
            return Resp(null, 'حدث خطأ: ' . $e->getMessage(), 500, false);
        }
    }

    /**
     * جلب التقييمات اللي أنا عملتها
     * GET /api/v1/my-reviews/given
     */
    public function myGivenReviews(Request $request)
    {
        try {
            $currentUserId = $this->getUserIDByToken($request->bearerToken());

            if (!$currentUserId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 401);
            }

            $reviews = Review::where('from_user_id', $currentUserId)
                ->with(['toUser', 'order'])
                ->orderBy('created_at', 'desc')
                ->get();

            return Resp(ReviewResource::collection($reviews), 'success', 200, true);

        } catch (\Exception $e) {
            return Resp(null, 'حدث خطأ: ' . $e->getMessage(), 500, false);
        }
    }

    /**
     * جلب التقييمات اللي أنا استلمتها
     * GET /api/v1/my-reviews/received
     */
    public function myReceivedReviews(Request $request)
    {
        try {
            $currentUserId = $this->getUserIDByToken($request->bearerToken());

            if (!$currentUserId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 401);
            }

            // جلب التقييمات
            $reviews = Review::where('to_user_id', $currentUserId)
                ->with(['fromUser', 'order'])
                ->orderBy('created_at', 'desc')
                ->get();

            // حساب متوسط التقييم
            $averageRating = Review::where('to_user_id', $currentUserId)->avg('rating');
            $totalReviews = Review::where('to_user_id', $currentUserId)->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'average_rating' => round($averageRating, 1) ?? 0,
                    'total_reviews' => $totalReviews,
                    'reviews' => ReviewResource::collection($reviews),
                ],
                'message' => 'success',
            ], 200);

        } catch (\Exception $e) {
            return Resp(null, 'حدث خطأ: ' . $e->getMessage(), 500, false);
        }
    }

    /**
     * التحقق من إمكانية التقييم للطلب
     * GET /api/v1/order/{order_id}/can-review
     */
    public function canReview(Request $request, $order_id)
    {
        try {
            $currentUserId = $this->getUserIDByToken($request->bearerToken());

            if (!$currentUserId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 401);
            }

            $order = Order::with(['driver', 'user'])->find($order_id);

            if (!$order) {
                return Resp(null, 'الطلب غير موجود', 404, false);
            }

            // التحقق من أن المستخدم الحالي هو صاحب الطلب أو السواق
            if ($order->user_id != $currentUserId && $order->driver_id != $currentUserId) {
                return Resp([
                    'can_review' => false,
                    'reason' => 'غير مصرح لك',
                ], 'success', 200, true);
            }

            // التحقق من وجود تقييم سابق
            $alreadyReviewed = Review::where('order_id', $order_id)
                ->where('from_user_id', $currentUserId)
                ->exists();

            $canReview = $order->status == 'completed' && !$alreadyReviewed;

            $toUser = $order->user_id == $currentUserId ? $order->driver : $order->user;

            return Resp([
                'can_review' => $canReview,
                'already_reviewed' => $alreadyReviewed,
                'order' => [
                    'id' => $order->id,
                    'status' => $order->status,
                    'to_user' => [
                        'id' => $toUser->id ?? '',
                        'name' => $toUser->full_name ?? $toUser->name ?? '',
                        'avatar' => $toUser->avatar ?? '',
                    ],
                ],
            ], 'success', 200, true);

        } catch (\Exception $e) {
            return Resp(null, 'حدث خطأ: ' . $e->getMessage(), 500, false);
        }
    }

    /**
     * تحديث إحصائيات التقييم للمستخدم
     */
    private function updateUserRatingStats($userId)
    {
        $user = User::find($userId);

        if ($user) {
            $totalReviews = Review::where('to_user_id', $userId)->count();
            $sumRatings = Review::where('to_user_id', $userId)->sum('rating');

            $user->update([
                'reviews_count' => $totalReviews,
                'reviews_sum' => $sumRatings,
            ]);
        }
    }
}
