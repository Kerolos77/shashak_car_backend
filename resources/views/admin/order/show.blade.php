@extends('layouts.admin')

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted">{{ trans('cruds.order.title_singular') }}</li>
    <span class="bullet bg-gray-300 w-5px h-2px"></span>
    <li class="breadcrumb-item text-dark">{{ trans('global.view') }}</li>
@endsection

@section('content')
@section('title', trans('cruds.order.title_singular') . ' #' . $order->id)

<div class="row g-7">
    <!-- Left Column - Trip Route Details -->
    <div class="col-xl-8">
        <!-- Main details -->
        <div class="card mb-7">
            <div class="card-header pt-6 pb-2 border-0 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="bg-light-warning p-3 rounded-circle me-3">
                        <i class="ki-duotone ki-geolocation fs-2x text-warning">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                    <div>
                        <h3 class="text-gray-900 fw-bold m-0 fs-4">Trip Information</h3>
                        <span class="text-gray-500 fs-7">Route and distance parameters</span>
                    </div>
                </div>
                <div>
                    <span class="badge badge-light-primary fs-7 px-3 py-2">ID: #{{ $order->id }}</span>
                </div>
            </div>
            
            <div class="card-body pt-2">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-4">
                        <tbody>
                            <tr>
                                <th class="fw-bold text-gray-500 w-250px">Source Location</th>
                                <td class="fw-bold text-gray-900">
                                    <div class="d-flex align-items-center">
                                        <i class="ki-outline ki-geolocation-home fs-5 text-success me-2"></i>
                                        <span>{{ $order->source_location_name ?? 'N/A' }}</span>
                                    </div>
                                    <span class="text-gray-400 fs-8 d-block mt-1">Coordinates: {{ $order->source_location_l_at_lng ?? '-' }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th class="fw-bold text-gray-500">Destination Location</th>
                                <td class="fw-bold text-gray-900">
                                    <div class="d-flex align-items-center">
                                        <i class="ki-outline ki-geolocation-home fs-5 text-danger me-2"></i>
                                        <span>{{ $order->destination_location_name ?? 'N/A' }}</span>
                                    </div>
                                    <span class="text-gray-400 fs-8 d-block mt-1">Coordinates: {{ $order->destination_location_l_at_lng ?? '-' }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th class="fw-bold text-gray-500">Distance</th>
                                <td class="fw-bold text-gray-900">
                                    {{ $order->distance ?? '-' }} {{ $order->distance_type ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <th class="fw-bold text-gray-500">Trip Status</th>
                                <td class="fw-bold text-gray-900">
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
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Participants Card -->
        <div class="card">
            <div class="card-header pt-6 pb-2 border-0">
                <h3 class="card-label fw-bold text-gray-900 fs-4">Trip Participants</h3>
            </div>
            <div class="card-body pt-0">
                <div class="row g-5">
                    <!-- Customer Details -->
                    <div class="col-md-6">
                        <div class="p-5 rounded border border-dashed bg-light bg-opacity-30">
                            <h4 class="fw-bold text-gray-800 fs-6 mb-4">Customer Info</h4>
                            @if($order->user)
                            <div class="d-flex align-items-center">
                                <div class="avatar-initial blue me-3">
                                    {{ substr($order->user->name ?? 'C', 0, 1) }}
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-900 fw-bold fs-6">{{ $order->user->name ?? '-' }}</span>
                                    <span class="text-gray-500 fs-7">{{ $order->user->phone_number ?? '-' }}</span>
                                </div>
                            </div>
                            @else
                            <span class="text-gray-400 italic fs-7">No customer details available</span>
                            @endif
                        </div>
                    </div>

                    <!-- Driver Details -->
                    <div class="col-md-6">
                        <div class="p-5 rounded border border-dashed bg-light bg-opacity-30">
                            <h4 class="fw-bold text-gray-800 fs-6 mb-4">Driver Info</h4>
                            @if($order->driver)
                            <div class="d-flex align-items-center">
                                <div class="avatar-initial green me-3">
                                    {{ substr($order->driver->full_name ?? 'D', 0, 1) }}
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-900 fw-bold fs-6">{{ $order->driver->full_name ?? '-' }}</span>
                                    <span class="text-gray-500 fs-7">Status: {{ $order->accepted_driver ?? '-' }}</span>
                                </div>
                            </div>
                            @else
                            <span class="text-gray-400 italic fs-7">No driver assigned yet</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column - Pricing & Controls -->
    <div class="col-xl-4">
        <!-- Pricing Summary -->
        <div class="card mb-7">
            <div class="card-header pt-6 pb-2 border-0">
                <h3 class="card-label fw-bold text-gray-900 fs-4">Financial Details</h3>
            </div>
            <div class="card-body pt-0">
                <div class="d-flex align-items-center mb-6">
                    <div class="symbol symbol-45px me-4">
                        <span class="symbol-label badge-soft-warning">
                            <i class="ki-duotone ki-dollar fs-2 text-warning">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                    </div>
                    <div class="d-flex flex-column flex-grow-1">
                        <span class="text-gray-500 fw-semibold fs-7">{{ trans('cruds.order.fields.offer_rate') }}</span>
                        <span class="text-gray-900 fw-bold fs-5">{{ number_format($order->offer_rate ?? 0, 2) }} {{ __('admin.egp') }}</span>
                    </div>
                </div>

                <div class="d-flex align-items-center mb-6">
                    <div class="symbol symbol-45px me-4">
                        <span class="symbol-label badge-soft-success">
                            <i class="ki-duotone ki-verify fs-2 text-success">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                    </div>
                    <div class="d-flex flex-column flex-grow-1">
                        <span class="text-gray-500 fw-semibold fs-7">{{ trans('cruds.order.fields.final_rate') }}</span>
                        <span class="text-gray-900 fw-bold fs-5">{{ number_format($order->final_rate ?? 0, 2) }} {{ __('admin.egp') }}</span>
                    </div>
                </div>

                <div class="d-flex align-items-center mb-6">
                    <div class="symbol symbol-45px me-4">
                        <span class="symbol-label badge-soft-primary">
                            <i class="ki-duotone ki-briefcase fs-2 text-primary">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                    </div>
                    <div class="d-flex flex-column flex-grow-1">
                        <span class="text-gray-500 fw-semibold fs-7">{{ trans('cruds.order.fields.admin_commission') }}</span>
                        <span class="text-gray-900 fw-bold fs-5">{{ number_format($order->admin_commission ?? 0, 2) }} {{ __('admin.egp') }}</span>
                    </div>
                </div>

                <div class="separator separator-dashed my-5"></div>

                <div class="d-flex justify-content-between align-items-center fs-7 mb-3">
                    <span class="text-gray-500 fw-semibold">Payment Type:</span>
                    <span class="text-gray-900 fw-bold">{{ $order->payment_type ?? 'N/A' }}</span>
                </div>
                
                <div class="d-flex justify-content-between align-items-center fs-7 mb-3">
                    <span class="text-gray-500 fw-semibold">Payment Status:</span>
                    @if($order->payment_status)
                        <span class="capsule-badge badge-soft-success">Paid</span>
                    @else
                        <span class="capsule-badge badge-soft-danger">Unpaid</span>
                    @endif
                </div>

                <div class="d-flex justify-content-between align-items-center fs-7">
                    <span class="text-gray-500 fw-semibold">OTP Code:</span>
                    <span class="badge badge-light-primary fw-bold fs-6">{{ $order->otp ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Controls -->
        <div class="card">
            <div class="card-body p-6">
                <div class="d-flex flex-column gap-3">
                    @can('order_edit')
                    <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-primary w-100 py-3">
                        <i class="ki-outline ki-pencil fs-5 me-1"></i>
                        {{ trans('global.edit') }}
                    </a>
                    @endcan
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-light w-100 py-3">
                        <i class="ki-outline ki-arrow-left fs-5 me-1"></i>
                        {{ trans('global.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
