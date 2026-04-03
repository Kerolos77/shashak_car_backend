@extends('layouts.admin')

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> المدفوعات </li>
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
        <li class="breadcrumb-item text-dark">عرض الكل</li>
    @endsection

@section('title', $pageTitle)
@section('pageName', $pageTitle)
@section('content')

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxxl">
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header flex-wrap py-5">
                <div class="row w-100 align-items-center">
                    <div class="col-md-9 col-12 mb-3 mb-md-0">
                        <div class="d-flex align-items-center">
                            <i class="ki-duotone ki-credit-cart fs-2x text-primary me-3">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <h3 class="text-gray-800 m-0">{{ $pageTitle }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body pt-0">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_payments_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="text-center">#</th>
                            <th class="text-center">{{ __('app.payment_id') }}</th>
                            <th class="text-center">{{ __('app.status') }}</th>
                            <th class="text-center">{{ __('app.amount') }}</th>
                            <th class="text-center">{{ __('app.payment_method') }}</th>
                            <th class="text-center">{{ __('app.payment_gateway') }}</th>
                            <th class="text-center">{{ __('app.user') }}</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @forelse ($rows ?? [] as $item)
                        <tr>
                            <td class="text-center">{{ $item->id }}</td>
                            <td class="text-center">{{ $item->payment_id }}</td>
                            <td class="text-center">
                                @php
                                    $badgeClass = match($item->status) {
                                        'success' => 'badge-light-success',
                                        'pending' => 'badge-light-warning',
                                        'failed'  => 'badge-light-danger',
                                        default   => 'badge-light-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($item->status) }}</span>
                            </td>
                            <td class="text-center">{{ number_format($item->amount, 2) }} L.E</td>
                            <td class="text-center">{{ $item->payment_method }}</td>
                            <td class="text-center">{{ $item->payment_gateway }}</td>
                            <td class="text-center">{{ $item->user->email }}</td>
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
            <!--end::Card body-->
        </div>
    </div>
</div>

@endsection
