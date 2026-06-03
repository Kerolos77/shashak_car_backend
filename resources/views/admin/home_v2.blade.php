@extends('layouts.admin')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
    /* Styling tokens & overrides */
    :root {
        --dash-primary: #3B82F6;
        --dash-primary-glow: rgba(59, 130, 246, 0.15);
        --dash-success: #10B981;
        --dash-success-glow: rgba(16, 185, 129, 0.15);
        --dash-warning: #F59E0B;
        --dash-warning-glow: rgba(245, 158, 11, 0.15);
        --dash-info: #06B6D4;
        --dash-info-glow: rgba(6, 182, 212, 0.15);
        --dash-danger: #EF4444;
        --dash-danger-glow: rgba(239, 68, 68, 0.15);
        --dash-brand: #FF7E40;
        --dash-brand-dark: #FF5A1F;
        --card-radius: 20px;
    }

    body, h1, h2, h3, h4, h5, h6, .card, .menu, .btn, .table, .breadcrumb, .breadcrumb-item {
        font-family: {{ app()->getLocale() == 'ar' ? "'Cairo', sans-serif" : "'Outfit', sans-serif" }} !important;
        -webkit-font-smoothing: antialiased;
    }

    /* Page Fade-in Animation */
    .animate-fade-in {
        animation: pageFadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    @keyframes pageFadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Glassmorphism Dashboard Cards */
    .glass-card {
        background: rgba(255, 255, 255, 0.8) !important;
        backdrop-filter: blur(25px) !important;
        -webkit-backdrop-filter: blur(25px) !important;
        border: 1px solid rgba(255, 255, 255, 0.5) !important;
        box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.03) !important;
        border-radius: var(--card-radius) !important;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
        position: relative;
        overflow: hidden;
    }

    .glass-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 100%);
        pointer-events: none;
        z-index: 1;
    }

    .glass-card:hover {
        transform: translateY(-6px) !important;
        box-shadow: 0 25px 50px -15px rgba(0, 0, 0, 0.08) !important;
        border-color: rgba(255, 126, 64, 0.3) !important;
    }

    /* Glow circle decorative background */
    .glow-circle {
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

    /* Dark Mode Enhancements */
    [data-bs-theme="dark"] .glass-card {
        background: rgba(30, 30, 45, 0.5) !important;
        border: 1px solid rgba(255, 255, 255, 0.04) !important;
        box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.4) !important;
    }

    [data-bs-theme="dark"] .glass-card:hover {
        border-color: rgba(255, 126, 64, 0.35) !important;
        box-shadow: 0 25px 50px -15px rgba(0, 0, 0, 0.55) !important;
    }

    /* Premium Banner */
    .premium-welcome-banner {
        background: linear-gradient(135deg, var(--dash-brand) 0%, var(--dash-brand-dark) 100%) !important;
        border-radius: var(--card-radius) !important;
        box-shadow: 0 15px 35px rgba(255, 90, 31, 0.25) !important;
        border: none !important;
        overflow: hidden;
        position: relative;
    }
    
    .premium-welcome-banner::after {
        content: '';
        position: absolute;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255,255,255,0.18) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        top: -150px;
        right: -100px;
        pointer-events: none;
    }
    [dir="rtl"] .premium-welcome-banner::after {
        right: auto;
        left: -100px;
    }

    /* Quick Action Card */
    .quick-action-btn {
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        border: 1px solid rgba(255,255,255,0.3);
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.06);
        color: white;
    }
    .quick-action-btn:hover {
        background: white;
        color: var(--dash-brand-dark) !important;
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    /* Mini Progress Bar styling */
    .target-progress {
        height: 6px;
        background: rgba(0, 0, 0, 0.05);
        border-radius: 10px;
        overflow: hidden;
    }
    [data-bs-theme="dark"] .target-progress {
        background: rgba(255, 255, 255, 0.05);
    }

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

    /* Custom Tables Layout */
    .table-responsive::-webkit-scrollbar {
        height: 6px;
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }
    [data-bs-theme="dark"] .table-responsive::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.1);
    }
    
    .table-v2 th {
        font-weight: 700 !important;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        color: #6B7280 !important;
        padding: 14px 16px !important;
        background: rgba(0,0,0,0.01) !important;
    }
    [data-bs-theme="dark"] .table-v2 th {
        color: #9CA3AF !important;
        background: rgba(255,255,255,0.01) !important;
    }

    .table-v2 td {
        padding: 16px !important;
        vertical-align: middle !important;
        font-size: 0.9rem;
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

    /* Custom Avatar Circle with random background gradients */
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
        padding: 12px 24px;
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
    .glass-card:hover .icon-box-v3 {
        transform: scale(1.1) rotate(6deg);
    }
</style>
@endpush

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted">{{ __('admin.global.home') ?? 'الرئيسية' }}</li>
    <span class="bullet bg-gray-300 w-5px h-2px"></span>
    <li class="breadcrumb-item text-dark">{{ __('admin.system_overview') }}</li>
@endsection

@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid animate-fade-in">
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

        <!-- Today's Statistics Widgets with progress target -->
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

            <!-- New Users Today Widget -->
            <div class="col-xl-3 col-md-6">
                <div class="card glass-card h-md-100">
                    <div class="glow-circle glow-circle-primary"></div>
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
                            <div class="d-flex align-items-baseline mb-2">
                                <span class="fs-2hx fw-bold text-gray-900 me-2">{{ $todayUsers }}</span>
                                <span class="text-gray-500 fw-semibold fs-7">{{ __('admin.new_user') }}</span>
                            </div>
                            
                            <!-- Daily Goal Target Indicator -->
                            @php
                                $userTarget = 50; 
                                $userPercent = min(100, round(($todayUsers / $userTarget) * 100));
                            @endphp
                            <div class="d-flex justify-content-between align-items-center fs-8 text-gray-500 mb-1">
                                <span>{{ __('admin.daily_target') }} ({{ $userTarget }})</span>
                                <span class="fw-bold">{{ $userPercent }}%</span>
                            </div>
                            <div class="target-progress mb-3">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $userPercent }}%; height:100%" aria-valuenow="{{ $userPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>

                            <div class="separator separator-dashed my-3"></div>
                            <div class="text-gray-500 fs-7">
                                {{ __('admin.total') }}: <span class="fw-bold text-gray-800">{{ number_format($totalUsers) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- New Drivers Today Widget -->
            <div class="col-xl-3 col-md-6">
                <div class="card glass-card h-md-100">
                    <div class="glow-circle glow-circle-success"></div>
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
                            <div class="d-flex align-items-baseline mb-2">
                                <span class="fs-2hx fw-bold text-gray-900 me-2">{{ $todayDrivers }}</span>
                                <span class="text-gray-500 fw-semibold fs-7">{{ __('admin.new_driver') }}</span>
                            </div>
                            
                            <!-- Daily Goal Target Indicator -->
                            @php
                                $driverTarget = 15; 
                                $driverPercent = min(100, round(($todayDrivers / $driverTarget) * 100));
                            @endphp
                            <div class="d-flex justify-content-between align-items-center fs-8 text-gray-500 mb-1">
                                <span>{{ __('admin.daily_target') }} ({{ $driverTarget }})</span>
                                <span class="fw-bold">{{ $driverPercent }}%</span>
                            </div>
                            <div class="target-progress mb-3">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $driverPercent }}%; height:100%" aria-valuenow="{{ $driverPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>

                            <div class="separator separator-dashed my-3"></div>
                            <div class="text-gray-500 fs-7">
                                {{ __('admin.total') }}: <span class="fw-bold text-gray-800">{{ number_format($totalDrivers) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- New Orders Today Widget -->
            <div class="col-xl-3 col-md-6">
                <div class="card glass-card h-md-100">
                    <div class="glow-circle glow-circle-warning"></div>
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
                            <div class="d-flex align-items-baseline mb-2">
                                <span class="fs-2hx fw-bold text-gray-900 me-2">{{ $todayOrders }}</span>
                                <span class="text-gray-500 fw-semibold fs-7">{{ __('admin.new_order') }}</span>
                            </div>
                            
                            <!-- Daily Goal Target Indicator -->
                            @php
                                $orderTarget = 100; 
                                $orderPercent = min(100, round(($todayOrders / $orderTarget) * 100));
                            @endphp
                            <div class="d-flex justify-content-between align-items-center fs-8 text-gray-500 mb-1">
                                <span>{{ __('admin.daily_target') }} ({{ $orderTarget }})</span>
                                <span class="fw-bold">{{ $orderPercent }}%</span>
                            </div>
                            <div class="target-progress mb-3">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $orderPercent }}%; height:100%" aria-valuenow="{{ $orderPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>

                            <div class="separator separator-dashed my-3"></div>
                            <div class="text-gray-500 fs-7">
                                {{ __('admin.total') }}: <span class="fw-bold text-gray-800">{{ number_format($totalOrders) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue Today Widget -->
            <div class="col-xl-3 col-md-6">
                <div class="card glass-card h-md-100">
                    <div class="glow-circle glow-circle-info"></div>
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
                            <div class="d-flex align-items-baseline mb-2">
                                <span class="fs-2hx fw-bold text-gray-900 me-2">{{ number_format($todayIncome, 1) }}</span>
                                <span class="text-gray-500 fw-semibold fs-7">{{ __('admin.egp') }}</span>
                            </div>
                            
                            <!-- Daily Goal Target Indicator -->
                            @php
                                $incomeTarget = 5000; 
                                $incomePercent = min(100, round(($todayIncome / $incomeTarget) * 100));
                            @endphp
                            <div class="d-flex justify-content-between align-items-center fs-8 text-gray-500 mb-1">
                                <span>{{ __('admin.daily_target') }} ({{ $incomeTarget }} {{ __('admin.egp') }})</span>
                                <span class="fw-bold">{{ $incomePercent }}%</span>
                            </div>
                            <div class="target-progress mb-3">
                                <div class="progress-bar bg-info" role="progressbar" style="width: {{ $incomePercent }}%; height:100%" aria-valuenow="{{ $incomePercent }}" aria-valuemin="0" aria-valuemax="100"></div>
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

        <!-- Main Registration & Status Cards -->
        <div class="row g-5 mb-8">
            <div class="col-12">
                <h3 class="text-gray-800 fw-bold fs-3 mb-0">
                    {{ __('admin.main_statistics') }}
                </h3>
            </div>

            <!-- Users Growth -->
            <div class="col-xl-4 col-md-6">
                <div class="card glass-card h-md-100">
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
                <div class="card glass-card h-md-100">
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
                <div class="card glass-card h-md-100">
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

        <!-- Dynamic Interactivity Chart Widget -->
        <div class="row mb-8">
            <div class="col-12">
                <div class="card glass-card">
                    <div class="card-header pt-6 pb-2 border-0 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-4">
                        <div class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900 fs-3">
                                {{ __('admin.last_30_days_statistics') }}
                            </span>
                            <span class="text-gray-500 mt-1 fw-semibold fs-6">
                                {{ __('admin.daily_activity_trends') }}
                            </span>
                        </div>
                        <!-- Chart View Controls (Vanilla JS Dataset Switches) -->
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="chart-toggle-btn active" id="btn-chart-all">
                                {{ __('admin.all_stats') }}
                            </button>
                            <button type="button" class="chart-toggle-btn" id="btn-chart-revenue">
                                <span class="bullet bg-info w-8px h-8px me-2 rounded-circle"></span>{{ __('admin.chart_revenue') }}
                            </button>
                            <button type="button" class="chart-toggle-btn" id="btn-chart-orders">
                                <span class="bullet bg-warning w-8px h-8px me-2 rounded-circle"></span>{{ __('admin.chart_orders') }}
                            </button>
                            <button type="button" class="chart-toggle-btn" id="btn-chart-users">
                                <span class="bullet bg-primary w-8px h-8px me-2 rounded-circle"></span>{{ __('admin.chart_users_drivers') }}
                            </button>
                        </div>
                    </div>
                    <div class="card-body pt-4">
                        <div style="height: 400px; position: relative;">
                            <canvas id="myChart"></canvas>
                            <div id="chartFallback" style="display: none; text-align: center; padding: 60px; color: #6B7280;">
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
                <div class="card glass-card h-100">
                    <div class="card-header pt-6 border-0 d-flex align-items-center justify-content-between">
                        <h3 class="card-label fw-bold text-gray-900 fs-3 m-0">
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
                                    <table class="table table-v2 align-middle table-row-dashed fs-6 gy-5">
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
                                    <table class="table table-v2 align-middle table-row-dashed fs-6 gy-5">
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
                                    <table class="table table-v2 align-middle table-row-dashed fs-6 gy-5">
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

            <!-- Right Side: Financial Highlights & Stats Summary -->
            <div class="col-xl-4">
                <div class="card glass-card h-100">
                    <div class="card-header pt-6 border-0">
                        <h3 class="card-label fw-bold text-gray-900 fs-3">
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

                        <!-- System Counts Micro Cards -->
                        <h4 class="text-gray-800 fw-bold fs-6 mb-4">{{ __('admin.system_statistics') }}</h4>
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

@push('scripts')
<script>
$(document).ready(function() {
    console.log('Premium Chart script started');
    
    // Show loading indicator
    $('#chartFallback').show();
    
    // Load Chart.js dynamically from stable CDN
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.js';
    script.onload = function() {
        console.log('Chart.js loaded successfully');
        initializeInteractiveChart();
    };
    script.onerror = function() {
        console.error('Failed to load Chart.js');
        showFallbackMessage();
    };
    document.head.appendChild(script);
    
    function initializeInteractiveChart() {
        try {
            const chartData = @json($chartData);
            console.log('Loaded Chart Data:', chartData);
            
            // Format labels and datasets
            const labels = [];
            const usersData = [];
            const driversData = [];
            const ordersData = [];
            const incomeData = [];
            
            for (let i = 29; i >= 0; i--) {
                const date = new Date();
                date.setDate(date.getDate() - i);
                const day = date.getDate();
                const month = date.getMonth() + 1;
                labels.push(`${day}/${month}`);
                
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
            if (!canvas) {
                showFallbackMessage();
                return;
            }
            
            const ctx = canvas.getContext('2d');
            $('#chartFallback').hide();
            
            const isArabic = '{{ app()->getLocale() }}' === 'ar';
            const fontName = isArabic ? 'Cairo' : 'Outfit';
            
            // Create soft overlay fill gradients
            const gradUsers = ctx.createLinearGradient(0, 0, 0, 380);
            gradUsers.addColorStop(0, 'rgba(59, 130, 246, 0.28)');
            gradUsers.addColorStop(1, 'rgba(59, 130, 246, 0.00)');
            
            const gradDrivers = ctx.createLinearGradient(0, 0, 0, 380);
            gradDrivers.addColorStop(0, 'rgba(16, 185, 129, 0.28)');
            gradDrivers.addColorStop(1, 'rgba(16, 185, 129, 0.00)');

            const gradOrders = ctx.createLinearGradient(0, 0, 0, 380);
            gradOrders.addColorStop(0, 'rgba(245, 158, 11, 0.28)');
            gradOrders.addColorStop(1, 'rgba(245, 158, 11, 0.00)');

            const gradIncome = ctx.createLinearGradient(0, 0, 0, 380);
            gradIncome.addColorStop(0, 'rgba(6, 182, 212, 0.28)');
            gradIncome.addColorStop(1, 'rgba(6, 182, 212, 0.00)');
            
            // Configure Datasets
            const datasetUsers = {
                label: isArabic ? 'المستخدمون الجدد' : 'New Users',
                data: usersData,
                borderColor: '#3B82F6',
                backgroundColor: gradUsers,
                tension: 0.4,
                fill: true,
                pointRadius: 2,
                pointHoverRadius: 6,
                borderWidth: 3,
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
                pointHoverRadius: 6,
                borderWidth: 3,
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
                pointHoverRadius: 6,
                borderWidth: 3,
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
                pointHoverRadius: 6,
                borderWidth: 3,
                yAxisID: 'y1'
            };
            
            // Initialize Chart.js
            const activeChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [datasetUsers, datasetDrivers, datasetOrders, datasetIncome]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: isArabic ? 'العدد (مستخدمين / طلبات)' : 'Counts (Users/Drivers/Orders)',
                                color: '#9CA3AF',
                                font: { family: fontName, size: 12, weight: '700' }
                            },
                            ticks: {
                                color: '#9CA3AF',
                                font: { family: fontName, size: 11 }
                            },
                            grid: {
                                color: 'rgba(156, 163, 175, 0.06)'
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: isArabic ? 'الإيرادات (جنيه)' : 'Revenue (EGP)',
                                color: '#9CA3AF',
                                font: { family: fontName, size: 12, weight: '700' }
                            },
                            ticks: {
                                color: '#9CA3AF',
                                font: { family: fontName, size: 11 }
                            },
                            grid: {
                                drawOnChartArea: false, // only show grid lines for left axis
                            }
                        },
                        x: {
                            display: true,
                            ticks: {
                                color: '#9CA3AF',
                                font: { family: fontName, size: 11 }
                            },
                            grid: {
                                color: 'rgba(156, 163, 175, 0.06)'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 25,
                                color: '#6B7280',
                                font: { family: fontName, size: 12, weight: '600' }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.96)',
                            titleColor: '#FFFFFF',
                            bodyColor: '#FFFFFF',
                            titleFont: { family: fontName, size: 13, weight: '700' },
                            bodyFont: { family: fontName, size: 12 },
                            borderColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 1,
                            padding: 12,
                            cornerRadius: 12
                        }
                    }
                }
            });
            
            // Interactivity Handlers: Toggling Chart Datasets
            $('#btn-chart-all').click(function() {
                $('.chart-toggle-btn').removeClass('active');
                $(this).addClass('active');
                activeChart.data.datasets = [datasetUsers, datasetDrivers, datasetOrders, datasetIncome];
                activeChart.options.scales.y.display = true;
                activeChart.options.scales.y1.display = true;
                activeChart.update();
            });

            $('#btn-chart-revenue').click(function() {
                $('.chart-toggle-btn').removeClass('active');
                $(this).addClass('active');
                activeChart.data.datasets = [datasetIncome];
                activeChart.options.scales.y.display = false;
                activeChart.options.scales.y1.display = true;
                activeChart.update();
            });

            $('#btn-chart-orders').click(function() {
                $('.chart-toggle-btn').removeClass('active');
                $(this).addClass('active');
                activeChart.data.datasets = [datasetOrders];
                activeChart.options.scales.y.display = true;
                activeChart.options.scales.y1.display = false;
                activeChart.update();
            });

            $('#btn-chart-users').click(function() {
                $('.chart-toggle-btn').removeClass('active');
                $(this).addClass('active');
                activeChart.data.datasets = [datasetUsers, datasetDrivers];
                activeChart.options.scales.y.display = true;
                activeChart.options.scales.y1.display = false;
                activeChart.update();
            });
            
            console.log('Interactive Chart loaded successfully.');
        } catch (error) {
            console.error('Error during chart initialization:', error);
            showFallbackMessage();
        }
    }
    
    function showFallbackMessage() {
        const isArabic = '{{ app()->getLocale() }}' === 'ar';
        $('#chartFallback').html(`
            <i class="ki-duotone ki-warning-2 fs-3x text-danger mb-3">
                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
            </i>
            <p class="fs-5">${isArabic ? 'حدث خطأ أثناء تحميل الرسم البياني' : 'Error loading chart'}</p>
        `).show();
    }
});
</script>
@endpush
@endsection
