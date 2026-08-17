@extends('layouts.admin')

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted">{{ __('admin.drivers') ?? 'السائقون' }}</li>
    <span class="bullet bg-gray-300 w-5px h-2px"></span>
    <li class="breadcrumb-item text-dark">{{ __('admin.show_all') ?? 'عرض الكل' }}</li>
@endsection

@section('content')
@section('title', $pageTitle)

<!-- Modern V2 Statistics Grid -->
<div class="row g-5 mb-8">
    <!-- All Drivers -->
    <div class="col-xl-3 col-md-6">
        <div class="card h-md-100">
            <div class="card-body p-6 d-flex flex-column justify-content-between">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <span class="text-gray-500 fw-bold fs-6">{{ __('app.all_drivers') }}</span>
                    <span class="badge badge-light-primary px-3 py-2 fw-bold">Total</span>
                </div>
                <div>
                    <div class="d-flex align-items-baseline mb-4">
                        <span class="fs-2hx fw-bold text-gray-900 me-2">{{ $allDriversCount }}</span>
                        <span class="text-gray-500 fw-semibold fs-7">{{ __('admin.drivers') }}</span>
                    </div>
                    <a href="{{ route('admin.drivers.index') }}" class="btn btn-sm btn-light-primary w-100 py-3">
                        <i class="ki-outline ki-eye fs-5 me-2"></i>
                        {{ __('admin.show_all') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Drivers -->
    <div class="col-xl-3 col-md-6">
        <div class="card h-md-100">
            <div class="card-body p-6 d-flex flex-column justify-content-between">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <span class="text-gray-500 fw-bold fs-6">{{ __('app.pending_drivers') }}</span>
                    <span class="badge badge-light-warning px-3 py-2 fw-bold">Review</span>
                </div>
                <div>
                    <div class="d-flex align-items-baseline mb-4">
                        <span class="fs-2hx fw-bold text-gray-900 me-2">{{ $pendingDriversCount }}</span>
                        <span class="text-gray-500 fw-semibold fs-7">{{ __('admin.pending') }}</span>
                    </div>
                    <a href="{{ route('admin.drivers.index', ['status' => 'pending']) }}" class="btn btn-sm btn-light-warning w-100 py-3">
                        <i class="ki-outline ki-hourglass fs-5 me-2"></i>
                        {{ __('admin.view') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Drivers -->
    <div class="col-xl-3 col-md-6">
        <div class="card h-md-100">
            <div class="card-body p-6 d-flex flex-column justify-content-between">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <span class="text-gray-500 fw-bold fs-6">{{ __('app.active_drivers') }}</span>
                    <span class="badge badge-light-success px-3 py-2 fw-bold">Active</span>
                </div>
                <div>
                    <div class="d-flex align-items-baseline mb-4">
                        <span class="fs-2hx fw-bold text-gray-900 me-2">{{ $activeDriversCount }}</span>
                        <span class="text-gray-500 fw-semibold fs-7">{{ __('admin.active') }}</span>
                    </div>
                    <a href="{{ route('admin.drivers.index', ['status' => 'active']) }}" class="btn btn-sm btn-light-success w-100 py-3">
                        <i class="ki-outline ki-check-circle fs-5 me-2"></i>
                        {{ __('admin.view') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Blocked Drivers -->
    <div class="col-xl-3 col-md-6">
        <div class="card h-md-100">
            <div class="card-body p-6 d-flex flex-column justify-content-between">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <span class="text-gray-500 fw-bold fs-6">{{ __('app.blocked_drivers') }}</span>
                    <span class="badge badge-light-danger px-3 py-2 fw-bold">Suspended</span>
                </div>
                <div>
                    <div class="d-flex align-items-baseline mb-4">
                        <span class="fs-2hx fw-bold text-gray-900 me-2">{{ $blockedDriversCount }}</span>
                        <span class="text-gray-500 fw-semibold fs-7">{{ __('admin.blocked') }}</span>
                    </div>
                    <a href="{{ route('admin.drivers.index', ['status' => 'blocked']) }}" class="btn btn-sm btn-light-danger w-100 py-3">
                        <i class="ki-outline ki-cross-circle fs-5 me-2"></i>
                        {{ __('admin.view') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Drivers Table View V2 -->
<div class="card">
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
                <span class="text-gray-500 fs-7">
                    @if(request('status') === 'active')
                        {{ __('admin.active') }}
                    @elseif(request('status') === 'pending')
                        {{ __('admin.pending') }}
                    @elseif(request('status') === 'blocked')
                        {{ __('admin.blocked') }}
                    @else
                        {{ __('admin.show_all') }}
                    @endif
                </span>
            </div>
        </div>
        
        <!-- Filter Tabs -->
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.drivers.index') }}" 
               class="btn btn-sm {{ !request('status') && !request('is_vip') ? 'btn-primary' : 'btn-light' }} px-4 py-2">
                {{ __('admin.show_all') }}
            </a>
            <a href="{{ route('admin.drivers.index', ['is_vip' => '1']) }}" 
               class="btn btn-sm {{ request('is_vip') == '1' ? 'btn-warning text-dark fw-bold' : 'btn-light-warning' }} px-4 py-2" title="كباتن VIP فقط">
                ⭐ كباتن VIP ({{ $vipDriversCount ?? 0 }})
            </a>
            <a href="{{ route('admin.drivers.index', ['status' => 'active']) }}" 
               class="btn btn-sm {{ request('status') == 'active' ? 'btn-success text-white' : 'btn-light-success' }} px-4 py-2">
                {{ __('admin.active') }}
            </a>
            <a href="{{ route('admin.drivers.index', ['status' => 'pending']) }}" 
               class="btn btn-sm {{ request('status') == 'pending' ? 'btn-warning text-white' : 'btn-light-warning' }} px-4 py-2">
                {{ __('admin.pending') }}
            </a>
            <a href="{{ route('admin.drivers.index', ['status' => 'rejected']) }}" 
               class="btn btn-sm {{ request('status') == 'rejected' ? 'btn-danger text-white' : 'btn-light-danger' }} px-4 py-2">
                مرفوض ({{ $rejectedDriversCount ?? 0 }})
            </a>
            <a href="{{ route('admin.drivers.index', ['status' => 'blocked']) }}" 
               class="btn btn-sm {{ request('status') == 'blocked' ? 'btn-danger text-white' : 'btn-light-danger' }} px-4 py-2">
                {{ __('admin.blocked') }}
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
                        <th class="text-center">{{ __('global.status') }}</th>
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
                                <div class="avatar-initial purple me-3">
                                    {{ substr($item->user->full_name ?? 'D', 0, 1) }}
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
                                <span class="capsule-badge badge-soft-success">{{ __('app.active') }}</span>
                            @elseif($item->status == 'pending')
                                <span class="capsule-badge badge-soft-warning">{{ __('app.pending') }}</span>
                            @elseif($item->status == 'rejected')
                                <span class="capsule-badge badge-soft-danger" title="{{ $item->latest_rejection_reason ?? '' }}">مرفوض</span>
                            @elseif($item->status == 'blocked')
                                <span class="capsule-badge badge-soft-danger">{{ __('app.blocked') }}</span>
                            @else
                                <span class="capsule-badge badge-soft-primary">{{ $item->status }}</span>
                            @endif
                        </td>
                        <td class="text-center text-gray-500 fs-7">{{ $item->created_at->format('M d, Y h:i A') }}</td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <!-- View Details -->
                                <a href="{{ route('admin.drivers.show', $item->id) }}" class="btn-table-action btn-table-action-view" title="{{ __('admin.view') }}">
                                    <i class="ki-duotone ki-eye fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                </a>
                                
                                <!-- Status Toggle -->
                                @if($item->status == 'pending' || $item->status == 'rejected')
                                <form action="{{ route('admin.drivers.active', $item->id) }}" method="post" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" onclick="return confirm('{{ __('app.are_you_sure') }}')" class="btn-table-action btn-table-action-approve" title="{{ __('admin.approve') }}">
                                        <i class="ki-duotone ki-check fs-5"></i>
                                    </button>  
                                </form>
                                <button type="button" class="btn-table-action btn-table-action-reject" data-bs-toggle="modal" data-bs-target="#rejectModal_{{ $item->id }}" title="رفض الطلب مع إدخال السبب">
                                    <i class="ki-duotone ki-cross fs-5"><span class="path1"></span><span class="path2"></span></i>
                                </button>
                                @elseif($item->status == 'active')
                                <form action="{{ route('admin.drivers.block', $item->id) }}" method="post" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" onclick="return confirm('{{ __('app.are_you_sure') }}')" class="btn-table-action btn-table-action-reject" title="{{ __('admin.blocked') }}">
                                        <i class="ki-duotone ki-cross fs-5"><span class="path1"></span><span class="path2"></span></i>
                                    </button>  
                                </form>
                                @elseif($item->status == 'blocked')
                                <form action="{{ route('admin.drivers.active', $item->id) }}" method="post" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" onclick="return confirm('{{ __('app.are_you_sure') }}')" class="btn-table-action btn-table-action-approve" title="{{ __('admin.active') }}">
                                        <i class="ki-duotone ki-check fs-5"></i>
                                    </button>  
                                </form>
                                @endif

                                <!-- Delete -->
                                <form action="{{ route('admin.drivers.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('{{ __('global.delete_confirm') }}')" class="btn-table-action btn-table-action-reject" title="Delete">
                                        <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                    </button>
                                </form>

                                <!-- Modal: Reject Driver Registration -->
                                <div class="modal fade" id="rejectModal_{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <form action="{{ route('admin.drivers.reject', $item->id) }}" method="POST" class="modal-content text-start">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title fw-bold text-white"><i class="ki-outline ki-cross-circle text-white me-2"></i>رفض طلب تسجيل السائق ({{ $item->user->full_name ?? '' }})</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="alert alert-warning d-flex align-items-center p-3 mb-4">
                                                    <i class="ki-outline ki-information-5 fs-2x me-3 text-warning"></i>
                                                    <div class="fs-7 text-gray-800">
                                                        سيظهر سبب الرفض المكتوب هنا مباشرة في تطبيق الموبايل لدى السائق ليقوم بتصحيح المستندات وإعادة الإرسال.
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">سبب الرفض الموجه للسائق <span class="text-danger">*</span></label>
                                                    <textarea name="reason" rows="4" class="form-control" placeholder="مثال: صورة رخصة القيادة غير واضحة، يرجى إعادة رفع صورة واضحة للوجهين." required>{{ $item->latest_rejection_reason ?? '' }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                                                <button type="submit" class="btn btn-danger fw-bold">تأكيد رفض الطلب</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
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

