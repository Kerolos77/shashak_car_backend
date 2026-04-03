@extends('layouts.admin')

@section('title', $pageTitle)
@section('pageName', $pageTitle)

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> المدفوعات </li>
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
        <li class="breadcrumb-item text-dark">طلبات السحب</li>
    @endsection

@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxxl">
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header flex-wrap py-5">
                <div class="row w-100 align-items-center">
                    <div class="col-md-12">
                        <div class="d-flex align-items-center">
                            <i class="ki-duotone ki-credit-cart fs-2x text-primary me-3">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <h3 class="text-gray-800 m-0">{{ $pageTitle }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body pt-0">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_payroll_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="text-center">#</th>
                            <th class="text-center">{{ trans('global.payroll_method') }}</th>
                            <th class="text-center">{{ trans('global.status') }}</th>
                            <th class="text-center">{{ trans('global.amount') }}</th>
                            <th class="text-center">{{ trans('global.note') }}</th>
                            <th class="text-center">{{ trans('global.actions') ?? 'Actions' }}</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @forelse ($rows ?? [] as $item)
                        <tr>
                            <td class="text-center">{{ $item->id }}</td>
                            <td class="text-center">{{ $item->payroll_method }}</td>
                            <td class="text-center">
                                @if ($item->status == 'success')
                                    <span class="badge badge-light-success">{{ trans('global.success') }}</span>
                                @elseif ($item->status == 'pending')
                                    <span class="badge badge-light-warning">{{ trans('global.pending') }}</span>
                                @elseif (in_array($item->status, ['failed', 'rejected']))
                                    <span class="badge badge-light-danger">{{ trans('global.' . $item->status) }}</span>
                                @endif
                            </td>
                            <td class="text-center">{{ number_format($item->amount, 2) }} L.E</td>
                            <td class="text-center">
                                @if ($item->note)
                                    {{ $item->note }}
                                @else
                                    <span class="badge badge-light-danger">null</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $item->user?->name ?? 'Unknown' }} ({{ $item->user?->phone_number ?? '-' }})</td>
                            <td class="text-center">
                                @if ($item->status == 'pending')
                                    <a href="{{ route('admin.payments.accept', $item->id) }}" class="btn btn-sm btn-success" onclick="return confirm('Are you sure you want to approve this withdrawal?');">
                                        <i class="ki-duotone ki-check fs-2"></i> {{ trans('global.accept') ?? 'Accept' }}
                                    </a>
                                    <a href="{{ route('admin.payments.reject', $item->id) }}" class="btn btn-sm btn-danger mt-1" onclick="return confirm('Are you sure you want to reject this withdrawal?');">
                                        <i class="ki-duotone ki-cross fs-2"></i> {{ trans('global.reject') ?? 'Reject' }}
                                    </a>
                                @else
                                    <span class="text-muted">Processed</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">{{ trans('global.no_records_found') }}</div>
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
