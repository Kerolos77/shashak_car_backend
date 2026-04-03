@extends('layouts.admin')

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> السائقون </li>
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
        <li class="breadcrumb-item text-dark">عرض الكل</li>
    @endsection

@section('content')
@section('title', $pageTitle)
@section('pageName', $pageTitle)

<!-- Statistics Cards with Modern Design -->
<div class="row g-5 mb-7">
    <!-- All Drivers Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-flush h-md-100">
            <div class="card-header">
                <div class="card-title">
                    <h2>{{ __('app.all_drivers') }}</h2>
                </div>
            </div>
            <div class="card-body pt-1">
                <div class="d-flex flex-column text-center">
                    <span class="text-gray-600 fw-semibold d-block mb-2">Total Registered</span>
                    <div class="d-flex align-items-center justify-content-center">
                        <span class="fs-2hx fw-bold text-primary me-2">{{ $allDriversCount }}</span>
                        <span class="badge badge-light-primary fs-base">
                            <i class="ki-duotone ki-profile-user fs-5 text-primary ms-n1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                    </div>
                    <div class="mt-5">
                        <a href="{{ route('admin.drivers.index') }}" class="btn btn-sm btn-light-primary d-flex align-items-center justify-content-center">
                            <i class="ki-outline ki-eye fs-5 me-2"></i>
                            <span>عرض الكل</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Drivers Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-flush h-md-100">
            <div class="card-header">
                <div class="card-title">
                    <h2>{{ __('app.pending_drivers') }}</h2>
                </div>
            </div>
            <div class="card-body pt-1">
                <div class="d-flex flex-column text-center">
                    <span class="text-gray-600 fw-semibold d-block mb-2">Awaiting Approval</span>
                    <div class="d-flex align-items-center justify-content-center">
                        <span class="fs-2hx fw-bold text-warning me-2">{{ $pendingDriversCount }}</span>
                        <span class="badge badge-light-warning fs-base">
                            <i class="ki-duotone ki-hourglass fs-5 text-warning ms-n1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                    </div>
                    <div class="mt-5">
                        <a href="{{ route('admin.drivers.index', ['status' => 'pending']) }}" class="btn btn-sm btn-light-warning d-flex align-items-center justify-content-center">
                            <i class="ki-outline ki-hourglass fs-5 me-2"></i>
                            <span>مراجعة</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Blocked Drivers Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-flush h-md-100">
            <div class="card-header">
                <div class="card-title">
                    <h2>{{ __('app.blocked_drivers') }}</h2>
                </div>
            </div>
            <div class="card-body pt-1">
                <div class="d-flex flex-column text-center">
                    <span class="text-gray-600 fw-semibold d-block mb-2">Suspended Accounts</span>
                    <div class="d-flex align-items-center justify-content-center">
                        <span class="fs-2hx fw-bold text-danger me-2">{{ $blockedDriversCount }}</span>
                        <span class="badge badge-light-danger fs-base">
                            <i class="ki-duotone ki-cross-circle fs-5 text-danger ms-n1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                    </div>
                    <div class="mt-5">
                        <a href="{{ route('admin.drivers.index', ['status' => 'blocked']) }}" class="btn btn-sm btn-light-danger d-flex align-items-center justify-content-center">
                            <i class="ki-outline ki-cross-circle fs-5 me-2"></i>
                            <span>إدارة</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Drivers Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-flush h-md-100">
            <div class="card-header">
                <div class="card-title">
                    <h2>{{ __('app.active_drivers') }}</h2>
                </div>
            </div>
            <div class="card-body pt-1">
                <div class="d-flex flex-column text-center">
                    <span class="text-gray-600 fw-semibold d-block mb-2">Verified & Active</span>
                    <div class="d-flex align-items-center justify-content-center">
                        <span class="fs-2hx fw-bold text-success me-2">{{ $activeDriversCount }}</span>
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-duotone ki-verify fs-5 text-success ms-n1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                    </div>
                    <div class="mt-5">
                        <a href="{{ route('admin.drivers.index', ['status' => 'active']) }}" class="btn btn-sm btn-light-success d-flex align-items-center justify-content-center">
                            <i class="ki-outline ki-check-circle fs-5 me-2"></i>
                            <span>عرض النشطين</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Drivers Table with Enhanced UI -->
<div class="card">
    <!-- Table Header -->

                <div class="card-header flex-wrap py-5">
                    <div class="row w-100 align-items-center">
                        <!-- Title -->
                        <div class="col-md-9 col-12 mb-3 mb-md-0">
                            <div class="d-flex align-items-center">
                                <i class="ki-duotone ki-user-square fs-2x text-primary me-3">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                <div>
                                    <h3 class="text-gray-800 m-0">{{ $pageTitle }}</h3>
                                    @if(request('status'))
                                        <span class="badge badge-light-{{ request('status') == 'active' ? 'success' : (request('status') == 'pending' ? 'warning' : 'danger') }} fs-8">
                                            {{ request('status') == 'active' ? 'نشط' : (request('status') == 'pending' ? 'في الانتظار' : 'محظور') }}
                                        </span>
                                    @else
                                        <span class="badge badge-light-primary fs-8">جميع السائقين</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Semester Select -->



                        <!-- Filter Buttons -->
                        <div class="col-md-3 col-12 text-md-end">
                            <div class="d-flex flex-wrap gap-2 justify-content-md-end mb-3">
                                <span class="text-muted fs-7 fw-semibold me-2 align-self-center">فلترة:</span>
                                <a href="{{ route('admin.drivers.index') }}" 
                                   class="btn {{ !request('status') ? 'btn-primary' : 'btn-light-primary' }} btn-sm d-flex align-items-center filter-btn {{ !request('status') ? 'active' : '' }}">
                                    <i class="ki-outline ki-profile-user fs-5 me-2"></i>
                                    <span>جميع السائقين</span>
                                    @if(!request('status'))
                                        <span class="badge badge-light ms-2">{{ $allDriversCount }}</span>
                                    @endif
                                    @if(!request('status'))
                                        <span class="position-absolute top-0 start-100 translate-middle p-2 bg-primary rounded-circle" style="width: 8px; height: 8px;"></span>
                                    @endif
                                </a>
                                <a href="{{ route('admin.drivers.index', ['status' => 'active']) }}" 
                                   class="btn {{ request('status') == 'active' ? 'btn-success' : 'btn-light-success' }} btn-sm d-flex align-items-center filter-btn {{ request('status') == 'active' ? 'active' : '' }}">
                                    <i class="ki-outline ki-check-circle fs-5 me-2"></i>
                                    <span>النشطين</span>
                                    @if(request('status') == 'active')
                                        <span class="badge badge-light ms-2">{{ $activeDriversCount }}</span>
                                    @endif
                                </a>
                                <a href="{{ route('admin.drivers.index', ['status' => 'pending']) }}" 
                                   class="btn {{ request('status') == 'pending' ? 'btn-warning' : 'btn-light-warning' }} btn-sm d-flex align-items-center filter-btn {{ request('status') == 'pending' ? 'active' : '' }}">
                                    <i class="ki-outline ki-hourglass fs-5 me-2"></i>
                                    <span>في الانتظار</span>
                                    @if(request('status') == 'pending')
                                        <span class="badge badge-light ms-2">{{ $pendingDriversCount }}</span>
                                    @endif
                                </a>
                                <a href="{{ route('admin.drivers.index', ['status' => 'blocked']) }}" 
                                   class="btn {{ request('status') == 'blocked' ? 'btn-danger' : 'btn-light-danger' }} btn-sm d-flex align-items-center filter-btn {{ request('status') == 'blocked' ? 'active' : '' }}">
                                    <i class="ki-outline ki-cross-circle fs-5 me-2"></i>
                                    <span>المحظورين</span>
                                    @if(request('status') == 'blocked')
                                        <span class="badge badge-light ms-2">{{ $blockedDriversCount }}</span>
                                    @endif
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            
           
    <!-- Table Body -->
    <div class="card-body pt-0">
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
            <thead>
                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-125px">ID</th>
                    <th class="min-w-125px">{{ __('global.name') }}</th>
                    <th class="min-w-125px">{{ __('app.email') }}</th>
                    <th class="min-w-125px">{{ __('global.phone') }}</th>
                    <th class="min-w-125px text-center">{{ __('global.status') }}</th>
                    <th class="min-w-125px text-center">{{ __('global.created_at') }}</th>
                    <th class="text-end min-w-100px">{{ __('global.action') }}</th>
                </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600">
                @forelse ($rows ?? [] as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                <div class="symbol-label">
                                    <img src="{{ asset('assets/media/avatars/blank.png') }}" alt="{{ $item->user->full_name }}" class="w-100" />
                                </div>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="text-gray-800 mb-1">{{ $item->user->full_name }}</span>
                            </div>
                        </div>
                    </td>
                    <td>{{ $item->user->email }}</td>
                    <td>
                        <span class="text-gray-800">{{ $item->user->phone_number ?? 'N/A' }}</span>
                    </td>
                    <td class="text-center">
                        @if($item->status == 'pending')
                        <span class="badge badge-light-warning py-3 px-4 fs-7">{{__('app.pending')}}</span>
                        @elseif($item->status == 'active')
                        <span class="badge badge-light-success py-3 px-4 fs-7">{{__('app.active')}}</span>
                        @elseif($item->status == 'blocked')
                        <span class="badge badge-light-danger py-3 px-4 fs-7">{{__('app.blocked')}}</span>
                        @else
                        <span class="badge badge-light-secondary py-3 px-4 fs-7">{{__('app.unknown')}}</span>
                        @endif
                    </td>
                    <td class="text-center">{{ $item->created_at->format('M d, Y h:i A') }}</td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end">
                            <!-- View Button -->
                            <a href="{{ route('admin.drivers.show', $item->id) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="View">
                                <i class="ki-duotone ki-eye fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </a>
                            
                            <!-- Status Toggle Buttons -->
                            @if($item->status == 'pending')
                            <form action="{{ route('admin.drivers.active', $item->id) }}" method="post" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" onclick="return confirm('{{ __('app.are_you_sure') }}')" class="btn btn-icon btn-bg-light btn-active-color-success btn-sm me-1" title="Approve">
                                    <i class="ki-duotone ki-check fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </button>  
                            </form>
                            <form action="{{ route('admin.drivers.block', $item->id) }}" method="post" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" onclick="return confirm('{{ __('app.are_you_sure') }}')" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm me-1" title="Reject">
                                    <i class="ki-duotone ki-cross fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </button>  
                            </form>
                            @elseif($item->status == 'active')
                            <form action="{{ route('admin.drivers.block', $item->id) }}" method="post" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" onclick="return confirm('{{ __('app.are_you_sure') }}')" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm me-1" title="Block">
                                    <i class="ki-duotone ki-cross fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </button>  
                            </form>
                            @elseif($item->status == 'blocked')
                            <form action="{{ route('admin.drivers.active', $item->id) }}" method="post" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" onclick="return confirm('{{ __('app.are_you_sure') }}')" class="btn btn-icon btn-bg-light btn-active-color-success btn-sm me-1" title="Unblock">
                                    <i class="ki-duotone ki-check fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </button>  
                            </form>
                            @endif
                            
                            <!-- Delete Button -->
                            <form action="{{ route('admin.drivers.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('{{ __('global.delete_confirm') }}')" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm" title="Delete">
                                    <i class="ki-duotone ki-trash fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                    </i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <div class="text-muted">No records found.</div>
                                </td>
                            </tr>
                        @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('styles')
<style>
.filter-btn {
    transition: all 0.3s ease;
    border-radius: 8px;
    font-weight: 600;
    position: relative;
    overflow: hidden;
}

.filter-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.filter-btn.active {
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    transform: translateY(-1px);
}

.filter-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.filter-btn:hover::before {
    left: 100%;
}

.badge-count {
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
}

.filter-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
</style>
@endpush
@endsection

