@extends('layouts.admin')

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> الإيرادات </li>
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
        <li class="breadcrumb-item text-dark">عرض الكل</li>
    @endsection

@section('content')
@section('title', __('global.income_dashboard'))
@section('pageName', __('global.income_dashboard'))

<!-- Income Statistics Cards -->
<div class="row g-5 mb-7">
    <!-- Today Income Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-flush h-md-100">
            <div class="card-header">
                <div class="card-title">
                    <h2>{{ __('global.todayIncome') }}</h2>
                </div>
            </div>
            <div class="card-body pt-1 text-center">
                <span class="text-gray-600 fw-semibold d-block mb-2">Today's Total</span>
                <div class="d-flex align-items-center justify-content-center">
                    <span class="fs-2hx fw-bold text-primary me-2">{{ number_format($todayIncome, 2) }} EGP</span>
                    <span class="badge badge-light-primary fs-base">
                        <i class="ti ti-currency-dollar fs-5 text-primary ms-n1"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Week Income Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-flush h-md-100">
            <div class="card-header">
                <div class="card-title">
                    <h2>{{ __('global.weekIncome') }}</h2>
                </div>
            </div>
            <div class="card-body pt-1 text-center">
                <span class="text-gray-600 fw-semibold d-block mb-2">This Week's Total</span>
                <div class="d-flex align-items-center justify-content-center">
                    <span class="fs-2hx fw-bold text-warning me-2">{{ number_format($weekIncome, 2) }} EGP</span>
                    <span class="badge badge-light-warning fs-base">
                        <i class="ti ti-calendar-event fs-5 text-warning ms-n1"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Month Income Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-flush h-md-100">
            <div class="card-header">
                <div class="card-title">
                    <h2>{{ __('global.monthIncome') }}</h2>
                </div>
            </div>
            <div class="card-body pt-1 text-center">
                <span class="text-gray-600 fw-semibold d-block mb-2">This Month's Total</span>
                <div class="d-flex align-items-center justify-content-center">
                    <span class="fs-2hx fw-bold text-success me-2">{{ number_format($monthIncome, 2) }} EGP</span>
                    <span class="badge badge-light-success fs-base">
                        <i class="ti ti-chart-bar fs-5 text-success ms-n1"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Year Income Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-flush h-md-100">
            <div class="card-header">
                <div class="card-title">
                    <h2>{{ __('global.yearIncome') }}</h2>
                </div>
            </div>
            <div class="card-body pt-1 text-center">
                <span class="text-gray-600 fw-semibold d-block mb-2">This Year's Total</span>
                <div class="d-flex align-items-center justify-content-center">
                    <span class="fs-2hx fw-bold text-danger me-2">{{ number_format($yearIncome, 2) }} EGP</span>
                    <span class="badge badge-light-danger fs-base">
                        <i class="ti ti-calendar fs-5 text-danger ms-n1"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Orders Table -->
<div class="card">
    <div class="card-header flex-wrap py-5">
        <div class="d-flex align-items-center">
            <i class="ti ti-shopping-cart fs-2x text-primary me-3"></i>
            <h3 class="text-gray-800 m-0">{{ __('global.incomes') }}</h3>
        </div>
    </div>
    <div class="card-body pt-0">
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_orders">
            <thead>
                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0 text-center">
                    <th class="min-w-50px">#</th>
                    <th class="min-w-150px">{{ __('global.client') }}</th>
                    <th class="min-w-150px">{{ __('global.driver') }}</th>
                    <th class="min-w-100px">{{ __('global.amount') }}</th>
                    <th class="min-w-150px">{{ __('global.created_at') }}</th>
                </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600 text-center">
                @forelse ($rows ?? [] as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->order->user->email ?? 'N/A' }}</td>
                    <td>{{ $item->order->driver->email ?? 'N/A' }}</td>
                    <td>{{ number_format($item->amount, 2) }} EGP</td>
                    <td>{{ $item->created_at->format('M d, Y h:i A') }}</td>
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
@endsection
