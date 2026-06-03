@extends('layouts.admin')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    /* Apply modern fonts dynamically based on locale */
    body, h1, h2, h3, h4, h5, h6, .card, .menu, .btn, .table, .breadcrumb, .breadcrumb-item {
        font-family: {{ app()->getLocale() == 'ar' ? "'Cairo', sans-serif" : "'Outfit', sans-serif" }} !important;
    }
    
    /* Modern Glassmorphic Dashboard Cards */
    .dashboard-v2-card {
        background: rgba(255, 255, 255, 0.7) !important;
        backdrop-filter: blur(20px) !important;
        -webkit-backdrop-filter: blur(20px) !important;
        border: 1px solid rgba(255, 255, 255, 0.45) !important;
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.04) !important;
        border-radius: 16px !important;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }
    
    .dashboard-v2-card:hover {
        transform: translateY(-5px) !important;
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.1) !important;
        border-color: rgba(255, 126, 64, 0.3) !important;
    }
    
    [data-bs-theme="dark"] .dashboard-v2-card {
        background: rgba(30, 30, 45, 0.45) !important;
        border: 1px solid rgba(255, 255, 255, 0.05) !important;
        box-shadow: 0 10px 35px -10px rgba(0, 0, 0, 0.3) !important;
    }
    
    [data-bs-theme="dark"] .dashboard-v2-card:hover {
        border-color: rgba(255, 126, 64, 0.4) !important;
        box-shadow: 0 20px 45px -15px rgba(0, 0, 0, 0.45) !important;
    }

    /* Premium Welcome Banner */
    .welcome-banner-v2 {
        background: linear-gradient(135deg, #FF7E40 0%, #FF5A1F 100%) !important;
        border-radius: 20px !important;
        box-shadow: 0 12px 30px rgba(255, 90, 31, 0.25) !important;
        border: none !important;
        overflow: hidden;
        position: relative;
    }

    .welcome-banner-v2::after {
        content: '';
        position: absolute;
        width: 320px;
        height: 320px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        top: -120px;
        right: -80px;
        pointer-events: none;
    }

    [dir="rtl"] .welcome-banner-v2::after {
        right: auto;
        left: -80px;
    }

    /* Icon Accent Backgrounds */
    .icon-box-v2 {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 52px;
        height: 52px;
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    
    .icon-box-primary { background: rgba(59, 130, 246, 0.1) !important; color: #3B82F6 !important; }
    .icon-box-success { background: rgba(16, 185, 129, 0.1) !important; color: #10B981 !important; }
    .icon-box-warning { background: rgba(245, 158, 11, 0.1) !important; color: #F59E0B !important; }
    .icon-box-info { background: rgba(6, 182, 212, 0.1) !important; color: #06B6D4 !important; }

    .dashboard-v2-card:hover .icon-box-v2 {
        transform: scale(1.1) rotate(5deg);
    }
</style>
@endpush

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted">{{ __('admin.global.home') ?? 'الرئيسية' }}</li>
    <span class="bullet bg-gray-300 w-5px h-2px"></span>
    <li class="breadcrumb-item text-dark">{{ __('admin.main_statistics') }}</li>
@endsection

@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        
        <!-- Welcome Section -->
        <div class="row mb-7">
            <div class="col-12">
                <div class="card welcome-banner-v2">
                    <div class="card-body d-flex align-items-center p-8">
                        <div class="d-flex align-items-center">
                            <i class="ki-duotone ki-truck fs-3x text-white me-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <div>
                                <h1 class="text-white fw-bold fs-1 mb-2">
                                    {{ __('admin.welcome_title') }}
                                </h1>
                                <p class="text-white opacity-90 fs-6 mb-0">
                                    {{ __('admin.welcome_subtitle') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Statistics -->
        <div class="row g-5 mb-7">
            <div class="col-12">
                <h3 class="text-gray-800 fw-bold fs-2 mb-2">
                    {{ __('admin.today_statistics') }}
                </h3>
            </div>
            
            <!-- Today New Users -->
            <div class="col-xl-3 col-md-6">
                <div class="card dashboard-v2-card h-md-100">
                    <div class="card-body p-6 d-flex flex-column justify-content-between">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h2 class="text-gray-600 fw-semibold fs-5 mb-0">
                                {{ __('admin.new_users') }}
                            </h2>
                            <div class="icon-box-v2 icon-box-primary">
                                <i class="ki-duotone ki-user fs-1 text-primary">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex align-items-baseline">
                                <span class="fs-2hx fw-bold text-primary me-2">{{ $todayUsers }}</span>
                                <span class="text-gray-500 fw-semibold fs-7">{{ __('admin.new_user') }}</span>
                            </div>
                            <div class="separator separator-dashed my-3"></div>
                            <div class="text-gray-500 fs-7">
                                {{ __('admin.total') }}: <span class="fw-bold text-gray-700">{{ number_format($totalUsers) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Today New Drivers -->
            <div class="col-xl-3 col-md-6">
                <div class="card dashboard-v2-card h-md-100">
                    <div class="card-body p-6 d-flex flex-column justify-content-between">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h2 class="text-gray-600 fw-semibold fs-5 mb-0">
                                {{ __('admin.new_drivers') }}
                            </h2>
                            <div class="icon-box-v2 icon-box-success">
                                <i class="ki-duotone ki-profile-user fs-1 text-success">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex align-items-baseline">
                                <span class="fs-2hx fw-bold text-success me-2">{{ $todayDrivers }}</span>
                                <span class="text-gray-500 fw-semibold fs-7">{{ __('admin.new_driver') }}</span>
                            </div>
                            <div class="separator separator-dashed my-3"></div>
                            <div class="text-gray-500 fs-7">
                                {{ __('admin.total') }}: <span class="fw-bold text-gray-700">{{ number_format($totalDrivers) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Today New Orders -->
            <div class="col-xl-3 col-md-6">
                <div class="card dashboard-v2-card h-md-100">
                    <div class="card-body p-6 d-flex flex-column justify-content-between">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h2 class="text-gray-600 fw-semibold fs-5 mb-0">
                                {{ __('admin.new_orders') }}
                            </h2>
                            <div class="icon-box-v2 icon-box-warning">
                                <i class="ki-duotone ki-shopping-cart fs-1 text-warning">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex align-items-baseline">
                                <span class="fs-2hx fw-bold text-warning me-2">{{ $todayOrders }}</span>
                                <span class="text-gray-500 fw-semibold fs-7">{{ __('admin.new_order') }}</span>
                            </div>
                            <div class="separator separator-dashed my-3"></div>
                            <div class="text-gray-500 fs-7">
                                {{ __('admin.total') }}: <span class="fw-bold text-gray-700">{{ number_format($totalOrders) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Today Revenue -->
            <div class="col-xl-3 col-md-6">
                <div class="card dashboard-v2-card h-md-100">
                    <div class="card-body p-6 d-flex flex-column justify-content-between">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h2 class="text-gray-600 fw-semibold fs-5 mb-0">
                                {{ __('admin.today_revenue') }}
                            </h2>
                            <div class="icon-box-v2 icon-box-info">
                                <i class="ki-duotone ki-dollar fs-1 text-info">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex align-items-baseline">
                                <span class="fs-2hx fw-bold text-info me-2">{{ number_format($todayIncome, 2) }}</span>
                                <span class="text-gray-500 fw-semibold fs-7">{{ __('admin.egp') }}</span>
                            </div>
                            <div class="separator separator-dashed my-3"></div>
                            <div class="text-gray-500 fs-7">
                                {{ __('admin.total') }}: <span class="fw-bold text-gray-700">{{ number_format($totalIncome, 2) }} {{ __('admin.egp') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Statistics Cards -->
        <div class="row g-5 mb-7">
            <div class="col-12">
                <h3 class="text-gray-800 fw-bold fs-2 mb-2">
                    {{ __('admin.main_statistics') }}
                </h3>
            </div>
            
            <!-- Users Statistics -->
            <div class="col-xl-4 col-md-6">
                <div class="card dashboard-v2-card h-md-100">
                    <div class="card-body p-6">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h2 class="text-gray-800 fw-bold fs-4 mb-0">
                                {{ __('admin.users') }}
                            </h2>
                            <div class="icon-box-v2 icon-box-primary">
                                <i class="ki-duotone ki-user fs-1 text-primary">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="fs-2hx fw-bold text-gray-900 me-2">{{ number_format($totalUsers) }}</div>
                            <div class="text-gray-500 fw-semibold fs-6">
                                {{ __('admin.total_users') }}
                            </div>
                        </div>
                        <div class="row g-3 py-2">
                            <div class="col-6">
                                <div class="bg-light bg-opacity-50 rounded p-3 text-center">
                                    <span class="text-gray-500 fs-7 d-block mb-1">{{ __('admin.this_month') }}</span>
                                    <span class="fw-bold text-gray-800 fs-6">{{ $monthUsers }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light bg-opacity-50 rounded p-3 text-center">
                                    <span class="text-gray-500 fs-7 d-block mb-1">{{ __('admin.this_week') }}</span>
                                    <span class="fw-bold text-gray-800 fs-6">{{ $weekUsers }}</span>
                                </div>
                            </div>
                        </div>
                        @if($userGrowth != 0)
                        <div class="d-flex align-items-center mt-4">
                            <span class="badge {{ $userGrowth > 0 ? 'badge-light-success' : 'badge-light-danger' }} fs-8 py-1 px-2 me-2">
                                <i class="ki-duotone ki-arrow-{{ $userGrowth > 0 ? 'up' : 'down' }} fs-7 me-1">
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

            <!-- Drivers Statistics -->
            <div class="col-xl-4 col-md-6">
                <div class="card dashboard-v2-card h-md-100">
                    <div class="card-body p-6">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h2 class="text-gray-800 fw-bold fs-4 mb-0">
                                {{ __('admin.drivers') }}
                            </h2>
                            <div class="icon-box-v2 icon-box-success">
                                <i class="ki-duotone ki-profile-user fs-1 text-success">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="fs-2hx fw-bold text-gray-900 me-2">{{ number_format($totalDrivers) }}</div>
                            <div class="text-gray-500 fw-semibold fs-6">
                                {{ __('admin.total_drivers') }}
                            </div>
                        </div>
                        <div class="row g-3 py-2">
                            <div class="col-4">
                                <div class="bg-light-success bg-opacity-30 rounded p-2 text-center">
                                    <span class="fw-bold text-success fs-5 d-block">{{ $activeDrivers }}</span>
                                    <span class="text-gray-500 fs-8">{{ __('admin.active') }}</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-light-warning bg-opacity-30 rounded p-2 text-center">
                                    <span class="fw-bold text-warning fs-5 d-block">{{ $pendingDrivers }}</span>
                                    <span class="text-gray-500 fs-8">{{ __('admin.pending') }}</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-light-danger bg-opacity-30 rounded p-2 text-center">
                                    <span class="fw-bold text-danger fs-5 d-block">{{ $blockedDrivers }}</span>
                                    <span class="text-gray-500 fs-8">{{ __('admin.blocked') }}</span>
                                </div>
                            </div>
                        </div>
                        @if($driverGrowth != 0)
                        <div class="d-flex align-items-center mt-4">
                            <span class="badge {{ $driverGrowth > 0 ? 'badge-light-success' : 'badge-light-danger' }} fs-8 py-1 px-2 me-2">
                                <i class="ki-duotone ki-arrow-{{ $driverGrowth > 0 ? 'up' : 'down' }} fs-7 me-1">
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

            <!-- Orders Statistics -->
            <div class="col-xl-4 col-md-6">
                <div class="card dashboard-v2-card h-md-100">
                    <div class="card-body p-6">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h2 class="text-gray-800 fw-bold fs-4 mb-0">
                                {{ __('admin.orders') }}
                            </h2>
                            <div class="icon-box-v2 icon-box-warning">
                                <i class="ki-duotone ki-shopping-cart fs-1 text-warning">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="fs-2hx fw-bold text-gray-900 me-2">{{ number_format($totalOrders) }}</div>
                            <div class="text-gray-500 fw-semibold fs-6">
                                {{ __('admin.total_orders') }}
                            </div>
                        </div>
                        <div class="row g-3 py-2">
                            <div class="col-4">
                                <div class="bg-light bg-opacity-50 rounded p-2 text-center">
                                    <span class="fw-bold text-warning fs-5 d-block">{{ $pendingOrders }}</span>
                                    <span class="text-gray-500 fs-8">{{ __('admin.pending') }}</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-light-success bg-opacity-30 rounded p-2 text-center">
                                    <span class="fw-bold text-success fs-5 d-block">{{ $completedOrders }}</span>
                                    <span class="text-gray-500 fs-8">{{ __('admin.completed') }}</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-light-danger bg-opacity-30 rounded p-2 text-center">
                                    <span class="fw-bold text-danger fs-5 d-block">{{ $cancelledOrders }}</span>
                                    <span class="text-gray-500 fs-8">{{ __('admin.cancelled') }}</span>
                                </div>
                            </div>
                        </div>
                        @if($orderGrowth != 0)
                        <div class="d-flex align-items-center mt-4">
                            <span class="badge {{ $orderGrowth > 0 ? 'badge-light-success' : 'badge-light-danger' }} fs-8 py-1 px-2 me-2">
                                <i class="ki-duotone ki-arrow-{{ $orderGrowth > 0 ? 'up' : 'down' }} fs-7 me-1">
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

        <!-- Financial Statistics -->
        <div class="row g-5 mb-7">
            <div class="col-12">
                <h3 class="text-gray-800 fw-bold fs-2 mb-2">
                    {{ __('admin.financial_statistics') }}
                </h3>
            </div>
            
            <!-- Total Revenue -->
            <div class="col-xl-3 col-md-6">
                <div class="card dashboard-v2-card h-md-100">
                    <div class="card-body p-6">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h2 class="text-gray-700 fw-semibold fs-5 mb-0">
                                {{ __('admin.total_revenue') }}
                            </h2>
                            <div class="icon-box-v2 icon-box-info">
                                <i class="ki-duotone ki-dollar fs-1 text-info">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="fs-2hx fw-bold text-gray-900 me-2">{{ number_format($totalIncome, 2) }}</div>
                            <div class="text-gray-500 fw-semibold fs-7">{{ __('admin.egp') }}</div>
                        </div>
                        <div class="row g-3 py-1">
                            <div class="col-6">
                                <span class="text-gray-500 fs-8 d-block">{{ __('admin.this_month') }}</span>
                                <span class="fw-bold text-gray-700 fs-7">{{ number_format($monthIncome, 2) }} {{ __('admin.egp') }}</span>
                            </div>
                            <div class="col-6">
                                <span class="text-gray-500 fs-8 d-block">{{ __('admin.this_week') }}</span>
                                <span class="fw-bold text-gray-700 fs-7">{{ number_format($weekIncome, 2) }} {{ __('admin.egp') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Wallet Transactions -->
            <div class="col-xl-3 col-md-6">
                <div class="card dashboard-v2-card h-md-100">
                    <div class="card-body p-6">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h2 class="text-gray-700 fw-semibold fs-5 mb-0">
                                {{ __('admin.wallet_transactions') }}
                            </h2>
                            <div class="icon-box-v2 icon-box-primary">
                                <i class="ki-duotone ki-briefcase fs-1 text-primary">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="fs-2hx fw-bold text-gray-900 me-2">{{ number_format($totalWalletTransactions) }}</div>
                            <div class="text-gray-500 fw-semibold fs-7">{{ __('admin.transaction') }}</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Payment Transactions -->
            <div class="col-xl-3 col-md-6">
                <div class="card dashboard-v2-card h-md-100">
                    <div class="card-body p-6">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h2 class="text-gray-700 fw-semibold fs-5 mb-0">
                                {{ __('admin.payment_transactions') }}
                            </h2>
                            <div class="icon-box-v2 icon-box-success">
                                <i class="ki-duotone ki-dollar fs-1 text-success">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="fs-2hx fw-bold text-gray-900 me-2">{{ number_format($totalPaymentTransactions) }}</div>
                            <div class="text-gray-500 fw-semibold fs-7">{{ __('admin.transaction') }}</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Withdrawal Requests -->
            <div class="col-xl-3 col-md-6">
                <div class="card dashboard-v2-card h-md-100">
                    <div class="card-body p-6">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h2 class="text-gray-700 fw-semibold fs-5 mb-0">
                                {{ __('admin.withdrawal_requests') }}
                            </h2>
                            <div class="icon-box-v2 icon-box-warning">
                                <i class="ki-duotone ki-questionnaire-edit fs-1 text-warning">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="fs-2hx fw-bold text-gray-900 me-2">{{ number_format($totalWithdrawRequests) }}</div>
                            <div class="text-gray-500 fw-semibold fs-7">{{ __('admin.request') }}</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge badge-light-warning fs-8 py-1 px-2">
                                {{ $pendingWithdrawRequests }} {{ __('admin.pending') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Statistics -->
        <div class="row g-5 mb-7">
            <div class="col-12">
                <h3 class="text-gray-800 fw-bold fs-2 mb-2">
                    {{ __('admin.system_statistics') }}
                </h3>
            </div>
            
            <!-- Services -->
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card dashboard-v2-card h-md-100">
                    <div class="card-body p-5 text-center d-flex flex-column align-items-center justify-content-center">
                        <div class="icon-box-v2 icon-box-primary mb-3">
                            <i class="ki-duotone ki-briefcase fs-1 text-primary">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                        <div class="fs-2hx fw-bold text-gray-900 mb-1">{{ number_format($totalServices) }}</div>
                        <div class="text-gray-500 fw-semibold fs-6">
                            {{ __('admin.services') }}
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Freight Vehicles -->
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card dashboard-v2-card h-md-100">
                    <div class="card-body p-5 text-center d-flex flex-column align-items-center justify-content-center">
                        <div class="icon-box-v2 icon-box-success mb-3">
                            <i class="ki-duotone ki-truck fs-1 text-success">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                        <div class="fs-2hx fw-bold text-gray-900 mb-1">{{ number_format($totalFreightVehicles) }}</div>
                        <div class="text-gray-500 fw-semibold fs-6">
                            {{ __('admin.freight_vehicles') }}
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Admins -->
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card dashboard-v2-card h-md-100">
                    <div class="card-body p-5 text-center d-flex flex-column align-items-center justify-content-center">
                        <div class="icon-box-v2 icon-box-info mb-3">
                            <i class="ki-duotone ki-user-square fs-1 text-info">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                        <div class="fs-2hx fw-bold text-gray-900 mb-1">{{ number_format($totalAdmins) }}</div>
                        <div class="text-gray-500 fw-semibold fs-6">
                            {{ __('admin.admins') }}
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- FAQs -->
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card dashboard-v2-card h-md-100">
                    <div class="card-body p-5 text-center d-flex flex-column align-items-center justify-content-center">
                        <div class="icon-box-v2 icon-box-warning mb-3">
                            <i class="ki-duotone ki-questionnaire-edit fs-1 text-warning">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                        <div class="fs-2hx fw-bold text-gray-900 mb-1">{{ number_format($totalFaqs) }}</div>
                        <div class="text-gray-500 fw-semibold fs-6">
                            {{ __('admin.faqs') }}
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Pages -->
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card dashboard-v2-card h-md-100">
                    <div class="card-body p-5 text-center d-flex flex-column align-items-center justify-content-center">
                        <div class="icon-box-v2 icon-box-primary mb-3" style="background: rgba(239, 68, 68, 0.1) !important; color: #EF4444 !important;">
                            <i class="ki-duotone ki-document fs-1 text-danger">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                        <div class="fs-2hx fw-bold text-gray-900 mb-1">{{ number_format($totalPages) }}</div>
                        <div class="text-gray-500 fw-semibold fs-6">
                            {{ __('admin.pages') }}
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Airports -->
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card dashboard-v2-card h-md-100">
                    <div class="card-body p-5 text-center d-flex flex-column align-items-center justify-content-center">
                        <div class="icon-box-v2 icon-box-primary mb-3">
                            <i class="ki-duotone ki-airplane fs-1 text-primary">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                        <div class="fs-2hx fw-bold text-gray-900 mb-1">{{ number_format($totalAirports) }}</div>
                        <div class="text-gray-500 fw-semibold fs-6">
                            {{ __('admin.airports') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="row g-5 mb-7">
            <div class="col-12">
                <div class="card dashboard-v2-card h-xl-100">
                    <div class="card-header pt-5 border-0">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900 fs-3">
                                {{ __('admin.last_30_days_statistics') }}
                            </span>
                            <span class="text-gray-500 mt-1 fw-semibold fs-6">
                                {{ __('admin.daily_activity_trends') }}
                            </span>
                        </h3>
                    </div>
                    <div class="card-body pt-4">
                        <div id="kt_charts_widget_30_days" class="min-h-auto" style="height: 400px; position: relative;">
                            <canvas id="myChart" width="400" height="200"></canvas>
                            <div id="chartFallback" style="display: none; text-align: center; padding: 50px; color: #6B7280;">
                                <i class="ki-duotone ki-chart-simple fs-3x text-muted mb-3">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                <p class="fs-5">
                                    {{ __('admin.loading_chart') }}
                                </p>
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
    console.log('Chart script started');
    
    // Show loading message
    $('#chartFallback').show();
    
    // Load Chart.js dynamically
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.js';
    script.onload = function() {
        console.log('Chart.js loaded successfully');
        createChart();
    };
    script.onerror = function() {
        console.error('Failed to load Chart.js');
        showFallbackMessage();
    };
    document.head.appendChild(script);
    
    function createChart() {
        try {
            // Chart data from PHP
            const chartData = @json($chartData);
            console.log('Chart data:', chartData);
            
            // Create labels for last 30 days
            const labels = [];
            const usersData = [];
            const driversData = [];
            const ordersData = [];
            const incomeData = [];
            
            // Generate data for last 30 days
            for (let i = 29; i >= 0; i--) {
                const date = new Date();
                date.setDate(date.getDate() - i);
                const day = date.getDate();
                const month = date.getMonth() + 1;
                labels.push(`${day}/${month}`);
                
                // Use actual data if available, otherwise use 0
                if (chartData && chartData.length > 0 && chartData[29-i]) {
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
            
            // Get canvas element
            const canvas = document.getElementById('myChart');
            if (!canvas) {
                console.error('Canvas element not found');
                showFallbackMessage();
                return;
            }
            
            const ctx = canvas.getContext('2d');
            
            // Hide loading message
            $('#chartFallback').hide();
            
            // Get current locale
            const isArabic = '{{ app()->getLocale() }}' === 'ar';
            const fontName = isArabic ? 'Cairo' : 'Outfit';
            
            // Create gradients for smooth area fill
            const gradientUsers = ctx.createLinearGradient(0, 0, 0, 350);
            gradientUsers.addColorStop(0, 'rgba(59, 130, 246, 0.25)');
            gradientUsers.addColorStop(1, 'rgba(59, 130, 246, 0.00)');
            
            const gradientDrivers = ctx.createLinearGradient(0, 0, 0, 350);
            gradientDrivers.addColorStop(0, 'rgba(16, 185, 129, 0.25)');
            gradientDrivers.addColorStop(1, 'rgba(16, 185, 129, 0.00)');

            const gradientOrders = ctx.createLinearGradient(0, 0, 0, 350);
            gradientOrders.addColorStop(0, 'rgba(245, 158, 11, 0.25)');
            gradientOrders.addColorStop(1, 'rgba(245, 158, 11, 0.00)');

            const gradientIncome = ctx.createLinearGradient(0, 0, 0, 350);
            gradientIncome.addColorStop(0, 'rgba(6, 182, 212, 0.25)');
            gradientIncome.addColorStop(1, 'rgba(6, 182, 212, 0.00)');
            
            // Create the chart
            const myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: isArabic ? 'المستخدمون' : 'Users',
                        data: usersData,
                        borderColor: '#3B82F6',
                        backgroundColor: gradientUsers,
                        tension: 0.35,
                        fill: true,
                        pointRadius: 3,
                        pointHoverRadius: 6,
                        borderWidth: 2
                    }, {
                        label: isArabic ? 'السائقون' : 'Drivers',
                        data: driversData,
                        borderColor: '#10B981',
                        backgroundColor: gradientDrivers,
                        tension: 0.35,
                        fill: true,
                        pointRadius: 3,
                        pointHoverRadius: 6,
                        borderWidth: 2
                    }, {
                        label: isArabic ? 'الطلبات' : 'Orders',
                        data: ordersData,
                        borderColor: '#F59E0B',
                        backgroundColor: gradientOrders,
                        tension: 0.35,
                        fill: true,
                        pointRadius: 3,
                        pointHoverRadius: 6,
                        borderWidth: 2
                    }, {
                        label: isArabic ? 'الإيرادات (جنيه)' : 'Revenue (EGP)',
                        data: incomeData,
                        borderColor: '#06B6D4',
                        backgroundColor: gradientIncome,
                        tension: 0.35,
                        fill: true,
                        pointRadius: 3,
                        pointHoverRadius: 6,
                        borderWidth: 2,
                        yAxisID: 'y1'
                    }]
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
                                text: isArabic ? 'العدد' : 'Count',
                                color: '#6B7280',
                                font: { family: fontName, size: 12, weight: '600' }
                            },
                            ticks: {
                                stepSize: 1,
                                color: '#9CA3AF',
                                font: { family: fontName, size: 11 }
                            },
                            grid: {
                                color: 'rgba(156, 163, 175, 0.08)'
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
                                color: '#6B7280',
                                font: { family: fontName, size: 12, weight: '600' }
                            },
                            ticks: {
                                color: '#9CA3AF',
                                font: { family: fontName, size: 11 }
                            },
                            grid: {
                                drawOnChartArea: false,
                            }
                        },
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: isArabic ? 'التاريخ' : 'Date',
                                color: '#6B7280',
                                font: { family: fontName, size: 12, weight: '600' }
                            },
                            ticks: {
                                color: '#9CA3AF',
                                font: { family: fontName, size: 11 }
                            },
                            grid: {
                                color: 'rgba(156, 163, 175, 0.08)'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                color: '#4B5563',
                                font: { family: fontName, size: 12, weight: '500' }
                            }
                        },
                        title: {
                            display: false
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(17, 24, 39, 0.95)',
                            titleColor: '#FFFFFF',
                            bodyColor: '#FFFFFF',
                            titleFont: { family: fontName, size: 13, weight: '700' },
                            bodyFont: { family: fontName, size: 12 },
                            borderColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 1,
                            padding: 12,
                            cornerRadius: 8
                        }
                    }
                }
            });
            
            console.log('Chart created successfully');
        } catch (error) {
            console.error('Error creating chart:', error);
            showFallbackMessage();
        }
    }
    
    function showFallbackMessage() {
        const isArabic = '{{ app()->getLocale() }}' === 'ar';
        $('#chartFallback').html(`
            <i class="ki-duotone ki-warning-2 fs-3x text-warning mb-3">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
            </i>
            <p class="fs-5">${isArabic ? 'لا يمكن تحميل الرسم البياني حالياً' : 'Unable to load chart at the moment'}</p>
            <p class="fs-6 text-muted">${isArabic ? 'يرجى المحاولة مرة أخرى لاحقاً' : 'Please try again later'}</p>
        `).show();
    }
});
</script>
@endpush

@endsection
