@extends('layouts.admin')

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted">الرئيسية</li>
    <span class="bullet bg-gray-300 w-5px h-2px"></span>
    <li class="breadcrumb-item text-dark">لوحة الإحصائيات</li>
@endsection

@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        
        <!-- Welcome Section -->
        <div class="row mb-7">
            <div class="col-12">
                <div class="card bg-primary">
                    <div class="card-body d-flex align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="ki-duotone ki-truck fs-2x text-white me-4">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <div>
                                <h1 class="text-white fw-bold fs-2 mb-1">
                                    {{ app()->getLocale() == 'ar' ? 'مرحباً بك في شكشك' : 'Welcome to Shakshak' }}
                                </h1>
                                <p class="text-white opacity-75 fs-6 mb-0">
                                    {{ app()->getLocale() == 'ar' ? 'لوحة تحكم شاملة لإدارة منصة التوصيل' : 'Comprehensive dashboard for delivery platform management' }}
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
                <h3 class="text-gray-800 fw-bold mb-5">
                    {{ app()->getLocale() == 'ar' ? 'إحصائيات اليوم' : 'Today\'s Statistics' }}
                </h3>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-flush h-md-100">
                    <div class="card-header">
                        <div class="card-title">
                            <h2 class="text-gray-800 fw-bold">
                                {{ app()->getLocale() == 'ar' ? 'المستخدمون الجدد' : 'New Users' }}
                            </h2>
                        </div>
                    </div>
                    <div class="card-body pt-1">
                        <div class="d-flex align-items-center">
                            <div class="fs-2hx fw-bold text-primary me-2">{{ $todayUsers }}</div>
                            <div class="text-gray-600 fw-semibold fs-6">
                                {{ app()->getLocale() == 'ar' ? 'مستخدم جديد' : 'new user' }}
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-2">
                            <i class="ki-duotone ki-user fs-3 text-primary me-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <span class="text-gray-600 fs-7">
                                {{ app()->getLocale() == 'ar' ? 'إجمالي: ' : 'Total: ' }}{{ number_format($totalUsers) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-flush h-md-100">
                    <div class="card-header">
                        <div class="card-title">
                            <h2 class="text-gray-800 fw-bold">
                                {{ app()->getLocale() == 'ar' ? 'السائقون الجدد' : 'New Drivers' }}
                            </h2>
                        </div>
                    </div>
                    <div class="card-body pt-1">
                        <div class="d-flex align-items-center">
                            <div class="fs-2hx fw-bold text-success me-2">{{ $todayDrivers }}</div>
                            <div class="text-gray-600 fw-semibold fs-6">
                                {{ app()->getLocale() == 'ar' ? 'سائق جديد' : 'new driver' }}
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-2">
                            <i class="ki-duotone ki-profile-user fs-3 text-success me-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <span class="text-gray-600 fs-7">
                                {{ app()->getLocale() == 'ar' ? 'إجمالي: ' : 'Total: ' }}{{ number_format($totalDrivers) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-flush h-md-100">
                    <div class="card-header">
                        <div class="card-title">
                            <h2 class="text-gray-800 fw-bold">
                                {{ app()->getLocale() == 'ar' ? 'الطلبات الجديدة' : 'New Orders' }}
                            </h2>
                        </div>
                    </div>
                    <div class="card-body pt-1">
                        <div class="d-flex align-items-center">
                            <div class="fs-2hx fw-bold text-warning me-2">{{ $todayOrders }}</div>
                            <div class="text-gray-600 fw-semibold fs-6">
                                {{ app()->getLocale() == 'ar' ? 'طلب جديد' : 'new order' }}
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-2">
                            <i class="ki-duotone ki-shopping-cart fs-3 text-warning me-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <span class="text-gray-600 fs-7">
                                {{ app()->getLocale() == 'ar' ? 'إجمالي: ' : 'Total: ' }}{{ number_format($totalOrders) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-flush h-md-100">
                    <div class="card-header">
                        <div class="card-title">
                            <h2 class="text-gray-800 fw-bold">
                                {{ app()->getLocale() == 'ar' ? 'إيرادات اليوم' : 'Today\'s Revenue' }}
                            </h2>
                        </div>
                    </div>
                    <div class="card-body pt-1">
                        <div class="d-flex align-items-center">
                            <div class="fs-2hx fw-bold text-info me-2">{{ number_format($todayIncome, 2) }}</div>
                            <div class="text-gray-600 fw-semibold fs-6">
                                {{ app()->getLocale() == 'ar' ? 'جنيه' : 'EGP' }}
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-2">
                            <i class="ki-duotone ki-dollar fs-3 text-info me-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <span class="text-gray-600 fs-7">
                                {{ app()->getLocale() == 'ar' ? 'إجمالي: ' : 'Total: ' }}{{ number_format($totalIncome, 2) }} {{ app()->getLocale() == 'ar' ? 'جنيه' : 'EGP' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Statistics Cards -->
        <div class="row g-5 mb-7">
            <div class="col-12">
                <h3 class="text-gray-800 fw-bold mb-5">
                    {{ app()->getLocale() == 'ar' ? 'الإحصائيات الرئيسية' : 'Main Statistics' }}
                </h3>
            </div>
            
            <!-- Users Statistics -->
            <div class="col-xl-4 col-md-6">
                <div class="card card-flush h-md-100">
                    <div class="card-header">
                        <div class="card-title">
                            <h2 class="text-gray-800 fw-bold">
                                {{ app()->getLocale() == 'ar' ? 'المستخدمون' : 'Users' }}
                            </h2>
                        </div>
                    </div>
                    <div class="card-body pt-1">
                        <div class="d-flex align-items-center mb-3">
                            <div class="fs-2hx fw-bold text-primary me-2">{{ number_format($totalUsers) }}</div>
                            <div class="text-gray-600 fw-semibold fs-6">
                                {{ app()->getLocale() == 'ar' ? 'إجمالي المستخدمين' : 'Total Users' }}
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <span class="text-gray-600 fs-7 me-2">
                                        {{ app()->getLocale() == 'ar' ? 'هذا الشهر:' : 'This Month:' }}
                                    </span>
                                    <span class="fw-bold text-gray-800">{{ $monthUsers }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <span class="text-gray-600 fs-7 me-2">
                                        {{ app()->getLocale() == 'ar' ? 'هذا الأسبوع:' : 'This Week:' }}
                                    </span>
                                    <span class="fw-bold text-gray-800">{{ $weekUsers }}</span>
                                </div>
                            </div>
                        </div>
                        @if($userGrowth != 0)
                        <div class="d-flex align-items-center mt-2">
                            <span class="badge {{ $userGrowth > 0 ? 'badge-light-success' : 'badge-light-danger' }} fs-8">
                                <i class="ki-duotone ki-arrow-{{ $userGrowth > 0 ? 'up' : 'down' }} fs-7 me-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                {{ abs($userGrowth) }}%
                            </span>
                            <span class="text-gray-600 fs-7 ms-2">
                                {{ app()->getLocale() == 'ar' ? 'نمو شهري' : 'Monthly Growth' }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Drivers Statistics -->
            <div class="col-xl-4 col-md-6">
                <div class="card card-flush h-md-100">
                    <div class="card-header">
                        <div class="card-title">
                            <h2 class="text-gray-800 fw-bold">
                                {{ app()->getLocale() == 'ar' ? 'السائقون' : 'Drivers' }}
                            </h2>
                        </div>
                    </div>
                    <div class="card-body pt-1">
                        <div class="d-flex align-items-center mb-3">
                            <div class="fs-2hx fw-bold text-success me-2">{{ number_format($totalDrivers) }}</div>
                            <div class="text-gray-600 fw-semibold fs-6">
                                {{ app()->getLocale() == 'ar' ? 'إجمالي السائقين' : 'Total Drivers' }}
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-4">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="fw-bold text-success fs-4">{{ $activeDrivers }}</span>
                                    <span class="text-gray-600 fs-8">
                                        {{ app()->getLocale() == 'ar' ? 'نشط' : 'Active' }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="fw-bold text-warning fs-4">{{ $pendingDrivers }}</span>
                                    <span class="text-gray-600 fs-8">
                                        {{ app()->getLocale() == 'ar' ? 'في الانتظار' : 'Pending' }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="fw-bold text-danger fs-4">{{ $blockedDrivers }}</span>
                                    <span class="text-gray-600 fs-8">
                                        {{ app()->getLocale() == 'ar' ? 'محظور' : 'Blocked' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @if($driverGrowth != 0)
                        <div class="d-flex align-items-center mt-2">
                            <span class="badge {{ $driverGrowth > 0 ? 'badge-light-success' : 'badge-light-danger' }} fs-8">
                                <i class="ki-duotone ki-arrow-{{ $driverGrowth > 0 ? 'up' : 'down' }} fs-7 me-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                {{ abs($driverGrowth) }}%
                            </span>
                            <span class="text-gray-600 fs-7 ms-2">
                                {{ app()->getLocale() == 'ar' ? 'نمو شهري' : 'Monthly Growth' }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Orders Statistics -->
            <div class="col-xl-4 col-md-6">
                <div class="card card-flush h-md-100">
                    <div class="card-header">
                        <div class="card-title">
                            <h2 class="text-gray-800 fw-bold">
                                {{ app()->getLocale() == 'ar' ? 'الطلبات' : 'Orders' }}
                            </h2>
                        </div>
                    </div>
                    <div class="card-body pt-1">
                        <div class="d-flex align-items-center mb-3">
                            <div class="fs-2hx fw-bold text-warning me-2">{{ number_format($totalOrders) }}</div>
                            <div class="text-gray-600 fw-semibold fs-6">
                                {{ app()->getLocale() == 'ar' ? 'إجمالي الطلبات' : 'Total Orders' }}
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-4">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="fw-bold text-warning fs-4">{{ $pendingOrders }}</span>
                                    <span class="text-gray-600 fs-8">
                                        {{ app()->getLocale() == 'ar' ? 'في الانتظار' : 'Pending' }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="fw-bold text-success fs-4">{{ $completedOrders }}</span>
                                    <span class="text-gray-600 fs-8">
                                        {{ app()->getLocale() == 'ar' ? 'مكتمل' : 'Completed' }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="fw-bold text-danger fs-4">{{ $cancelledOrders }}</span>
                                    <span class="text-gray-600 fs-8">
                                        {{ app()->getLocale() == 'ar' ? 'ملغي' : 'Cancelled' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @if($orderGrowth != 0)
                        <div class="d-flex align-items-center mt-2">
                            <span class="badge {{ $orderGrowth > 0 ? 'badge-light-success' : 'badge-light-danger' }} fs-8">
                                <i class="ki-duotone ki-arrow-{{ $orderGrowth > 0 ? 'up' : 'down' }} fs-7 me-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                {{ abs($orderGrowth) }}%
                            </span>
                            <span class="text-gray-600 fs-7 ms-2">
                                {{ app()->getLocale() == 'ar' ? 'نمو شهري' : 'Monthly Growth' }}
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
                <h3 class="text-gray-800 fw-bold mb-5">
                    {{ app()->getLocale() == 'ar' ? 'الإحصائيات المالية' : 'Financial Statistics' }}
                </h3>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-flush h-md-100">
                    <div class="card-header">
                        <div class="card-title">
                            <h2 class="text-gray-800 fw-bold">
                                {{ app()->getLocale() == 'ar' ? 'إجمالي الإيرادات' : 'Total Revenue' }}
                            </h2>
                        </div>
                    </div>
                    <div class="card-body pt-1">
                        <div class="d-flex align-items-center mb-3">
                            <div class="fs-2hx fw-bold text-info me-2">{{ number_format($totalIncome, 2) }}</div>
                            <div class="text-gray-600 fw-semibold fs-6">
                                {{ app()->getLocale() == 'ar' ? 'جنيه' : 'EGP' }}
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="d-flex flex-column">
                                    <span class="text-gray-600 fs-7">
                                        {{ app()->getLocale() == 'ar' ? 'هذا الشهر:' : 'This Month:' }}
                                    </span>
                                    <span class="fw-bold text-gray-800">{{ number_format($monthIncome, 2) }} {{ app()->getLocale() == 'ar' ? 'جنيه' : 'EGP' }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex flex-column">
                                    <span class="text-gray-600 fs-7">
                                        {{ app()->getLocale() == 'ar' ? 'هذا الأسبوع:' : 'This Week:' }}
                                    </span>
                                    <span class="fw-bold text-gray-800">{{ number_format($weekIncome, 2) }} {{ app()->getLocale() == 'ar' ? 'جنيه' : 'EGP' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-flush h-md-100">
                    <div class="card-header">
                        <div class="card-title">
                            <h2 class="text-gray-800 fw-bold">
                                {{ app()->getLocale() == 'ar' ? 'معاملات المحفظة' : 'Wallet Transactions' }}
                            </h2>
                        </div>
                    </div>
                    <div class="card-body pt-1">
                        <div class="d-flex align-items-center">
                            <div class="fs-2hx fw-bold text-primary me-2">{{ number_format($totalWalletTransactions) }}</div>
                            <div class="text-gray-600 fw-semibold fs-6">
                                {{ app()->getLocale() == 'ar' ? 'معاملة' : 'transaction' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-flush h-md-100">
                    <div class="card-header">
                        <div class="card-title">
                            <h2 class="text-gray-800 fw-bold">
                                {{ app()->getLocale() == 'ar' ? 'معاملات الدفع' : 'Payment Transactions' }}
                            </h2>
                        </div>
                    </div>
                    <div class="card-body pt-1">
                        <div class="d-flex align-items-center">
                            <div class="fs-2hx fw-bold text-success me-2">{{ number_format($totalPaymentTransactions) }}</div>
                            <div class="text-gray-600 fw-semibold fs-6">
                                {{ app()->getLocale() == 'ar' ? 'معاملة' : 'transaction' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-flush h-md-100">
                    <div class="card-header">
                        <div class="card-title">
                            <h2 class="text-gray-800 fw-bold">
                                {{ app()->getLocale() == 'ar' ? 'طلبات السحب' : 'Withdrawal Requests' }}
                            </h2>
                        </div>
                    </div>
                    <div class="card-body pt-1">
                        <div class="d-flex align-items-center mb-3">
                            <div class="fs-2hx fw-bold text-warning me-2">{{ number_format($totalWithdrawRequests) }}</div>
                            <div class="text-gray-600 fw-semibold fs-6">
                                {{ app()->getLocale() == 'ar' ? 'طلب' : 'request' }}
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge badge-light-warning fs-8 me-2">
                                {{ $pendingWithdrawRequests }} {{ app()->getLocale() == 'ar' ? 'في الانتظار' : 'Pending' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Statistics -->
        <div class="row g-5 mb-7">
            <div class="col-12">
                <h3 class="text-gray-800 fw-bold mb-5">
                    {{ app()->getLocale() == 'ar' ? 'إحصائيات النظام' : 'System Statistics' }}
                </h3>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card card-flush h-md-100">
                    <div class="card-body text-center">
                        <i class="ki-duotone ki-briefcase fs-2x text-primary mb-3">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <div class="fs-2hx fw-bold text-gray-800">{{ number_format($totalServices) }}</div>
                        <div class="text-gray-600 fs-6">
                            {{ app()->getLocale() == 'ar' ? 'الخدمات' : 'Services' }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card card-flush h-md-100">
                    <div class="card-body text-center">
                        <i class="ki-duotone ki-truck fs-2x text-success mb-3">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <div class="fs-2hx fw-bold text-gray-800">{{ number_format($totalFreightVehicles) }}</div>
                        <div class="text-gray-600 fs-6">
                            {{ app()->getLocale() == 'ar' ? 'مركبات الشحن' : 'Freight Vehicles' }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card card-flush h-md-100">
                    <div class="card-body text-center">
                        <i class="ki-duotone ki-user-square fs-2x text-info mb-3">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <div class="fs-2hx fw-bold text-gray-800">{{ number_format($totalAdmins) }}</div>
                        <div class="text-gray-600 fs-6">
                            {{ app()->getLocale() == 'ar' ? 'المشرفون' : 'Admins' }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card card-flush h-md-100">
                    <div class="card-body text-center">
                        <i class="ki-duotone ki-questionnaire-edit fs-2x text-warning mb-3">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <div class="fs-2hx fw-bold text-gray-800">{{ number_format($totalFaqs) }}</div>
                        <div class="text-gray-600 fs-6">
                            {{ app()->getLocale() == 'ar' ? 'الأسئلة الشائعة' : 'FAQs' }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card card-flush h-md-100">
                    <div class="card-body text-center">
                        <i class="ki-duotone ki-document fs-2x text-danger mb-3">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <div class="fs-2hx fw-bold text-gray-800">{{ number_format($totalPages) }}</div>
                        <div class="text-gray-600 fs-6">
                            {{ app()->getLocale() == 'ar' ? 'الصفحات' : 'Pages' }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card card-flush h-md-100">
                    <div class="card-body text-center">
                        <i class="ki-duotone ki-airplane fs-2x text-primary mb-3">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <div class="fs-2hx fw-bold text-gray-800">{{ number_format($totalAirports) }}</div>
                        <div class="text-gray-600 fs-6">
                            {{ app()->getLocale() == 'ar' ? 'المطارات' : 'Airports' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="row g-5 mb-7">
            <div class="col-12">
                <div class="card card-flush h-xl-100">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900">
                                {{ app()->getLocale() == 'ar' ? 'إحصائيات آخر 30 يوم' : 'Last 30 Days Statistics' }}
                            </span>
                            <span class="text-gray-500 mt-1 fw-semibold fs-6">
                                {{ app()->getLocale() == 'ar' ? 'تطور النشاط اليومي' : 'Daily Activity Trends' }}
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
                                    {{ app()->getLocale() == 'ar' ? 'جاري تحميل الرسم البياني...' : 'Loading chart...' }}
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
            
            console.log('Labels:', labels);
            console.log('Users data:', usersData);
            
            // Get canvas element
            const ctx = document.getElementById('myChart');
            if (!ctx) {
                console.error('Canvas element not found');
                showFallbackMessage();
                return;
            }
            
            // Hide loading message
            $('#chartFallback').hide();
            
            // Get current locale
            const isArabic = '{{ app()->getLocale() }}' === 'ar';
            
            // Create the chart
            const myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: isArabic ? 'المستخدمون' : 'Users',
                        data: usersData,
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }, {
                        label: isArabic ? 'السائقون' : 'Drivers',
                        data: driversData,
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }, {
                        label: isArabic ? 'الطلبات' : 'Orders',
                        data: ordersData,
                        borderColor: '#F59E0B',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }, {
                        label: isArabic ? 'الإيرادات (جنيه)' : 'Revenue (EGP)',
                        data: incomeData,
                        borderColor: '#EF4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6,
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
                                color: '#6B7280'
                            },
                            ticks: {
                                stepSize: 1,
                                color: '#6B7280'
                            },
                            grid: {
                                color: 'rgba(107, 114, 128, 0.1)'
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
                                color: '#6B7280'
                            },
                            ticks: {
                                stepSize: 1,
                                color: '#6B7280'
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
                                color: '#6B7280'
                            },
                            ticks: {
                                color: '#6B7280'
                            },
                            grid: {
                                color: 'rgba(107, 114, 128, 0.1)'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                color: '#374151'
                            }
                        },
                        title: {
                            display: true,
                            text: isArabic ? 'تطور النشاط اليومي - آخر 30 يوم' : 'Daily Activity Trends - Last 30 Days',
                            font: {
                                size: 16,
                                weight: 'bold'
                            },
                            color: '#111827'
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: 'white',
                            bodyColor: 'white',
                            borderColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 1
                        }
                    },
                    elements: {
                        line: {
                            borderWidth: 2
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
