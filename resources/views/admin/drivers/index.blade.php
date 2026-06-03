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
               class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-light' }} px-4 py-2">
                {{ __('admin.show_all') }}
            </a>
            <a href="{{ route('admin.drivers.index', ['status' => 'active']) }}" 
               class="btn btn-sm {{ request('status') == 'active' ? 'btn-success text-white' : 'btn-light-success' }} px-4 py-2">
                {{ __('admin.active') }}
            </a>
            <a href="{{ route('admin.drivers.index', ['status' => 'pending']) }}" 
               class="btn btn-sm {{ request('status') == 'pending' ? 'btn-warning text-white' : 'btn-light-warning' }} px-4 py-2">
                {{ __('admin.pending') }}
            </a>
            <a href="{{ route('admin.drivers.index', ['status' => 'blocked']) }}" 
               class="btn btn-sm {{ request('status') == 'blocked' ? 'btn-danger text-white' : 'btn-light-danger' }} px-4 py-2">
                {{ __('admin.blocked') }}
            </a>
        </div>
    </div>

    <div class="card-body pt-0">
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
                                    <span class="text-gray-400 fs-8">Wallet: {{ number_format($item->user->wallet_amount ?? 0, 2) }} EGP</span>
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
                                @if($item->status == 'pending')
                                <form action="{{ route('admin.drivers.active', $item->id) }}" method="post" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" onclick="return confirm('{{ __('app.are_you_sure') }}')" class="btn-table-action btn-table-action-approve" title="{{ __('admin.approve') }}">
                                        <i class="ki-duotone ki-check fs-5"></i>
                                    </button>  
                                </form>
                                <form action="{{ route('admin.drivers.block', $item->id) }}" method="post" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" onclick="return confirm('{{ __('app.are_you_sure') }}')" class="btn-table-action btn-table-action-reject" title="{{ __('admin.reject') }}">
                                        <i class="ki-duotone ki-cross fs-5"><span class="path1"></span><span class="path2"></span></i>
                                    </button>  
                                </form>
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
