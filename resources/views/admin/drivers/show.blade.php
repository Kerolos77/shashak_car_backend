@extends('layouts.admin')

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted">{{ __('admin.drivers') ?? 'السائقون' }}</li>
    <span class="bullet bg-gray-300 w-5px h-2px"></span>
    <li class="breadcrumb-item text-dark">{{ __('admin.view') ?? 'عرض التفاصيل' }}</li>
@endsection

@section('content')
@section('title', $pageTitle)

<div class="row g-7">
    <!-- Left Column - Profile Summary -->
    <div class="col-xl-4">
        <div class="card mb-7">
            <div class="card-body p-6 text-center">
                <!-- User Avatar -->
                <div class="symbol symbol-120px symbol-circle mb-5 border border-primary border-opacity-20 p-2 bg-white bg-opacity-20">
                    <img src="{{ asset($row->user->photo ?? 'assets/media/avatars/blank.png') }}" alt="{{ $row->user->full_name }}" style="object-fit: cover;">
                </div>
                
                <h3 class="text-gray-900 fw-bold fs-3 mb-1">{{ $row->user->full_name }}</h3>
                <span class="badge badge-light-primary fw-semibold fs-7 mb-6">{{ $row->user->email }}</span>
                
                <div class="separator separator-dashed my-5"></div>
                
                <!-- Status Badge -->
                <div class="mb-5">
                    @if($row->status == 'active')
                        <span class="capsule-badge badge-soft-success fs-6 py-2 px-4">{{ __('app.active') }}</span>
                    @elseif($row->status == 'pending')
                        <span class="capsule-badge badge-soft-warning fs-6 py-2 px-4">{{ __('app.pending') }}</span>
                    @elseif($row->status == 'blocked')
                        <span class="capsule-badge badge-soft-danger fs-6 py-2 px-4">{{ __('app.blocked') }}</span>
                    @else
                        <span class="capsule-badge badge-soft-primary fs-6 py-2 px-4">{{ $row->status }}</span>
                    @endif
                </div>

                <!-- Action Button row -->
                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('admin.drivers.edit', $row->id) }}" class="btn btn-sm btn-primary px-4 py-2">
                        <i class="ki-outline ki-pencil fs-5 me-1"></i>
                        {{ trans('global.edit') }}
                    </a>
                    <a href="{{ route('admin.drivers.index') }}" class="btn btn-sm btn-light px-4 py-2">
                        <i class="ki-outline ki-arrow-left fs-5 me-1"></i>
                        {{ trans('global.back') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Wallet Highlights -->
        <div class="card">
            <div class="card-header pt-6 pb-2 border-0">
                <h3 class="card-label fw-bold text-gray-900 fs-4">Financial Highlights</h3>
            </div>
            <div class="card-body pt-0">
                <div class="d-flex align-items-center mb-6">
                    <div class="symbol symbol-45px me-4">
                        <span class="symbol-label badge-soft-success">
                            <i class="ki-duotone ki-wallet fs-2 text-success">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                    </div>
                    <div class="d-flex flex-column flex-grow-1">
                        <span class="text-gray-500 fw-semibold fs-7">{{ trans('cruds.admin.fields.wallet_amount') }}</span>
                        <span class="text-gray-900 fw-bold fs-5">{{ number_format($row->user->wallet_amount, 2) }} {{ __('admin.egp') }}</span>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <div class="symbol symbol-45px me-4">
                        <span class="symbol-label badge-soft-warning">
                            <i class="ki-duotone ki-hourglass fs-2 text-warning">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                    </div>
                    <div class="d-flex flex-column flex-grow-1">
                        <span class="text-gray-500 fw-semibold fs-7">{{ trans('cruds.admin.fields.pending_wallet') }}</span>
                        <span class="text-gray-900 fw-bold fs-5">{{ number_format($row->user->pending_wallet, 2) }} {{ __('admin.egp') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column - Information & Documents -->
    <div class="col-xl-8">
        <!-- Details Card -->
        <div class="card mb-7">
            <div class="card-header pt-6 pb-2 border-0">
                <h3 class="card-label fw-bold text-gray-900 fs-4">Driver Profile Information</h3>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-4">
                        <tbody>
                            <tr>
                                <th class="fw-bold text-gray-500 w-200px">ID</th>
                                <td class="fw-bold text-gray-900">#{{ $row->id }}</td>
                            </tr>
                            <tr>
                                <th class="fw-bold text-gray-500">{{ trans('cruds.admin.fields.full_name') }}</th>
                                <td class="fw-bold text-gray-900">{{ $row->user->full_name }}</td>
                            </tr>
                            <tr>
                                <th class="fw-bold text-gray-500">{{ trans('cruds.admin.fields.email') }}</th>
                                <td class="fw-bold text-gray-900">{{ $row->user->email }}</td>
                            </tr>
                            <tr>
                                <th class="fw-bold text-gray-500">{{ trans('cruds.admin.fields.phone_number') }}</th>
                                <td class="fw-bold text-gray-900">{{ $row->user->phone_number ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="fw-bold text-gray-500">{{ trans('cruds.admin.fields.country') }}</th>
                                <td class="fw-bold text-gray-900">
                                    @if($row->user->country)
                                        {{ $row->user->country->name }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="fw-bold text-gray-500">Service Assigned</th>
                                <td class="fw-bold text-gray-900">{{ $row->service->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="fw-bold text-gray-500">National ID</th>
                                <td class="fw-bold text-gray-900">{{ $row->id_number ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="fw-bold text-gray-500">Birth Date</th>
                                <td class="fw-bold text-gray-900">{{ $row->birth_date ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Documents Card -->
        <div class="card">
            <div class="card-header pt-6 pb-2 border-0 d-flex align-items-center justify-content-between">
                <h3 class="card-label fw-bold text-gray-900 fs-4">{{ trans('global.documents') }}</h3>
                <span class="badge badge-light-primary fw-bold">{{ count($driverDocuments ?? []) }} Files</span>
            </div>
            <div class="card-body pt-4">
                @if(!empty($driverDocuments))
                    <div class="row g-4">
                        @foreach($driverDocuments as $document)
                        <div class="col-md-6">
                            <div class="p-4 rounded border border-dashed border-gray-300 d-flex align-items-center justify-content-between bg-light bg-opacity-30">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-45px me-3 overflow-hidden rounded">
                                        <img src="{{ $document['image'] }}" alt="{{ $document['name'] }}" style="object-fit: cover;">
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-950 fw-bold fs-7">{{ $document['name'] }}</span>
                                        <span class="text-success fs-9 d-flex align-items-center gap-1">
                                            <span class="bullet bg-success w-4px h-4px rounded-circle"></span>
                                            Verified Upload
                                        </span>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-icon btn-light" data-bs-toggle="modal" data-bs-target="#documentModal{{ $loop->index }}" title="View Document">
                                    <i class="ki-duotone ki-eye fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-10">
                        <i class="ki-duotone ki-document fs-3x text-muted mb-3"><span class="path1"></span><span class="path2"></span></i>
                        <p class="text-gray-500 m-0">{{ trans('global.no_documents_found') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Document Modals -->
@if(!empty($driverDocuments))
    @foreach($driverDocuments as $index => $document)
        <div class="modal fade" id="documentModal{{ $index }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content glass-card border border-white border-opacity-40">
                    <div class="modal-header border-0 pt-6">
                        <h2 class="modal-title fw-bold text-gray-900">{{ $document['name'] }}</h2>
                        <button type="button" class="btn btn-icon btn-sm btn-light" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                        </button>
                    </div>
                    <div class="modal-body text-center p-8">
                        <img src="{{ $document['image'] }}" alt="{{ $document['name'] }}" class="img-fluid rounded shadow-sm border border-light" style="max-height: 520px; object-fit: contain;">
                    </div>
                    <div class="modal-footer border-0 pb-6">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ trans('global.close') }}</button>
                        <a href="{{ $document['image'] }}" target="_blank" class="btn btn-primary">
                            <i class="ki-outline ki-exit-right fs-5 me-1"></i>
                            {{ trans('global.open_in_new_tab') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif

@endsection
