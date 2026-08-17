@extends('layouts.admin')

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted">{{ __('admin.drivers') ?? 'السائقون' }}</li>
    <span class="bullet bg-gray-300 w-5px h-2px"></span>
    <li class="breadcrumb-item text-dark">{{ __('admin.show_all') ?? 'عرض الكل' }}</li>
@endsection

@section('content')
@section('title', $pageTitle)

<!-- Top Alert Banner Directing to Dedicated Driver Onboarding Center -->
<div class="alert alert-primary d-flex align-items-center justify-content-between p-4 mb-6 shadow-sm rounded-3">
    <div class="d-flex align-items-center">
        <div class="bg-primary text-white p-3 rounded-circle me-3">
            <i class="ki-outline ki-id-card fs-2x text-white"></i>
        </div>
        <div>
            <h5 class="fw-bold text-gray-900 mb-1">مركز مراجعة وتدقيق طلبات انضمام السائقين والمستندات</h5>
            <span class="fs-7 text-gray-700">لمراجعة المستندات والأوراق ورخص التقديم الجديدة ومقارنة الصور بالبيانات مباشرة، استخدم مركز طلبات الانضمام المستقل.</span>
        </div>
    </div>
    <a href="{{ route('admin.driver-applications.index') }}" class="btn btn-primary fw-bold btn-sm px-4 py-3">
        <i class="ki-outline ki-eye fs-5 me-1"></i> الانتقال لمركز طلبات الانضمام ({{ $pendingDriversCount }} طلب معلق) ➔
    </a>
</div>

<!-- Modern Statistics Grid -->
<div class="row g-5 mb-8">
    <!-- Active Drivers -->
    <div class="col-xl-3 col-md-6">
        <div class="card h-md-100 border-start border-4 border-success shadow-sm">
            <div class="card-body p-6 d-flex flex-column justify-content-between">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <span class="text-gray-600 fw-bold fs-6">سائقون معتمدون ونشطون</span>
                    <span class="badge badge-light-success px-3 py-2 fw-bold">Active</span>
                </div>
                <div>
                    <div class="d-flex align-items-baseline mb-4">
                        <span class="fs-2hx fw-bold text-gray-900 me-2">{{ $activeDriversCount }}</span>
                        <span class="text-gray-500 fw-semibold fs-7">سائق مفعّل</span>
                    </div>
                    <a href="{{ route('admin.drivers.index', ['status' => 'active']) }}" class="btn btn-sm btn-light-success w-100 py-3">
                        <i class="ki-outline ki-check-circle fs-5 me-2"></i>
                        عرض السائقين النشطين
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Blocked Drivers -->
    <div class="col-xl-3 col-md-6">
        <div class="card h-md-100 border-start border-4 border-danger shadow-sm">
            <div class="card-body p-6 d-flex flex-column justify-content-between">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <span class="text-gray-600 fw-bold fs-6">حسابات محظورة من النظام</span>
                    <span class="badge badge-light-danger px-3 py-2 fw-bold">Blocked</span>
                </div>
                <div>
                    <div class="d-flex align-items-baseline mb-4">
                        <span class="fs-2hx fw-bold text-gray-900 me-2">{{ $blockedDriversCount }}</span>
                        <span class="text-gray-500 fw-semibold fs-7">حساب محظور</span>
                    </div>
                    <a href="{{ route('admin.drivers.index', ['status' => 'blocked']) }}" class="btn btn-sm btn-light-danger w-100 py-3">
                        <i class="ki-outline ki-ban fs-5 me-2"></i>
                        عرض الحسابات المحظورة
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Onboarding Applications (Points directly to Application Center) -->
    <div class="col-xl-3 col-md-6">
        <div class="card h-md-100 border-start border-4 border-warning shadow-sm">
            <div class="card-body p-6 d-flex flex-column justify-content-between">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <span class="text-gray-600 fw-bold fs-6">طلبات انضمام جديدة معلقة</span>
                    <span class="badge badge-light-warning px-3 py-2 fw-bold">Review Center</span>
                </div>
                <div>
                    <div class="d-flex align-items-baseline mb-4">
                        <span class="fs-2hx fw-bold text-gray-900 me-2">{{ $pendingDriversCount }}</span>
                        <span class="text-gray-500 fw-semibold fs-7">طلب جديد</span>
                    </div>
                    <a href="{{ route('admin.driver-applications.index', ['status' => 'pending']) }}" class="btn btn-sm btn-warning text-white fw-bold w-100 py-3">
                        <i class="ki-outline ki-hourglass fs-5 me-2"></i>
                        مراجعة طلبات الانضمام ➔
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- All Accounts -->
    <div class="col-xl-3 col-md-6">
        <div class="card h-md-100 border-start border-4 border-primary shadow-sm">
            <div class="card-body p-6 d-flex flex-column justify-content-between">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <span class="text-gray-600 fw-bold fs-6">إجمالي حسابات السائقين</span>
                    <span class="badge badge-light-primary px-3 py-2 fw-bold">Total</span>
                </div>
                <div>
                    <div class="d-flex align-items-baseline mb-4">
                        <span class="fs-2hx fw-bold text-gray-900 me-2">{{ $allDriversCount }}</span>
                        <span class="text-gray-500 fw-semibold fs-7">حساب كلي</span>
                    </div>
                    <a href="{{ route('admin.drivers.index') }}" class="btn btn-sm btn-light-primary w-100 py-3">
                        <i class="ki-outline ki-eye fs-5 me-2"></i>
                        عرض جميع الحسابات
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Drivers Table View -->
<div class="card shadow-sm">
    <div class="card-header pt-6 pb-4 border-0 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-4">
        <div class="d-flex align-items-center">
            <div class="bg-light-primary p-3 rounded-circle me-3">
                <i class="ki-duotone ki-user-square fs-2x text-primary">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </div>
            <div>
                <h3 class="text-gray-900 fw-bold m-0 fs-3">{{ $pageTitle }}</h3>
                <span class="text-gray-500 fs-7">إدارة بيانات وحسابات السائقين المعتمدين وتتبع حالاتهم</span>
            </div>
        </div>
        
        <!-- Filter Tabs -->
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.drivers.index') }}" 
               class="btn btn-sm {{ !request('status') && !request('is_vip') ? 'btn-primary' : 'btn-light' }} px-4 py-2">
                {{ __('admin.show_all') }}
            </a>
            <a href="{{ route('admin.drivers.index', ['status' => 'active']) }}" 
               class="btn btn-sm {{ request('status') == 'active' ? 'btn-success text-white' : 'btn-light-success' }} px-4 py-2">
                {{ __('admin.active') }} ({{ $activeDriversCount }})
            </a>
            <a href="{{ route('admin.drivers.index', ['is_vip' => '1']) }}" 
               class="btn btn-sm {{ request('is_vip') == '1' ? 'btn-warning text-dark fw-bold' : 'btn-light-warning' }} px-4 py-2" title="كباتن VIP فقط">
                ⭐ كباتن VIP ({{ $vipDriversCount ?? 0 }})
            </a>
            <a href="{{ route('admin.drivers.index', ['status' => 'blocked']) }}" 
               class="btn btn-sm {{ request('status') == 'blocked' ? 'btn-danger text-white' : 'btn-light-danger' }} px-4 py-2">
                {{ __('admin.blocked') }} ({{ $blockedDriversCount }})
            </a>
            <a href="{{ route('admin.driver-applications.index') }}" 
               class="btn btn-sm btn-outline-primary fw-bold px-4 py-2">
                <i class="ki-outline ki-id-card me-1"></i> مركز طلبات الانضمام ({{ $pendingDriversCount }}) ➔
            </a>
        </div>
    </div>

    <div class="card-body pt-0">
        <!-- Search & Filter Controls -->
        <form action="{{ route('admin.drivers.index') }}" method="GET" class="mb-5 border border-dashed border-gray-300 p-4 rounded bg-light bg-opacity-30">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="row g-3 align-items-end">
                <div class="col-md-4 col-12">
                    <label class="form-label fs-7 fw-bold text-gray-700">{{ __('admin.search_placeholder_driver') }}</label>
                    <div class="position-relative">
                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4 translate-middle-y top-50"></i>
                        <input type="text" name="search" class="form-control form-control-solid ps-12 fs-7" 
                               placeholder="{{ __('admin.search_placeholder_driver') }}" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3 col-12">
                    <label class="form-label fs-7 fw-bold text-gray-700">{{ __('admin.filter_by_service') }}</label>
                    <select name="service_id" class="form-select form-select-solid fs-7">
                        <option value="">{{ __('admin.all_services') }}</option>
                        @foreach($services as $id => $title)
                            <option value="{{ $id }}" {{ request('service_id') == $id ? 'selected' : '' }}>{{ $title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-12">
                    <label class="form-label fs-7 fw-bold text-gray-700">فئة VIP</label>
                    <select name="is_vip" class="form-select form-select-solid fs-7">
                        <option value="">جميع الفئات</option>
                        <option value="1" {{ request('is_vip') == '1' ? 'selected' : '' }}>كباتن VIP ⭐</option>
                        <option value="0" {{ request('is_vip') == '0' ? 'selected' : '' }}>كباتن عاديون</option>
                    </select>
                </div>
                <div class="col-md-3 col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm px-5 py-3">{{ __('admin.search_btn') }}</button>
                    @if(request('search') || request('service_id') || request('is_vip'))
                        <a href="{{ route('admin.drivers.index', request('status') ? ['status' => request('status')] : []) }}" class="btn btn-light btn-sm px-5 py-3">{{ __('admin.reset_filter') }}</a>
                    @endif
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th>ID</th>
                        <th>{{ __('global.name') }}</th>
                        <th>{{ __('app.email') }}</th>
                        <th>{{ __('global.phone') }}</th>
                        <th class="text-center">حالة الحساب</th>
                        <th class="text-center">{{ __('global.created_at') }}</th>
                        <th class="text-end">{{ __('global.action') }}</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    @forelse ($rows ?? [] as $item)
                    <tr>
                        <td class="fw-bold">#{{ $item->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-40px symbol-circle me-3">
                                    <img src="{{ asset($item->user->photo ?? 'assets/media/avatars/blank.png') }}" alt="{{ $item->user->full_name }}" style="object-fit: cover;">
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-900 fw-bold fs-6">{{ $item->user->full_name }}</span>
                                    <div class="d-flex flex-wrap gap-2 mt-1">
                                        <span class="badge badge-light-warning fs-9">Wallet: {{ number_format($item->user->wallet_amount ?? 0, 2) }} EGP</span>
                                        @if($item->driver_cars && $item->driver_cars->car_number)
                                            <span class="badge badge-light-success fs-9"><i class="ki-outline ki-car text-success fs-9 me-1"></i>{{ __('admin.car_number') }}: {{ $item->driver_cars->car_number }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $item->user->email }}</td>
                        <td class="fw-bold">{{ $item->user->phone_number ?? '-' }}</td>
                        <td class="text-center">
                            @if($item->status == 'active')
                                <span class="badge bg-light-success text-success fw-bold px-3 py-2"><i class="ki-outline ki-check-circle text-success me-1"></i> {{ __('app.active') }}</span>
                            @elseif($item->status == 'pending')
                                <span class="badge bg-light-warning text-warning fw-bold px-3 py-2"><i class="ki-outline ki-hourglass text-warning me-1"></i> طلب معلق</span>
                            @elseif($item->status == 'rejected')
                                <span class="badge bg-light-danger text-danger fw-bold px-3 py-2" title="{{ $item->latest_rejection_reason ?? '' }}"><i class="ki-outline ki-cross-circle text-danger me-1"></i> طلب مرفوض</span>
                            @elseif($item->status == 'blocked')
                                <span class="badge bg-danger text-white fw-bold px-3 py-2"><i class="ki-outline ki-ban text-white me-1"></i> {{ __('app.blocked') }}</span>
                            @else
                                <span class="badge bg-light-primary text-primary fw-bold px-3 py-2">{{ $item->status }}</span>
                            @endif
                        </td>
                        <td class="text-center text-gray-500 fs-7">{{ $item->created_at->format('M d, Y h:i A') }}</td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end align-items-center gap-2">
                                <!-- Onboarding Application Review Link (if pending or rejected) -->
                                @if($item->status == 'pending' || $item->status == 'rejected')
                                    <a href="{{ route('admin.driver-applications.show', $item->id) }}" class="btn btn-sm btn-light-warning fw-bold text-dark px-3 py-2" title="مراجعة ومقارنة مستندات ورخص التقديم">
                                        <i class="ki-outline ki-eye text-dark fs-5 me-1"></i> مراجعة طلب الانضمام ➔
                                    </a>
                                @else
                                    <!-- View General Driver Profile Details -->
                                    <a href="{{ route('admin.drivers.show', $item->id) }}" class="btn btn-sm btn-light-primary fw-bold px-3 py-2" title="عرض ملف السائق التفصيلي">
                                        <i class="ki-outline ki-user fs-5 me-1"></i> ملف السائق
                                    </a>
                                @endif

                                <!-- Account Block / Unblock Actions -->
                                @if($item->status == 'blocked')
                                    <form action="{{ route('admin.drivers.active', $item->id) }}" method="post" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" onclick="return confirm('هل أنت متأكد من فك الحظر عن حساب السائق؟')" class="btn btn-sm btn-success px-3 py-2" title="فك الحظر العام عن الحساب">
                                            <i class="ki-outline ki-check-circle fs-5 me-1"></i> فك الحظر
                                        </button>  
                                    </form>
                                @elseif($item->status == 'active')
                                    <form action="{{ route('admin.drivers.block', $item->id) }}" method="post" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" onclick="return confirm('هل أنت متأكد من حظر حساب السائق من استخدام التطبيق؟')" class="btn btn-sm btn-outline-danger px-3 py-2" title="حظر الحساب من التطبيق">
                                            <i class="ki-outline ki-ban fs-5 me-1"></i> حظر
                                        </button>  
                                    </form>
                                @endif

                                <!-- Delete -->
                                <form action="{{ route('admin.drivers.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('{{ __('global.delete_confirm') }}')" class="btn btn-sm btn-light-danger px-2 py-2" title="حذف">
                                        <i class="ki-outline ki-trash fs-5"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-10">
                            <i class="ki-duotone ki-profile-user fs-3x text-muted mb-3"><span class="path1"></span><span class="path2"></span></i>
                            <p class="text-gray-500 m-0">{{ __('admin.no_data') }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($rows instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
        <div class="d-flex justify-content-end mt-4">
            {{ $rows->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
