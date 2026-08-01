<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Service;
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
}
