@extends('layouts.admin')

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted">المدفوعات</li>
    <span class="bullet bg-gray-300 w-5px h-2px"></span>
    <li class="breadcrumb-item text-dark">طلبات السحب المعلقة</li>
@endsection

@section('content')
@section('title', $pageTitle)

<div class="card">
    <div class="card-header pt-6 pb-4 border-0 d-flex align-items-center">
        <div class="bg-light-primary p-3 rounded-circle me-3">
            <i class="ki-duotone ki-credit-cart fs-2x text-primary">
                <span class="path1"></span><span class="path2"></span>
            </i>
        </div>
        <div>
            <h3 class="text-gray-900 fw-bold m-0 fs-3">{{ $pageTitle }}</h3>
            <span class="text-gray-500 fs-7">Approve or reject withdrawal applications from drivers</span>
        </div>
    </div>

    <div class="card-body pt-0">
        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_payroll_table">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th>ID</th>
                        <th>Driver / User</th>
                        <th>{{ trans('global.payroll_method') }}</th>
                        <th>{{ trans('global.amount') }}</th>
                        <th>{{ trans('global.note') }}</th>
                        <th class="text-center">{{ trans('global.status') }}</th>
                        <th class="text-end">{{ trans('global.actions') ?? 'Actions' }}</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    @forelse ($rows ?? [] as $item)
                    <tr>
                        <td class="fw-bold">#{{ $item->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-initial me-3">
                                    {{ substr($item->user?->full_name ?? $item->user?->name ?? 'D', 0, 1) }}
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-900 fw-bold fs-6">{{ $item->user?->full_name ?? $item->user?->name ?? 'Unknown Driver' }}</span>
                                    <span class="text-gray-400 fs-8">{{ $item->user?->phone_number ?? '-' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="fw-bold">{{ $item->payroll_method }}</td>
                        <td class="fw-bold text-gray-900">{{ number_format($item->amount, 2) }} {{ __('admin.egp') }}</td>
                        <td>
                            @if ($item->note)
                                <span class="text-gray-700 fs-7">{{ $item->note }}</span>
                            @else
                                <span class="text-gray-400 italic fs-8">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($item->status == 'success' || $item->status == 'accepted')
                                <span class="capsule-badge badge-soft-success">{{ trans('global.success') }}</span>
                            @elseif ($item->status == 'pending')
                                <span class="capsule-badge badge-soft-warning">{{ trans('global.pending') }}</span>
                            @elseif (in_array($item->status, ['failed', 'rejected']))
                                <span class="capsule-badge badge-soft-danger">{{ trans('global.' . $item->status) ?? 'Rejected' }}</span>
                            @else
                                <span class="capsule-badge badge-soft-primary">{{ $item->status }}</span>
                            @endif
                        </td>
                        <td class="text-end">
                            @if ($item->status == 'pending')
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.payments.accept', $item->id) }}" class="btn btn-sm btn-success px-4 py-2" onclick="return confirm('Are you sure you want to approve this withdrawal?');">
                                        <i class="ki-duotone ki-check fs-5 me-1"></i>
                                        {{ trans('global.accept') ?? 'Accept' }}
                                    </a>
                                    <a href="{{ route('admin.payments.reject', $item->id) }}" class="btn btn-sm btn-danger px-4 py-2" onclick="return confirm('Are you sure you want to reject this withdrawal?');">
                                        <i class="ki-duotone ki-cross fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>
                                        {{ trans('global.reject') ?? 'Reject' }}
                                    </a>
                                </div>
                            @else
                                <span class="text-gray-400 fs-7 italic">Processed</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-10">
                            <i class="ki-duotone ki-credit-cart fs-3x text-muted mb-3"><span class="path1"></span><span class="path2"></span></i>
                            <p class="text-gray-500 m-0">{{ trans('global.no_records_found') }}</p>
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
