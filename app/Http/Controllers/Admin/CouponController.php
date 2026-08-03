<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Service;
use App\Models\User;
use App\Models\Order;
use App\Notifications\PushNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $rows = Coupon::with(['service'])->withCount('usages')->orderBy('id', 'desc')->paginate(15);
        return view('admin.coupons.index', compact('rows'));
    }

    public function create()
    {
        $services = Service::where('enable', true)->get();
        return view('admin.coupons.create', compact('services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code|max:50',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0.01',
            'max_discount' => 'nullable|numeric|min:0',
            'min_order' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'user_limit' => 'required|integer|min:1',
            'service_id' => 'nullable|exists:services,id',
            'expires_at' => 'nullable|date',
        ]);

        Coupon::create([
            'code' => strtoupper(trim($request->code)),
            'type' => $request->type,
            'value' => $request->value,
            'max_discount' => $request->max_discount,
            'min_order' => $request->min_order ?? 0,
            'usage_limit' => $request->usage_limit,
            'user_limit' => $request->user_limit ?? 1,
            'service_id' => $request->service_id,
            'expires_at' => $request->expires_at,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'تم إنشاء كود الخصم بنجاح');
    }

    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'تم حذف الكوبون بنجاح');
    }

    public function toggleActive($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->is_active = !$coupon->is_active;
        $coupon->save();

        return response()->json([
            'success' => true,
            'is_active' => $coupon->is_active,
            'message' => $coupon->is_active ? 'تم تفعيل الكوبون' : 'تم تعطيل الكوبون'
        ]);
    }

    public function sendFcmNotification(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $request->validate([
            'target' => 'required|in:all_users,all_drivers,inactive_users,active_vip,specific_user',
            'user_id' => 'required_if:target,specific_user|nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image_url' => 'nullable|url'
        ]);

        $users = collect();

        if ($request->target === 'all_users') {
            $users = User::whereHas('roles', fn($q) => $q->where('title', 'User'))
                         ->whereNotNull('fcm_token')
                         ->get();
        } elseif ($request->target === 'all_drivers') {
            $users = User::drivers()->whereNotNull('fcm_token')->get();
        } elseif ($request->target === 'inactive_users') {
            $thirtyDaysAgo = now()->subDays(30);
            $users = User::whereHas('roles', fn($q) => $q->where('title', 'User'))
                         ->whereNotNull('fcm_token')
                         ->whereDoesntHave('userOrders', function ($q) use ($thirtyDaysAgo) {
                             $q->where('created_at', '>=', $thirtyDaysAgo);
                         })
                         ->get();
        } elseif ($request->target === 'active_vip') {
            $users = User::whereHas('roles', fn($q) => $q->where('title', 'User'))
                         ->whereNotNull('fcm_token')
                         ->whereHas('userOrders', function ($q) {
                             $q->where('status', Order::STATUS_COMPLETED);
                         }, '>=', 5)
                         ->get();
        } elseif ($request->target === 'specific_user') {
            $user = User::find($request->user_id);
            if ($user && $user->fcm_token) {
                $users->push($user);
            }
        }

        if ($users->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'لم يتم العثور على مستخدمين يمتلكون رمز FCM للإرسال ضمن الفئة المحددة.',
            ], 422);
        }

        try {
            Notification::send($users, new PushNotification(
                $request->title,
                $request->body,
                $request->image_url
            ));
        } catch (\Exception $e) {
            \Log::error("Database Notification Error: " . $e->getMessage());
        }

        $tokens = $users->pluck('fcm_token')->filter()->unique()->values()->toArray();

        if (!empty($tokens)) {
            try {
                $messaging = app('firebase.messaging');
                $message = \Kreait\Firebase\Messaging\CloudMessage::new()
                    ->withNotification([
                        'title' => $request->title,
                        'body' => $request->body,
                        'image' => $request->image_url,
                    ])
                    ->withData([
                        'type' => 'coupon',
                        'coupon_code' => (string)$coupon->code,
                        'coupon_id' => (string)$coupon->id,
                    ]);

                foreach (array_chunk($tokens, 500) as $chunk) {
                    $messaging->sendMulticast($message, $chunk);
                }
            } catch (\Exception $e) {
                \Log::error("FCM Send Error for Coupon: " . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال إشعار الكوبون بنجاح إلى ' . $users->count() . ' مستخدم.',
            'sent_count' => $users->count()
        ]);
    }

    public function searchUsers(Request $request)
    {
        $q = trim($request->get('q', ''));
        $users = User::whereHas('roles', fn($query) => $query->where('title', 'User'))
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('phone_number', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->select('id', 'name', 'phone_number', 'email', 'fcm_token')
            ->orderBy('id', 'desc')
            ->limit(50)
            ->get();

        return response()->json($users);
    }
}
