@extends('layouts.admin')

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted">إدارة الحسابات</li>
    <span class="bullet bg-gray-300 w-5px h-2px"></span>
    <li class="breadcrumb-item text-dark">المصروفات</li>
@endsection

@section('content')
@section('title', 'لوحة التحكم للمصروفات')
@section('pageName', 'لوحة التحكم للمصروفات')

<!-- Notification Alerts -->
@if(session('success'))
    <div class="alert alert-success d-flex align-items-center p-5 mb-5">
        <i class="ti ti-circle-check fs-2hx text-success me-4"></i>
        <div class="d-flex flex-column">
            <h4 class="mb-1 text-dark">نجاح العملية</h4>
            <span>{{ session('success') }}</span>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger d-flex align-items-center p-5 mb-5">
        <i class="ti ti-circle-x fs-2hx text-danger me-4"></i>
        <div class="d-flex flex-column">
            <h4 class="mb-1 text-dark">خطأ</h4>
            <span>{{ session('error') }}</span>
        </div>
    </div>
@endif

<!-- Expense Statistics Cards -->
<div class="row g-5 mb-7">
    <!-- Today Expenses -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-flush h-md-100 border-start border-primary border-4">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <span class="text-gray-400 fw-bold fs-7 text-uppercase">مصروفات اليوم</span>
                    <span class="fs-2hx fw-bold text-dark me-2 lh-1ls mt-2">{{ number_format($todayExpenses, 2) }} ج.م</span>
                </div>
            </div>
            <div class="card-body d-flex flex-column justify-content-end pr-0">
                <span class="text-gray-500 fs-7">إجمالي مصروفات اليوم الفعلي</span>
            </div>
        </div>
    </div>

    <!-- Month Expenses -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-flush h-md-100 border-start border-danger border-4">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <span class="text-gray-400 fw-bold fs-7 text-uppercase">مصروفات هذا الشهر</span>
                    <span class="fs-2hx fw-bold text-danger me-2 lh-1ls mt-2">{{ number_format($monthExpenses, 2) }} ج.م</span>
                </div>
            </div>
            <div class="card-body d-flex flex-column justify-content-end pr-0">
                <span class="text-gray-500 fs-7">إجمالي مصروفات الشهر الحالي</span>
            </div>
        </div>
    </div>

    <!-- Month Revenues -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-flush h-md-100 border-start border-success border-4">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <span class="text-gray-400 fw-bold fs-7 text-uppercase">إيرادات هذا الشهر</span>
                    <span class="fs-2hx fw-bold text-success me-2 lh-1ls mt-2">{{ number_format($monthIncomes, 2) }} ج.م</span>
                </div>
            </div>
            <div class="card-body d-flex flex-column justify-content-end pr-0">
                <span class="text-gray-500 fs-7">شحنات المحفظة والرحلات الناجحة</span>
            </div>
        </div>
    </div>

    <!-- Net Profit / Loss -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-flush h-md-100 border-start {{ $netProfit >= 0 ? 'border-success' : 'border-danger' }} border-4">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <span class="text-gray-400 fw-bold fs-7 text-uppercase">صافي الربح / الخسارة</span>
                    <span class="fs-2hx fw-bold {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }} me-2 lh-1ls mt-2">{{ number_format($netProfit, 2) }} ج.م</span>
                </div>
            </div>
            <div class="card-body d-flex flex-column justify-content-end pr-0">
                <span class="text-gray-500 fs-7">الإيرادات مطروحاً منها المصروفات</span>
            </div>
        </div>
    </div>
</div>

<!-- Controls & Actions -->
<div class="d-flex flex-stack justify-content-between mb-5">
    <div class="d-flex align-items-center position-relative my-1">
        <span class="fs-4 fw-semibold text-gray-700">قائمة تفاصيل كافة المصروفات</span>
        @if(request()->hasAny(['category', 'month', 'keyword']))
            <span class="badge bg-light-primary text-primary ms-3">
                مجموع النتائج المفلترة: {{ number_format($filteredTotalEgp ?? 0, 2) }} ج.م
            </span>
        @endif
    </div>
    
    <div class="d-flex align-items-center gap-2">
        <!-- Sync Button Form -->
        <form action="{{ route('admin.expenses.sync') }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="btn btn-light-primary btn-active-light-primary me-2">
                <i class="ki-outline ki-arrows-loop fs-4 me-1"></i> تحديث المصروفات التلقائية
            </button>
        </form>

        <!-- Create Button -->
        <a href="{{ route('admin.expenses.create') }}" class="btn btn-primary">
            <i class="ki-outline ki-plus fs-4 me-1"></i> إضافة مصروف يدوي
        </a>
    </div>
</div>

<!-- Filter Form Card -->
<div class="card mb-5 border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('admin.expenses.index') }}">
            <div class="row g-3 align-items-center">
                <div class="col-md-3">
                    <label class="form-label fw-bold fs-7 mb-1">تصفية بحسب التصنيف</label>
                    <select name="category" class="form-select form-select-solid">
                        <option value="">جميع التصنيفات</option>
                        <option value="digitalocean" {{ request('category') === 'digitalocean' ? 'selected' : '' }}>ديجيتال أوشن (سيرفر)</option>
                        <option value="google_cloud" {{ request('category') === 'google_cloud' ? 'selected' : '' }}>جوجل كلاود (Firebase/Gemini)</option>
                        <option value="sms" {{ request('category') === 'sms' ? 'selected' : '' }}>رسائل SMS النصية</option>
                        <option value="domain" {{ request('category') === 'domain' ? 'selected' : '' }}>تجديد الدومين</option>
                        <option value="paymob" {{ request('category') === 'paymob' ? 'selected' : '' }}>عمولات Paymob</option>
                        <option value="other" {{ request('category') === 'other' ? 'selected' : '' }}>مصاريف أخرى</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold fs-7 mb-1">تصفية بحسب الشهر والسنة</label>
                    <input type="month" name="month" class="form-control form-control-solid" value="{{ request('month') }}" />
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold fs-7 mb-1">بحث في التفاصيل / الوصف</label>
                    <input type="text" name="keyword" class="form-control form-control-solid" placeholder="ابحث برقم الفاتورة أو الوصف..." value="{{ request('keyword') }}" />
                </div>
                <div class="col-md-2 d-flex align-items-center gap-2 mt-7">
                    <button type="submit" class="btn btn-primary fw-bold w-100">
                        <i class="ki-outline ki-filter me-1"></i> تصفية
                    </button>
                    @if(request()->hasAny(['category', 'month', 'keyword']))
                        <a href="{{ route('admin.expenses.index') }}" class="btn btn-light-danger fw-bold" title="إعادة تعيين">
                            <i class="ki-outline ki-cross fs-4"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Table Card -->
<div class="card">
    <div class="card-body pt-5">
        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="expenses_table">
                <thead>
                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0 text-center">
                        <th class="min-w-50px">#</th>
                        <th class="min-w-120px">التصنيف</th>
                        <th class="min-w-100px">القيمة الأصلية</th>
                        <th class="min-w-120px">سعر الصرف</th>
                        <th class="min-w-100px">القيمة بالجنيه (EGP)</th>
                        <th class="min-w-250px text-start">الوصف والبيانات</th>
                        <th class="min-w-100px">التاريخ</th>
                        <th class="min-w-80px">النوع</th>
                        <th class="min-w-80px">المرفق</th>
                        <th class="min-w-80px">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600 text-center">
                    @forelse ($rows ?? [] as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>
                                @if($item->category === 'paymob')
                                    <span class="badge bg-light-primary text-primary fs-7">عمولة Paymob</span>
                                @elseif($item->category === 'digitalocean')
                                    <span class="badge bg-light-warning text-warning fs-7">ديجيتال أوشن (سيرفر)</span>
                                @elseif($item->category === 'google_cloud')
                                    <span class="badge bg-light-danger text-danger fs-7">جوجل كلاود (Firebase/Gemini)</span>
                                @elseif($item->category === 'sms')
                                    <span class="badge bg-light-success text-success fs-7">رسائل SMS</span>
                                @elseif($item->category === 'domain')
                                    <span class="badge bg-light-info text-info fs-7">تجديد الدومين</span>
                                @else
                                    <span class="badge bg-light-secondary text-secondary fs-7">مصاريف أخرى</span>
                                @endif
                            </td>
                            <td>
                                {{ number_format($item->amount, 2) }} {{ $item->currency }}
                            </td>
                            <td>
                                @if($item->currency === 'USD')
                                    <span class="text-muted fs-7">1 $ = {{ number_format($item->exchange_rate, 2) }} ج.م</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="text-dark fw-bold">
                                {{ number_format($item->amount_egp, 2) }} ج.م
                            </td>
                            <td class="text-start fs-7">
                                {{ $item->description }}
                            </td>
                            <td>
                                {{ $item->expense_date->format('Y-m-d') }}
                            </td>
                            <td>
                                @if($item->is_automated)
                                    <span class="badge badge-light-success fs-8">تلقائي</span>
                                @else
                                    <span class="badge badge-light-primary fs-8">يدوي</span>
                                @endif
                            </td>
                            <td>
                                @if($item->invoice_path)
                                    <a href="{{ asset('storage/' . $item->invoice_path) }}" target="_blank" class="btn btn-sm btn-icon btn-light-primary" title="عرض الفاتورة المرفقة">
                                        <i class="ki-outline ki-file fs-4"></i>
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('admin.expenses.destroy', $item->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المصروف؟')" class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-icon btn-light-danger" title="حذف">
                                        <i class="ki-outline ki-trash fs-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <div class="text-muted">لا توجد مصروفات مسجلة حالياً.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center flex-wrap pt-5">
            <div class="fs-6 fw-semibold text-gray-700">
                عرض {{ $rows->firstItem() ?? 0 }} إلى {{ $rows->lastItem() ?? 0 }} من إجمالي {{ $rows->total() }} مصروف
            </div>
            <div>
                {{ $rows->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
