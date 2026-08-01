@extends('layouts.admin')

@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxxl">

        <div class="card mb-6 shadow-sm border-0">
            <div class="card-body p-6 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-4">
                    <div class="bg-light-primary p-4 rounded-circle">
                        <i class="ki-outline ki-plus-circle fs-2x text-primary"></i>
                    </div>
                    <div>
                        <h2 class="fw-bolder mb-1">إضافة كوبون خصم جديد (New Coupon)</h2>
                        <div class="text-muted fs-6">إنشاء برومو كود ترويجي جديد وتحديد تفاصيله وصلاحيته.</div>
                    </div>
                </div>
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-light fw-bold">
                    <i class="ki-outline ki-arrow-right fs-4 me-1"></i> العودة للكوبونات
                </a>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-6">
                <form action="{{ route('admin.coupons.store') }}" method="POST">
                    @csrf

                    <div class="row g-6 mb-6">
                        <div class="col-md-6">
                            <label class="form-label required fw-bold">كود الخصم (Promo Code)</label>
                            <input type="text" name="code" class="form-control form-control-solid text-uppercase fw-bolder" placeholder="مثال: SHAKSHAK20" required value="{{ old('code') }}" />
                        </div>

                        <div class="col-md-3">
                            <label class="form-label required fw-bold">نوع الخصم</label>
                            <select name="type" class="form-select form-select-solid" required>
                                <option value="percentage" {{ old('type') === 'percentage' ? 'selected' : '' }}>نسبة مئوية (%)</option>
                                <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>مبلغ ثابت (ج.م)</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label required fw-bold">قيمة الخصم</label>
                            <input type="number" step="0.01" name="value" class="form-control form-control-solid" placeholder="مثال: 20" required value="{{ old('value') }}" />
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">الحد الأقصى للخصم (ج.م - في حالة النسبة المئوية)</label>
                            <input type="number" step="0.01" name="max_discount" class="form-control form-control-solid" placeholder="مثال: 50 (أو اتركه فارغاً)" value="{{ old('max_discount') }}" />
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">الحد الأدنى لقيمة الطلب لاستخدام الكود (ج.م)</label>
                            <input type="number" step="0.01" name="min_order" class="form-control form-control-solid" placeholder="0" value="{{ old('min_order', 0) }}" />
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">تخصيص لخدمة معينة (اختياري)</label>
                            <select name="service_id" class="form-select form-select-solid">
                                <option value="">جميع الخدمات والرحلات</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                        {{ $service->title }} ({{ $service->service_type }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">إجمالي عدد مرات الاستخدام الكلية (إجباري)</label>
                            <input type="number" name="usage_limit" class="form-control form-control-solid" placeholder="مثال: 1000 (اتركه فارغاً لمفتوح)" value="{{ old('usage_limit') }}" />
                        </div>

                        <div class="col-md-4">
                            <label class="form-label required fw-bold">عدد مرات الاستخدام المسموحة لكل مستخدم</label>
                            <input type="number" name="user_limit" class="form-control form-control-solid" placeholder="1" required value="{{ old('user_limit', 1) }}" />
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">تاريخ ووقت انتهاء الصلاحية</label>
                            <input type="datetime-local" name="expires_at" class="form-control form-control-solid" value="{{ old('expires_at') }}" />
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch form-check-custom form-check-solid p-4 border rounded bg-light d-flex align-items-center justify-content-between">
                                <div>
                                    <label class="form-check-label fw-bolder text-gray-800 fs-5 d-block" for="is_active">
                                        تفعيل الكود فوراً عند الحفظ (ON)
                                    </label>
                                </div>
                                <input class="form-check-input h-30px w-50px" type="checkbox" name="is_active" value="1" id="is_active" checked />
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3">
                        <a href="{{ route('admin.coupons.index') }}" class="btn btn-light fw-bold">إلغاء</a>
                        <button type="submit" class="btn btn-primary fw-bold">
                            <i class="ki-outline ki-check fs-4 me-1"></i> حفظ وتفعيل الكوبون
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
