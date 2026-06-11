@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    /* Specific Home V2 styling */
    .glow-circle-home {
        position: absolute;
        width: 150px;
        height: 150px;
        border-radius: 50%;
        filter: blur(60px);
        opacity: 0.12;
        pointer-events: none;
        z-index: 0;
    }
    .glow-circle-primary { background-color: var(--dash-primary); top: -20px; right: -20px; }
    .glow-circle-success { background-color: var(--dash-success); top: -20px; right: -20px; }
    .glow-circle-warning { background-color: var(--dash-warning); top: -20px; right: -20px; }
    .glow-circle-info { background-color: var(--dash-info); top: -20px; right: -20px; }

    /* Interactive Toggle Buttons for Chart */
    .chart-toggle-btn {
        background: transparent;
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 30px;
        padding: 6px 16px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #4B5563;
        transition: all 0.3s ease;
    }
    [data-bs-theme="dark"] .chart-toggle-btn {
        border-color: rgba(255, 255, 255, 0.08);
        color: #9CA3AF;
    }
    .chart-toggle-btn.active {
        background: var(--dash-brand) !important;
        border-color: var(--dash-brand) !important;
        color: white !important;
        box-shadow: 0 4px 12px rgba(255, 126, 64, 0.25);
    }

    /* Soft Capsule Badges */
    .capsule-badge {
        padding: 6px 12px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .badge-soft-success { background: rgba(16, 185, 129, 0.1) !important; color: #10B981 !important; }
    .badge-soft-warning { background: rgba(245, 158, 11, 0.1) !important; color: #F59E0B !important; }
    .badge-soft-danger { background: rgba(239, 68, 68, 0.1) !important; color: #EF4444 !important; }
    .badge-soft-primary { background: rgba(59, 130, 246, 0.1) !important; color: #3B82F6 !important; }

    /* Custom Avatar Circle */
    .avatar-initial {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
        color: white;
        background: linear-gradient(135deg, #FF7E40 0%, #FF5A1F 100%);
        box-shadow: 0 4px 10px rgba(255, 90, 31, 0.15);
    }
    .avatar-initial.blue { background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%); box-shadow: 0 4px 10px rgba(59, 130, 246, 0.15); }
    .avatar-initial.green { background: linear-gradient(135deg, #10B981 0%, #047857 100%); box-shadow: 0 4px 10px rgba(16, 185, 129, 0.15); }
    .avatar-initial.purple { background: linear-gradient(135deg, #8B5CF6 0%, #6D28D9 100%); box-shadow: 0 4px 10px rgba(139, 92, 246, 0.15); }

    /* Micro action buttons in tables */
    .btn-table-action {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        border: none;
    }
    .btn-table-action-view { background: rgba(59, 130, 246, 0.08); color: #3B82F6; }
    .btn-table-action-view:hover { background: #3B82F6; color: white; transform: scale(1.1); }
    .btn-table-action-approve { background: rgba(16, 185, 129, 0.08); color: #10B981; }
    .btn-table-action-approve:hover { background: #10B981; color: white; transform: scale(1.1); }
    .btn-table-action-reject { background: rgba(239, 68, 68, 0.08); color: #EF4444; }
    .btn-table-action-reject:hover { background: #EF4444; color: white; transform: scale(1.1); }

    /* Live feed tabs header */
    .live-feed-tabs .nav-link {
        border: none !important;
        font-weight: 700;
        font-size: 0.95rem;
        color: #6B7280;
        padding: 10px 20px;
        border-radius: 30px;
        transition: all 0.3s ease;
    }
    .live-feed-tabs .nav-link.active {
        background: rgba(255, 126, 64, 0.08) !important;
        color: var(--dash-brand) !important;
    }
    [data-bs-theme="dark"] .live-feed-tabs .nav-link.active {
        background: rgba(255, 126, 64, 0.15) !important;
    }

    /* Icon Box Premium */
    .icon-box-v3 {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        transition: all 0.3s ease;
    }
    .card:hover .icon-box-v3 {
        transform: scale(1.1) rotate(6deg);
    }

    /* Map container style */
    #live-map {
        height: 380px;
        width: 100%;
        border-radius: 16px;
        z-index: 10;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
    [data-bs-theme="dark"] #live-map {
        border-color: rgba(255, 255, 255, 0.05);
        filter: grayscale(1) invert(0.9) hue-rotate(180deg);
    }
    
    .map-popup-avatar {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #FF7E40;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 11px;
    }
</style>
@endpush

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted">{{ __('admin.global.home') ?? 'الرئيسية' }}</li>
    <span class="bullet bg-gray-300 w-5px h-2px"></span>
    <li class="breadcrumb-item text-dark">{{ __('admin.system_overview') }}</li>
@endsection

@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        
        <!-- Premium Welcome Section -->
        <div class="row mb-8">
            <div class="col-12">
                <div class="card premium-welcome-banner">
                    <div class="card-body p-8 p-lg-10">
                        <div class="row align-items-center">
                            <div class="col-lg-8 mb-5 mb-lg-0">
                                <div class="d-flex align-items-center">
                                    <div class="me-6 bg-white bg-opacity-15 p-4 rounded-circle">
                                        <i class="ki-duotone ki-truck fs-3hx text-white">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </div>
                                    <div>
                                        <h1 class="text-white fw-bold fs-2hx mb-2">
                                            {{ __('admin.welcome_title') }}
                                        </h1>
                                        <p class="text-white opacity-85 fs-5 mb-0">
                                            {{ __('admin.welcome_subtitle') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 text-lg-end">
                                <div class="d-flex gap-3 justify-content-lg-end">
                                    <a href="{{ route('admin.drivers.index') }}" class="btn quick-action-btn py-3 px-5 fw-bold">
                                        <i class="ki-duotone ki-profile-user fs-5 me-2"><span class="path1"></span><span class="path2"></span></i>
                                        {{ __('admin.manage_drivers') }}
                                    </a>
                                    <a href="{{ route('admin.orders.index') }}" class="btn quick-action-btn py-3 px-5 fw-bold">
                                        <i class="ki-duotone ki-shopping-cart fs-5 me-2"><span class="path1"></span><span class="path2"></span></i>
                                        {{ __('admin.manage_orders') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Statistics Cards (Clean Layout, No Daily Targets) -->
        <div class="row g-5 mb-8">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="text-gray-800 fw-bold fs-3 mb-0">
                        {{ __('admin.today_statistics') }}
                    </h3>
                    <span class="badge badge-light-primary fw-semibold fs-7 px-3 py-2">
                        <i class="ki-duotone ki-calendar-8 fs-6 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>
                        {{ date('d-m-Y') }}
                    </span>
                </div>
            </div>

            <!-- New Users Today -->
            <div class="col-xl-3 col-md-6">
                <div class="card h-md-100 position-relative overflow-hidden">
                    <div class="glow-circle-home glow-circle-primary"></div>
                    <div class="card-body p-6 d-flex flex-column justify-content-between position-relative z-index-2">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <span class="text-gray-500 fw-bold fs-6">
                                {{ __('admin.new_users') }}
                            </span>
                            <div class="icon-box-v3" style="background: var(--dash-primary-glow); color: var(--dash-primary);">
                                <i class="ki-duotone ki-user fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex align-items-baseline mb-3">
                                <span class="fs-2hx fw-bold text-gray-900 me-2">{{ $todayUsers }}</span>
                                <span class="text-gray-500 fw-semibold fs-7">{{ __('admin.new_user') }}</span>
                            </div>
                            <div class="separator separator-dashed my-3"></div>
                            <div class="text-gray-500 fs-7">
                                {{ __('admin.total') }}: <span class="fw-bold text-gray-800">{{ number_format($totalUsers) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- New Drivers Today -->
            <div class="col-xl-3 col-md-6">
                <div class="card h-md-100 position-relative overflow-hidden">
                    <div class="glow-circle-home glow-circle-success"></div>
                    <div class="card-body p-6 d-flex flex-column justify-content-between position-relative z-index-2">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <span class="text-gray-500 fw-bold fs-6">
                                {{ __('admin.new_drivers') }}
                            </span>
                            <div class="icon-box-v3" style="background: var(--dash-success-glow); color: var(--dash-success);">
                                <i class="ki-duotone ki-profile-user fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex align-items-baseline mb-3">
                                <span class="fs-2hx fw-bold text-gray-900 me-2">{{ $todayDrivers }}</span>
                                <span class="text-gray-500 fw-semibold fs-7">{{ __('admin.new_driver') }}</span>
                            </div>
                            <div class="separator separator-dashed my-3"></div>
                            <div class="text-gray-500 fs-7">
                                {{ __('admin.total') }}: <span class="fw-bold text-gray-800">{{ number_format($totalDrivers) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- New Orders Today -->
            <div class="col-xl-3 col-md-6">
                <div class="card h-md-100 position-relative overflow-hidden">
                    <div class="glow-circle-home glow-circle-warning"></div>
                    <div class="card-body p-6 d-flex flex-column justify-content-between position-relative z-index-2">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <span class="text-gray-500 fw-bold fs-6">
                                {{ __('admin.new_orders') }}
                            </span>
                            <div class="icon-box-v3" style="background: var(--dash-warning-glow); color: var(--dash-warning);">
                                <i class="ki-duotone ki-shopping-cart fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex align-items-baseline mb-3">
                                <span class="fs-2hx fw-bold text-gray-900 me-2">{{ $todayOrders }}</span>
                                <span class="text-gray-500 fw-semibold fs-7">{{ __('admin.new_order') }}</span>
                            </div>
                            <div class="separator separator-dashed my-3"></div>
                            <div class="text-gray-500 fs-7">
                                {{ __('admin.total') }}: <span class="fw-bold text-gray-800">{{ number_format($totalOrders) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue Today -->
            <div class="col-xl-3 col-md-6">
                <div class="card h-md-100 position-relative overflow-hidden">
                    <div class="glow-circle-home glow-circle-info"></div>
                    <div class="card-body p-6 d-flex flex-column justify-content-between position-relative z-index-2">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <span class="text-gray-500 fw-bold fs-6">
                                {{ __('admin.today_revenue') }}
                            </span>
                            <div class="icon-box-v3" style="background: var(--dash-info-glow); color: var(--dash-info);">
                                <i class="ki-duotone ki-dollar fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex align-items-baseline mb-3">
                                <span class="fs-2hx fw-bold text-gray-900 me-2">{{ number_format($todayIncome, 1) }}</span>
                                <span class="text-gray-500 fw-semibold fs-7">{{ __('admin.egp') }}</span>
                            </div>
                            <div class="separator separator-dashed my-3"></div>
                            <div class="text-gray-500 fs-7">
                                {{ __('admin.total') }}: <span class="fw-bold text-gray-800">{{ number_format($totalIncome, 1) }} {{ __('admin.egp') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Stats Grid -->
        <div class="row g-5 mb-8">
            <!-- Users Statistics -->
            <div class="col-xl-4 col-md-6">
                <div class="card h-md-100">
                    <div class="card-body p-6 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <h2 class="text-gray-800 fw-bold fs-5 mb-0">
                                    {{ __('admin.users') }}
                                </h2>
                                <span class="badge badge-light-primary fw-semibold fs-8 px-2 py-1">{{ __('admin.total_users') }}</span>
                            </div>
                            <div class="d-flex align-items-baseline mb-4">
                                <div class="fs-2hx fw-bold text-gray-900 me-2">{{ number_format($totalUsers) }}</div>
                            </div>
                            <div class="row g-3 py-2 mb-4">
                                <div class="col-6">
                                    <div class="bg-light bg-opacity-40 rounded p-3 text-center border border-dashed">
                                        <span class="text-gray-500 fs-7 d-block mb-1">{{ __('admin.this_month') }}</span>
                                        <span class="fw-bold text-gray-800 fs-5">{{ $monthUsers }}</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light bg-opacity-40 rounded p-3 text-center border border-dashed">
                                        <span class="text-gray-500 fs-7 d-block mb-1">{{ __('admin.this_week') }}</span>
                                        <span class="fw-bold text-gray-800 fs-5">{{ $weekUsers }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($userGrowth != 0)
                        <div class="d-flex align-items-center">
                            <span class="badge {{ $userGrowth > 0 ? 'badge-light-success' : 'badge-light-danger' }} fs-7 py-1 px-3 me-2 fw-bold">
                                <i class="ki-duotone ki-arrow-{{ $userGrowth > 0 ? 'up' : 'down' }} fs-6 me-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                {{ abs($userGrowth) }}%
                            </span>
                            <span class="text-gray-500 fs-7">
                                {{ __('admin.monthly_growth') }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Drivers Status Grid -->
            <div class="col-xl-4 col-md-6">
                <div class="card h-md-100">
                    <div class="card-body p-6 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <h2 class="text-gray-800 fw-bold fs-5 mb-0">
                                    {{ __('admin.drivers') }}
                                </h2>
                                <span class="badge badge-light-success fw-semibold fs-8 px-2 py-1">{{ __('admin.total_drivers') }}</span>
                            </div>
                            <div class="d-flex align-items-baseline mb-4">
                                <div class="fs-2hx fw-bold text-gray-900 me-2">{{ number_format($totalDrivers) }}</div>
                            </div>
                            <div class="row g-3 py-2 mb-4">
                                <div class="col-4">
                                    <div class="bg-light-success bg-opacity-20 rounded p-3 text-center border border-success border-opacity-20">
                                        <span class="fw-bold text-success fs-5 d-block">{{ $activeDrivers }}</span>
                                        <span class="text-gray-500 fs-8">{{ __('admin.active') }}</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="bg-light-warning bg-opacity-20 rounded p-3 text-center border border-warning border-opacity-20">
                                        <span class="fw-bold text-warning fs-5 d-block">{{ $pendingDrivers }}</span>
                                        <span class="text-gray-500 fs-8">{{ __('admin.pending') }}</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="bg-light-danger bg-opacity-20 rounded p-3 text-center border border-danger border-opacity-20">
                                        <span class="fw-bold text-danger fs-5 d-block">{{ $blockedDrivers }}</span>
                                        <span class="text-gray-500 fs-8">{{ __('admin.blocked') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($driverGrowth != 0)
                        <div class="d-flex align-items-center">
                            <span class="badge {{ $driverGrowth > 0 ? 'badge-light-success' : 'badge-light-danger' }} fs-7 py-1 px-3 me-2 fw-bold">
                                <i class="ki-duotone ki-arrow-{{ $driverGrowth > 0 ? 'up' : 'down' }} fs-6 me-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                {{ abs($driverGrowth) }}%
                            </span>
                            <span class="text-gray-500 fs-7">
                                {{ __('admin.monthly_growth') }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Orders Status Grid -->
            <div class="col-xl-4 col-md-6">
                <div class="card h-md-100">
                    <div class="card-body p-6 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <h2 class="text-gray-800 fw-bold fs-5 mb-0">
                                    {{ __('admin.orders') }}
                                </h2>
                                <span class="badge badge-light-warning fw-semibold fs-8 px-2 py-1">{{ __('admin.total_orders') }}</span>
                            </div>
                            <div class="d-flex align-items-baseline mb-4">
                                <div class="fs-2hx fw-bold text-gray-900 me-2">{{ number_format($totalOrders) }}</div>
                            </div>
                            <div class="row g-3 py-2 mb-4">
                                <div class="col-4">
                                    <div class="bg-light-warning bg-opacity-20 rounded p-3 text-center border border-warning border-opacity-20">
                                        <span class="fw-bold text-warning fs-5 d-block">{{ $pendingOrders }}</span>
                                        <span class="text-gray-500 fs-8">{{ __('admin.pending') }}</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="bg-light-success bg-opacity-20 rounded p-3 text-center border border-success border-opacity-20">
                                        <span class="fw-bold text-success fs-5 d-block">{{ $completedOrders }}</span>
                                        <span class="text-gray-500 fs-8">{{ __('admin.completed') }}</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="bg-light-danger bg-opacity-20 rounded p-3 text-center border border-danger border-opacity-20">
                                        <span class="fw-bold text-danger fs-5 d-block">{{ $cancelledOrders }}</span>
                                        <span class="text-gray-500 fs-8">{{ __('admin.cancelled') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($orderGrowth != 0)
                        <div class="d-flex align-items-center">
                            <span class="badge {{ $orderGrowth > 0 ? 'badge-light-success' : 'badge-light-danger' }} fs-7 py-1 px-3 me-2 fw-bold">
                                <i class="ki-duotone ki-arrow-{{ $orderGrowth > 0 ? 'up' : 'down' }} fs-6 me-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                {{ abs($orderGrowth) }}%
                            </span>
                            <span class="text-gray-500 fs-7">
                                {{ __('admin.monthly_growth') }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Interactive Map Widget & Trends -->
        <div class="row g-8 mb-8">
            <!-- Map Container -->
            <div class="col-xl-6">
                <div class="card h-100">
                    <div class="card-header pt-6 pb-2 border-0">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900 fs-4">
                                {{ __('admin.live_tracking') }}
                            </span>
                            <span class="text-gray-500 mt-1 fw-semibold fs-7">
                                {{ __('admin.active_drivers_simulation') }}
                            </span>
                        </h3>
                        <div class="card-toolbar">
                            <span class="badge badge-light-success fw-bold px-3 py-2 d-flex align-items-center gap-2">
                                <span class="bullet bg-success w-6px h-6px rounded-circle animation-blink"></span>
                                {{ __('admin.simulation_active') }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body pt-2 position-relative">
                        <div id="live-map"></div>
                    </div>
                </div>
            </div>

            <!-- Trends line chart -->
            <div class="col-xl-6">
                <div class="card h-100">
                    <div class="card-header pt-6 pb-2 border-0 d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3">
                        <div class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900 fs-4">
                                {{ __('admin.last_30_days_statistics') }}
                            </span>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="chart-toggle-btn active" id="btn-chart-all">
                                {{ __('admin.all_stats') }}
                            </button>
                            <button type="button" class="chart-toggle-btn" id="btn-chart-revenue">
                                {{ __('admin.revenue') }}
                            </button>
                            <button type="button" class="chart-toggle-btn" id="btn-chart-orders">
                                {{ __('admin.orders') }}
                            </button>
                        </div>
                    </div>
                    <div class="card-body pt-4">
                        <div style="height: 310px; position: relative;">
                            <canvas id="myChart"></canvas>
                            <div id="chartFallback" style="display: none; text-align: center; padding: 40px; color: #6B7280;">
                                <i class="ki-duotone ki-chart-simple fs-3x text-muted mb-3"><span class="path1"></span><span class="path2"></span></i>
                                <p class="fs-5">{{ __('admin.loading_chart') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities Feed (Tabs & Custom Tables) & Financial Stats -->
        <div class="row g-8 mb-8">
            <!-- Left Side: Live Feed Tables -->
            <div class="col-xl-8">
                <div class="card h-100">
                    <div class="card-header pt-6 border-0 d-flex align-items-center justify-content-between">
                        <h3 class="card-label fw-bold text-gray-900 fs-4 m-0">
                            {{ __('admin.system_overview') }}
                        </h3>
                        <ul class="nav nav-pills live-feed-tabs border-0" id="liveFeedTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="orders-feed-tab" data-bs-toggle="pill" data-bs-target="#orders-feed" type="button" role="tab">
                                    {{ __('admin.recent_orders') }}
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="drivers-feed-tab" data-bs-toggle="pill" data-bs-target="#drivers-feed" type="button" role="tab">
                                    {{ __('admin.recent_drivers') }}
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="withdrawals-feed-tab" data-bs-toggle="pill" data-bs-target="#withdrawals-feed" type="button" role="tab">
                                    {{ __('admin.recent_withdrawals') }}
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body pt-3">
                        <div class="tab-content" id="liveFeedTabContent">
                            
                            <!-- Tab 1: Recent Orders -->
                            <div class="tab-pane fade show active" id="orders-feed" role="tabpanel">
                                @if($recentOrdersList && $recentOrdersList->count() > 0)
                                <div class="table-responsive">
                                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                                        <thead>
                                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                                <th>{{ __('admin.order_id') }}</th>
                                                <th>{{ __('admin.customer') }}</th>
                                                <th>{{ __('admin.driver_name') }}</th>
                                                <th>{{ __('admin.amount') }}</th>
                                                <th>{{ __('admin.status') }}</th>
                                                <th>{{ __('admin.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentOrdersList as $order)
                                            <tr>
                                                <td class="fw-bold">#{{ $order->id }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-initial blue me-3">
                                                            {{ substr($order->user->full_name ?? 'C', 0, 1) }}
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <span class="text-gray-900 fw-bold">{{ $order->user->full_name ?? 'Guest User' }}</span>
                                                            <span class="text-gray-500 fs-7">{{ $order->user->phone_number ?? '' }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($order->driver)
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-initial green me-3">
                                                                {{ substr($order->driver->full_name ?? 'D', 0, 1) }}
                                                            </div>
                                                            <div class="d-flex flex-column">
                                                                <span class="text-gray-900 fw-bold">{{ $order->driver->full_name }}</span>
                                                                <span class="text-gray-500 fs-7">{{ $order->driver->phone_number ?? '' }}</span>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <span class="text-gray-400 italic fs-7">-</span>
                                                    @endif
                                                </td>
                                                <td class="fw-bold text-gray-900">{{ number_format($order->offer_rate, 2) }} {{ __('admin.egp') }}</td>
                                                <td>
                                                    @if($order->status === 'completed')
                                                        <span class="capsule-badge badge-soft-success">{{ __('admin.completed') }}</span>
                                                    @elseif($order->status === 'pending')
                                                        <span class="capsule-badge badge-soft-warning">{{ __('admin.pending') }}</span>
                                                    @elseif($order->status === 'cancelled')
                                                        <span class="capsule-badge badge-soft-danger">{{ __('admin.cancelled') }}</span>
                                                    @else
                                                        <span class="capsule-badge badge-soft-primary">{{ $order->status }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-table-action btn-table-action-view" title="{{ __('admin.view') }}">
                                                        <i class="ki-duotone ki-eye fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <div class="text-center py-10">
                                    <i class="ki-duotone ki-basket fs-3x text-muted mb-3"><span class="path1"></span><span class="path2"></span></i>
                                    <p class="text-gray-500">{{ __('admin.no_data') }}</p>
                                </div>
                                @endif
                            </div>

                            <!-- Tab 2: Recent Drivers -->
                            <div class="tab-pane fade" id="drivers-feed" role="tabpanel">
                                @if($recentDriversList && $recentDriversList->count() > 0)
                                <div class="table-responsive">
                                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                                        <thead>
                                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                                <th>{{ __('admin.customer') }}</th>
                                                <th>{{ __('admin.phone') }}</th>
                                                <th>{{ __('admin.service') }}</th>
                                                <th>{{ __('admin.status') }}</th>
                                                <th>{{ __('admin.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentDriversList as $driver)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-initial purple me-3">
                                                            {{ substr($driver->user->full_name ?? 'D', 0, 1) }}
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <span class="text-gray-900 fw-bold">{{ $driver->user->full_name ?? 'Driver Application' }}</span>
                                                            <span class="text-gray-500 fs-7">{{ $driver->user->email ?? '' }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="fw-bold">{{ $driver->user->phone_number ?? '-' }}</td>
                                                <td>{{ $driver->service->name ?? '-' }}</td>
                                                <td>
                                                    @if($driver->status === 'active')
                                                        <span class="capsule-badge badge-soft-success">{{ __('admin.active') }}</span>
                                                    @elseif($driver->status === 'pending')
                                                        <span class="capsule-badge badge-soft-warning">{{ __('admin.pending') }}</span>
                                                    @elseif($driver->status === 'blocked')
                                                        <span class="capsule-badge badge-soft-danger">{{ __('admin.blocked') }}</span>
                                                    @else
                                                        <span class="capsule-badge badge-soft-primary">{{ $driver->status }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.drivers.show', $driver->id) }}" class="btn-table-action btn-table-action-view" title="{{ __('admin.view') }}">
                                                        <i class="ki-duotone ki-eye fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <div class="text-center py-10">
                                    <i class="ki-duotone ki-profile-user fs-3x text-muted mb-3"><span class="path1"></span><span class="path2"></span></i>
                                    <p class="text-gray-500">{{ __('admin.no_data') }}</p>
                                </div>
                                @endif
                            </div>

                            <!-- Tab 3: Pending Withdrawals -->
                            <div class="tab-pane fade" id="withdrawals-feed" role="tabpanel">
                                @if($recentWithdrawalsList && $recentWithdrawalsList->count() > 0)
                                <div class="table-responsive">
                                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                                        <thead>
                                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                                <th>{{ __('admin.customer') }}</th>
                                                <th>{{ __('admin.amount') }}</th>
                                                <th>{{ __('admin.status') }}</th>
                                                <th>{{ __('admin.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentWithdrawalsList as $withdraw)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-initial me-3">
                                                            {{ substr($withdraw->user->full_name ?? 'W', 0, 1) }}
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <span class="text-gray-900 fw-bold">{{ $withdraw->user->full_name ?? 'Driver' }}</span>
                                                            <span class="text-gray-500 fs-7">{{ $withdraw->user->phone_number ?? '' }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="fw-bold text-gray-900">{{ number_format($withdraw->amount, 2) }} {{ __('admin.egp') }}</td>
                                                <td>
                                                    @if($withdraw->status === 'accepted')
                                                        <span class="capsule-badge badge-soft-success">{{ __('admin.completed') }}</span>
                                                    @elseif($withdraw->status === 'pending')
                                                        <span class="capsule-badge badge-soft-warning">{{ __('admin.pending') }}</span>
                                                    @elseif($withdraw->status === 'rejected')
                                                        <span class="capsule-badge badge-soft-danger">{{ __('admin.cancelled') }}</span>
                                                    @else
                                                        <span class="capsule-badge badge-soft-primary">{{ $withdraw->status }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        @if($withdraw->status === 'pending')
                                                            <a href="{{ route('admin.payments.accept', $withdraw->id) }}" class="btn-table-action btn-table-action-approve" title="{{ __('admin.approve') }}">
                                                                <i class="ki-duotone ki-check fs-5"></i>
                                                            </a>
                                                            <a href="{{ route('admin.payments.reject', $withdraw->id) }}" class="btn-table-action btn-table-action-reject" title="{{ __('admin.reject') }}">
                                                                <i class="ki-duotone ki-cross fs-5"><span class="path1"></span><span class="path2"></span></i>
                                                            </a>
                                                        @else
                                                            <span class="text-gray-400 fs-8">-</span>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <div class="text-center py-10">
                                    <i class="ki-duotone ki-wallet fs-3x text-muted mb-3"><span class="path1"></span><span class="path2"></span></i>
                                    <p class="text-gray-500">{{ __('admin.no_data') }}</p>
                                </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Financial Highlights -->
            <div class="col-xl-4">
                <div class="card h-100">
                    <div class="card-header pt-6 border-0">
                        <h3 class="card-label fw-bold text-gray-900 fs-4">
                            {{ __('admin.financial_statistics') }}
                        </h3>
                    </div>
                    <div class="card-body pt-3">
                        
                        <!-- Financial List items -->
                        <div class="d-flex align-items-center mb-6">
                            <div class="symbol symbol-45px me-4">
                                <span class="symbol-label" style="background: var(--dash-info-glow); color: var(--dash-info);">
                                    <i class="ki-duotone ki-dollar fs-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </span>
                            </div>
                            <div class="d-flex flex-column flex-grow-1">
                                <span class="text-gray-500 fw-semibold fs-7">{{ __('admin.total_revenue') }}</span>
                                <span class="text-gray-900 fw-bold fs-5">{{ number_format($totalIncome, 2) }} {{ __('admin.egp') }}</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-6">
                            <div class="symbol symbol-45px me-4">
                                <span class="symbol-label" style="background: var(--dash-primary-glow); color: var(--dash-primary);">
                                    <i class="ki-duotone ki-briefcase fs-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </span>
                            </div>
                            <div class="d-flex flex-column flex-grow-1">
                                <span class="text-gray-500 fw-semibold fs-7">{{ __('admin.wallet_transactions') }}</span>
                                <span class="text-gray-900 fw-bold fs-5">{{ number_format($totalWalletTransactions) }}</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-6">
                            <div class="symbol symbol-45px me-4">
                                <span class="symbol-label" style="background: var(--dash-success-glow); color: var(--dash-success);">
                                    <i class="ki-duotone ki-barcode fs-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </span>
                            </div>
                            <div class="d-flex flex-column flex-grow-1">
                                <span class="text-gray-500 fw-semibold fs-7">{{ __('admin.payment_transactions') }}</span>
                                <span class="text-gray-900 fw-bold fs-5">{{ number_format($totalPaymentTransactions) }}</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-6">
                            <div class="symbol symbol-45px me-4">
                                <span class="symbol-label" style="background: var(--dash-warning-glow); color: var(--dash-warning);">
                                    <i class="ki-duotone ki-questionnaire-edit fs-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </span>
                            </div>
                            <div class="d-flex flex-column flex-grow-1">
                                <span class="text-gray-500 fw-semibold fs-7">{{ __('admin.withdrawal_requests') }}</span>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="text-gray-900 fw-bold fs-5">{{ number_format($totalWithdrawRequests) }}</span>
                                    <span class="badge badge-light-warning fw-bold fs-9 px-2 py-0.5">{{ $pendingWithdrawRequests }} {{ __('admin.pending') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="separator separator-dashed my-6"></div>

                        <!-- System Catalog stats collapsible/simplified -->
                        <div class="accordion accordion-solid accordion-toggle-arrow" id="catalog_stats_accordion">
                            <div class="accordion-item border-0">
                                <h2 class="accordion-header" id="headingCatalog">
                                    <button class="accordion-button fs-6 fw-bold text-gray-800 collapsed py-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCatalog" aria-expanded="false" aria-controls="collapseCatalog" style="background:transparent !important; border:none !important; box-shadow:none !important;">
                                        {{ __('admin.catalog_settings_counts') }}
                                    </button>
                                </h2>
                                <div id="collapseCatalog" class="accordion-collapse collapse" aria-labelledby="headingCatalog" data-bs-parent="#catalog_stats_accordion">
                                    <div class="accordion-body pt-0 text-gray-600">
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <div class="p-3 bg-light bg-opacity-40 rounded text-center border">
                                                    <span class="fs-4 fw-bold text-gray-900 d-block">{{ $totalServices }}</span>
                                                    <span class="text-gray-500 fs-8">{{ __('admin.services') }}</span>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="p-3 bg-light bg-opacity-40 rounded text-center border">
                                                    <span class="fs-4 fw-bold text-gray-900 d-block">{{ $totalFreightVehicles }}</span>
                                                    <span class="text-gray-500 fs-8">{{ __('admin.freight_vehicles') }}</span>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="p-3 bg-light bg-opacity-40 rounded text-center border">
                                                    <span class="fs-4 fw-bold text-gray-900 d-block">{{ $totalAdmins }}</span>
                                                    <span class="text-gray-500 fs-8">{{ __('admin.admins') }}</span>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="p-3 bg-light bg-opacity-40 rounded text-center border">
                                                    <span class="fs-4 fw-bold text-gray-900 d-block">{{ $totalAirports }}</span>
                                                    <span class="text-gray-500 fs-8">{{ __('admin.airports') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<!-- Leaflet Map JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
$(document).ready(function() {
    console.log('Cairo Simulation Map Loading...');
    
    // Cairo coordinates center
    const cairoCenter = [30.0444, 31.2357];
    
    // Initialize Leaflet Map
    const map = L.map('live-map').setView(cairoCenter, 11);
    
    // Load elegant map tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a> contributors'
    }).addTo(map);
    
    // Simulated drivers with initial positions and paths
    const drivers = [
        {
            name: "{{ __('admin.sim_driver_1') }}",
            phone: "01023456789",
            status: "{{ __('admin.sim_status_active') }}",
            lat: 30.0626,
            lng: 31.2497,
            deltaLat: 0.0003,
            deltaLng: 0.0002,
            marker: null
        },
        {
            name: "{{ __('admin.sim_driver_2') }}",
            phone: "01234567890",
            status: "{{ __('admin.sim_status_searching') }}",
            lat: 30.0131,
            lng: 31.2089,
            deltaLat: -0.0002,
            deltaLng: 0.0004,
            marker: null
        },
        {
            name: "{{ __('admin.sim_driver_3') }}",
            phone: "01545678901",
            status: "{{ __('admin.sim_status_delivering') }}",
            lat: 30.0771,
            lng: 31.3426,
            deltaLat: 0.0004,
            deltaLng: -0.0003,
            marker: null
        }
    ];

    // Create markers and popups for drivers
    drivers.forEach(function(driver) {
        const popupContent = `
            <div style="font-family: Cairo, sans-serif; text-align: center;">
                <div class="d-flex align-items-center gap-2 mb-2 justify-content-center">
                    <div class="map-popup-avatar">${driver.name.charAt(0)}</div>
                    <strong style="color: #2F3E46; font-size:12px;">${driver.name}</strong>
                </div>
                <span class="badge badge-light-success fs-9 py-1 px-2 mb-2 d-inline-block">${driver.status}</span>
                <div class="text-muted fs-8">${driver.phone}</div>
            </div>
        `;
        
        driver.marker = L.marker([driver.lat, driver.lng]).addTo(map);
        driver.marker.bindPopup(popupContent);
    });

    // Move drivers slowly to simulate live motion
    setInterval(function() {
        drivers.forEach(function(driver) {
            // Update coordinates
            driver.lat += driver.deltaLat;
            driver.lng += driver.deltaLng;
            
            // Boundary checking (Egypt/Cairo area loop)
            if (Math.abs(driver.lat - cairoCenter[0]) > 0.15) {
                driver.deltaLat = -driver.deltaLat;
            }
            if (Math.abs(driver.lng - cairoCenter[1]) > 0.20) {
                driver.deltaLng = -driver.deltaLng;
            }
            
            // Move marker smoothly
            driver.marker.setLatLng([driver.lat, driver.lng]);
        });
    }, 4000);

    // Load dynamic Chart.js
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.js';
    script.onload = function() {
        initializeChart();
    };
    document.head.appendChild(script);

    function initializeChart() {
        try {
            const chartData = @json($chartData);
            const labels = [];
            const usersData = [];
            const driversData = [];
            const ordersData = [];
            const incomeData = [];
            
            for (let i = 29; i >= 0; i--) {
                const date = new Date();
                date.setDate(date.getDate() - i);
                labels.push(`${date.getDate()}/${date.getMonth() + 1}`);
                
                if (chartData && chartData[29-i]) {
                    usersData.push(chartData[29-i].users || 0);
                    driversData.push(chartData[29-i].drivers || 0);
                    ordersData.push(chartData[29-i].orders || 0);
                    incomeData.push(chartData[29-i].income || 0);
                } else {
                    usersData.push(0);
                    driversData.push(0);
                    ordersData.push(0);
                    incomeData.push(0);
                }
            }

            const canvas = document.getElementById('myChart');
            if (!canvas) return;
            
            const ctx = canvas.getContext('2d');
            
            const isArabic = '{{ app()->getLocale() }}' === 'ar';
            const fontName = isArabic ? 'Cairo' : 'Outfit';
            
            const gradUsers = ctx.createLinearGradient(0, 0, 0, 280);
            gradUsers.addColorStop(0, 'rgba(59, 130, 246, 0.2)');
            gradUsers.addColorStop(1, 'rgba(59, 130, 246, 0.0)');
            
            const gradDrivers = ctx.createLinearGradient(0, 0, 0, 280);
            gradDrivers.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
            gradDrivers.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

            const gradOrders = ctx.createLinearGradient(0, 0, 0, 280);
            gradOrders.addColorStop(0, 'rgba(245, 158, 11, 0.2)');
            gradOrders.addColorStop(1, 'rgba(245, 158, 11, 0.0)');

            const gradIncome = ctx.createLinearGradient(0, 0, 0, 280);
            gradIncome.addColorStop(0, 'rgba(6, 182, 212, 0.2)');
            gradIncome.addColorStop(1, 'rgba(6, 182, 212, 0.0)');

            const datasetUsers = {
                label: isArabic ? 'المستخدمون الجدد' : 'New Users',
                data: usersData,
                borderColor: '#3B82F6',
                backgroundColor: gradUsers,
                tension: 0.4,
                fill: true,
                pointRadius: 2,
                borderWidth: 2,
                yAxisID: 'y'
            };
            
            const datasetDrivers = {
                label: isArabic ? 'السائقون الجدد' : 'New Drivers',
                data: driversData,
                borderColor: '#10B981',
                backgroundColor: gradDrivers,
                tension: 0.4,
                fill: true,
                pointRadius: 2,
                borderWidth: 2,
                yAxisID: 'y'
            };
            
            const datasetOrders = {
                label: isArabic ? 'الطلبات' : 'Orders',
                data: ordersData,
                borderColor: '#F59E0B',
                backgroundColor: gradOrders,
                tension: 0.4,
                fill: true,
                pointRadius: 2,
                borderWidth: 2,
                yAxisID: 'y'
            };
            
            const datasetIncome = {
                label: isArabic ? 'الإيرادات اليومية' : 'Daily Revenue',
                data: incomeData,
                borderColor: '#06B6D4',
                backgroundColor: gradIncome,
                tension: 0.4,
                fill: true,
                pointRadius: 2,
                borderWidth: 2,
                yAxisID: 'y1'
            };

            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [datasetUsers, datasetDrivers, datasetOrders, datasetIncome]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            ticks: { color: '#9CA3AF', font: { family: fontName, size: 10 } },
                            grid: { color: 'rgba(156, 163, 175, 0.04)' }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            ticks: { color: '#9CA3AF', font: { family: fontName, size: 10 } },
                            grid: { drawOnChartArea: false }
                        },
                        x: {
                            ticks: { color: '#9CA3AF', font: { family: fontName, size: 10 } },
                            grid: { color: 'rgba(156, 163, 175, 0.04)' }
                        }
                    },
                    plugins: {
                        legend: { labels: { color: '#6B7280', font: { family: fontName, size: 11 } } }
                    }
                }
            });

            // Toggles
            $('#btn-chart-all').click(function() {
                $('.chart-toggle-btn').removeClass('active');
                $(this).addClass('active');
                chart.data.datasets = [datasetUsers, datasetDrivers, datasetOrders, datasetIncome];
                chart.options.scales.y.display = true;
                chart.options.scales.y1.display = true;
                chart.update();
            });

            $('#btn-chart-revenue').click(function() {
                $('.chart-toggle-btn').removeClass('active');
                $(this).addClass('active');
                chart.data.datasets = [datasetIncome];
                chart.options.scales.y.display = false;
                chart.options.scales.y1.display = true;
                chart.update();
            });

            $('#btn-chart-orders').click(function() {
                $('.chart-toggle-btn').removeClass('active');
                $(this).addClass('active');
                chart.data.datasets = [datasetOrders];
                chart.options.scales.y.display = true;
                chart.options.scales.y1.display = false;
                chart.update();
            });
        } catch (e) {
            console.error(e);
        }
    }
});
</script>
@endpush
@endsection
