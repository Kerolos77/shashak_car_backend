@extends('layouts.admin')

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted">طلبات الانضمام</li>
    <span class="bullet bg-gray-300 w-5px h-2px"></span>
    <li class="breadcrumb-item text-dark">مراجعة أوراق ومستندات السائقين</li>
@endsection

@section('content')
@section('title', $pageTitle)

<!-- Stats Grid -->
<div class="row g-5 mb-8">
    <!-- Pending Applications -->
    <div class="col-xl-3 col-md-6">
        <div class="card h-md-100 border-start border-4 border-warning shadow-sm">
            <div class="card-body p-6">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <span class="text-gray-600 fw-bold fs-6">طلبات معلقة قيد المراجعة</span>
                    <span class="badge badge-light-warning px-3 py-2 fw-bold">جديد</span>
                </div>
                <div class="d-flex align-items-baseline">
                    <span class="fs-2hx fw-bold text-gray-900 me-2">{{ $pendingCount }}</span>
                    <span class="text-gray-500 fw-semibold fs-7">طلب مراجعة</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Rejected Applications -->
    <div class="col-xl-3 col-md-6">
        <div class="card h-md-100 border-start border-4 border-danger shadow-sm">
            <div class="card-body p-6">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <span class="text-gray-600 fw-bold fs-6">طلبات مرفوضة</span>
                    <span class="badge badge-light-danger px-3 py-2 fw-bold">مرفوض</span>
                </div>
                <div class="d-flex align-items-baseline">
                    <span class="fs-2hx fw-bold text-gray-900 me-2">{{ $rejectedCount }}</span>
                    <span class="text-gray-500 fw-semibold fs-7">طلب</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Drivers -->
    <div class="col-xl-3 col-md-6">
        <div class="card h-md-100 border-start border-4 border-success shadow-sm">
            <div class="card-body p-6">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <span class="text-gray-600 fw-bold fs-6">سائقون معتمدون</span>
                    <span class="badge badge-light-success px-3 py-2 fw-bold">نشط</span>
                </div>
                <div class="d-flex align-items-baseline">
                    <span class="fs-2hx fw-bold text-gray-900 me-2">{{ $activeCount }}</span>
                    <span class="text-gray-500 fw-semibold fs-7">سائق مفعّل</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Applications -->
    <div class="col-xl-3 col-md-6">
        <div class="card h-md-100 border-start border-4 border-primary shadow-sm">
            <div class="card-body p-6">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <span class="text-gray-600 fw-bold fs-6">إجمالي أوراق التقديم</span>
                    <span class="badge badge-light-primary px-3 py-2 fw-bold">الكلي</span>
                </div>
                <div class="d-flex align-items-baseline">
                    <span class="fs-2hx fw-bold text-gray-900 me-2">{{ $totalCount }}</span>
                    <span class="text-gray-500 fw-semibold fs-7">ملف تقديم</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Table Card -->
<div class="card shadow-sm">
    <div class="card-header pt-6 pb-4 border-0 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-4">
        <div class="d-flex align-items-center">
            <div class="bg-light-primary p-3 rounded-circle me-3">
                <i class="ki-duotone ki-id-card fs-2x text-primary">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </div>
            <div>
                <h3 class="text-gray-900 fw-bold m-0 fs-3">مركز مراجعة طلبات الانضمام والمستندات</h3>
                <span class="text-gray-500 fs-7">قم بفتح ملف السائق لمقارنة صور المستندات والرخص بالبيانات مباشرة قبل اتخاذ القرار</span>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.driver-applications.index', ['status' => 'pending']) }}" 
               class="btn btn-sm {{ request('status', 'pending') == 'pending' ? 'btn-warning text-white fw-bold' : 'btn-light-warning' }} px-4 py-2">
                <i class="ki-outline ki-hourglass me-1"></i> طلبات معلقة ({{ $pendingCount }})
            </a>
            <a href="{{ route('admin.driver-applications.index', ['status' => 'rejected']) }}" 
               class="btn btn-sm {{ request('status') == 'rejected' ? 'btn-danger text-white fw-bold' : 'btn-light-danger' }} px-4 py-2">
                <i class="ki-outline ki-cross-circle me-1"></i> طلبات مرفوضة ({{ $rejectedCount }})
            </a>
            <a href="{{ route('admin.driver-applications.index', ['status' => 'active']) }}" 
               class="btn btn-sm {{ request('status') == 'active' ? 'btn-success text-white fw-bold' : 'btn-light-success' }} px-4 py-2">
                <i class="ki-outline ki-check-circle me-1"></i> طلبات مقبولة ({{ $activeCount }})
            </a>
            <a href="{{ route('admin.driver-applications.index', ['status' => 'all']) }}" 
               class="btn btn-sm {{ request('status') == 'all' ? 'btn-primary text-white fw-bold' : 'btn-light' }} px-4 py-2">
                عرض جميع الملفات
            </a>
        </div>
    </div>

    <div class="card-body pt-0">
        <!-- Search Form -->
        <form action="{{ route('admin.driver-applications.index') }}" method="GET" class="mb-5 border border-dashed border-gray-300 p-4 rounded bg-light bg-opacity-30">
            <input type="hidden" name="status" value="{{ request('status', 'pending') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-6 col-12">
                    <label class="form-label fs-7 fw-bold text-gray-700">بحث باسم السائق، رقم الهاتف، أو رقم السيارة</label>
                    <div class="position-relative">
                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4 translate-middle-y top-50"></i>
                        <input type="text" name="search" class="form-control form-control-solid ps-12 fs-7" 
                               placeholder="أدخل اسم السائق أو الرقم القومي أو الهاتف..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3 col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm px-5 py-3"><i class="ki-outline ki-magnifier me-1"></i> بحث</button>
                    @if(request('search'))
                        <a href="{{ route('admin.driver-applications.index', ['status' => request('status', 'pending')]) }}" class="btn btn-light btn-sm px-5 py-3">إعادة ضبط</a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Applications Table -->
        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th>ID</th>
                        <th>السائق المتقدم</th>
                        <th>الهاتف / البريد</th>
                        <th>رقم السيارة / الخدمة</th>
                        <th class="text-center">حالة طلب الانضمام</th>
                        <th class="text-center">تاريخ التقديم</th>
                        <th class="text-end">الإجراء والمقارنة</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    @forelse ($rows as $item)
                    <tr>
                        <td class="fw-bold">#{{ $item->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-45px symbol-circle me-3">
                                    <img src="{{ asset($item->user->photo ?? 'assets/media/avatars/blank.png') }}" alt="{{ $item->user->full_name }}" style="object-fit: cover;">
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-900 fw-bold fs-6">{{ $item->user->full_name }}</span>
                                    <span class="text-muted fs-8">الرقم القومي: {{ $item->id_number ?? '-' }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-dark fs-7">{{ $item->user->phone_number ?? '-' }}</span>
                                <span class="text-muted fs-8">{{ $item->user->email }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="badge badge-light-info fs-8 w-fit mb-1">{{ $item->service->title ?? 'خدمة عامة' }}</span>
                                <span class="text-gray-800 fs-7 fw-bold"><i class="ki-outline ki-car me-1"></i>{{ $item->driver_cars->car_number ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            @if($item->status == 'pending')
                                <span class="badge bg-light-warning text-warning fw-bold px-3 py-2"><i class="ki-outline ki-hourglass text-warning me-1"></i> قيد المراجعة</span>
                            @elseif($item->status == 'rejected')
                                <span class="badge bg-light-danger text-danger fw-bold px-3 py-2" title="{{ $item->latest_rejection_reason ?? '' }}"><i class="ki-outline ki-cross-circle text-danger me-1"></i> مرفوض</span>
                            @elseif($item->status == 'active')
                                <span class="badge bg-light-success text-success fw-bold px-3 py-2"><i class="ki-outline ki-check-circle text-success me-1"></i> مقبول ومفعّل</span>
                            @else
                                <span class="badge bg-light-secondary text-dark fw-bold px-3 py-2">{{ $item->status }}</span>
                            @endif
                        </td>
                        <td class="text-center text-gray-500 fs-7">{{ $item->created_at->format('Y-m-d h:i A') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.driver-applications.show', $item->id) }}" class="btn btn-sm btn-primary px-4 py-2 fw-bold">
                                <i class="ki-outline ki-eye fs-5 me-1"></i> مراجعة ومقارنة المستندات
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-10">
                            <i class="ki-duotone ki-id-card fs-3x text-muted mb-3"><span class="path1"></span><span class="path2"></span></i>
                            <p class="text-gray-500 m-0">لا توجد طلبات انضمام في هذه القائمة حالياً.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-end mt-4">
            {{ $rows->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
