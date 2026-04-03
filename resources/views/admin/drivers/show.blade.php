@extends('layouts.admin')

@section('title', $pageTitle)

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> السائقون </li>
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
        <li class="breadcrumb-item text-dark">عرض التفاصيل</li>
    @endsection

@section('content')
<div class="card">
    <!-- Card Header -->
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <h2 class="fw-bold">{{ $pageTitle }}</h2>
        </div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.drivers.edit', $row) }}" class="btn btn-primary me-3">
                    <i class="ki-duotone ki-pencil fs-2 me-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    {{ trans('global.edit') }}
                </a>
                <a href="{{ route('admin.drivers.index') }}" class="btn btn-light">
                    <i class="ki-duotone ki-arrow-left fs-2 me-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    {{ trans('global.back') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Card Body -->
    <div class="card-body pt-0">
        <div class="d-flex flex-column flex-lg-row">
            <!-- Left Column - Profile Info -->
            <div class="flex-lg-row-auto w-lg-250px w-xl-300px mb-7 me-7">
                <div class="card card-flush py-4">
                    <!-- Profile Picture -->
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Profile Picture</h2>
                        </div>
                    </div>
                    <div class="card-body text-center pt-0">
                        <div class="symbol symbol-150px symbol-circle mb-5">
                            <img src="{{ asset($row->user->photo ?? 'assets/media/avatars/blank.png') }}" alt="{{ $row->user->full_name }}">
                        </div>
                        <div class="mb-7">
                            <h3 class="fw-bold text-dark">{{ $row->user->full_name }}</h3>
                            <span class="badge badge-light-primary fs-7">{{ $row->user->email }}</span>
                        </div>
                    </div>
                </div>

                <!-- Wallet Summary -->
                <div class="card card-flush py-4">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Wallet Summary</h2>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="d-flex flex-column gap-5 mt-5">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-40px symbol-circle me-4">
                                    <span class="symbol-label bg-light-success">
                                        <i class="ki-duotone ki-wallet fs-2 text-success">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-600 fw-semibold d-block fs-7">{{ trans('cruds.admin.fields.wallet_amount') }}</span>
                                    <span class="text-gray-800 fw-bold fs-6">{{ number_format($row->user->wallet_amount, 2) }} EGP</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-40px symbol-circle me-4">
                                    <span class="symbol-label bg-light-warning">
                                        <i class="ki-duotone ki-hourglass fs-2 text-warning">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-600 fw-semibold d-block fs-7">{{ trans('cruds.admin.fields.pending_wallet') }}</span>
                                    <span class="text-gray-800 fw-bold fs-6">{{ number_format($row->user->pending_wallet, 2) }} EGP</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Details -->
            <div class="flex-lg-fluid">
                <div class="card card-flush mb-7">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Driver Information</h2>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table table-row-bordered table-row-dashed gy-4">
                                <tbody>
                                    <tr>
                                        <th class="w-250px min-w-200px">{{ trans('cruds.admin.fields.id') }}</th>
                                        <td class="fw-bold">{{ $row->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('cruds.admin.fields.full_name') }}</th>
                                        <td class="fw-bold">{{ $row->user->full_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('cruds.admin.fields.email') }}</th>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="ki-duotone ki-sms fs-2 text-primary me-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                                <a href="mailto:{{ $row->user->email }}" class="text-gray-600 text-hover-primary">{{ $row->user->email }}</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('cruds.admin.fields.country') }}</th>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($row->user->country)
                                                <div class="symbol symbol-20px symbol-circle me-3">
                                                    <img src="{{ asset('assets/media/flags/' . strtolower($row->user->country->cca2) . '.svg') }}" alt="{{ $row->user->country->name }}" title="{{ $row->user->country->name }}">
                                                </div>
                                                <span class="fw-bold">{{ $row->user->country->name }} ({{ $row->user->country->cca3 }})</span>
                                                @else
                                                <span class="text-muted">N/A</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('cruds.admin.fields.phone_number') }}</th>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="ki-duotone ki-phone fs-2 text-primary me-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                                <a href="tel:{{ $row->user->phone_number }}" class="text-gray-600 text-hover-primary">{{ $row->user->phone_number }}</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Account Status</th>
                                        <td>
                                            @if($row->status == 'active')
                                            <span class="badge badge-light-success py-3 px-4 fs-7">Active</span>
                                            @elseif($row->status == 'pending')
                                            <span class="badge badge-light-warning py-3 px-4 fs-7">Pending Approval</span>
                                            @elseif($row->status == 'blocked')
                                            <span class="badge badge-light-danger py-3 px-4 fs-7">Blocked</span>
                                            @else
                                            <span class="badge badge-light-secondary py-3 px-4 fs-7">Unknown</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Registration Date</th>
                                        <td class="fw-bold">{{ $row->created_at->format('F j, Y \a\t g:i A') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Last Updated</th>
                                        <td class="fw-bold">{{ $row->updated_at->format('F j, Y \a\t g:i A') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Documents Section -->
                <div class="row g-6">
                    <div class="col-12">
                        <div class="card card-flush">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>{{ trans('global.documents') }}</h2>
                                </div>
                                <div class="card-toolbar">
                                    <span class="badge badge-light-primary fs-7">
                                        {{ count($driverDocuments ?? []) }} {{ trans('global.documents') }}
                                    </span>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                @if(!empty($driverDocuments))
                                    <div class="row g-4">
                                        @foreach($driverDocuments as $document)
                                            <div class="col-md-6 col-lg-4">
                                                <div class="card card-flush h-100">
                                                    <div class="card-body d-flex flex-column">
                                                        <div class="text-center mb-4">
                                                            <div class="symbol symbol-100px symbol-circle mx-auto mb-3">
                                                                <img src="{{ $document['image'] }}" alt="{{ $document['name'] }}" class="symbol-label" style="object-fit: cover;">
                                                            </div>
                                                            <h5 class="fw-bold text-dark mb-1">{{ $document['name'] }}</h5>
                                                            <span class="badge badge-light-success fs-8">
                                                                <i class="ki-duotone ki-check-circle fs-6 me-1">
                                                                    <span class="path1"></span>
                                                                    <span class="path2"></span>
                                                                </i>
                                                                {{ trans('global.uploaded') }}
                                                            </span>
                                                        </div>
                                                        <div class="mt-auto">
                                                            <button type="button" class="btn btn-light-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#documentModal{{ $loop->index }}">
                                                                <i class="ki-duotone ki-eye fs-5 me-2">
                                                                    <span class="path1"></span>
                                                                    <span class="path2"></span>
                                                                    <span class="path3"></span>
                                                                </i>
                                                                {{ trans('global.view') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-10">
                                        <div class="symbol symbol-100px symbol-circle mx-auto mb-5">
                                            <span class="symbol-label bg-light-warning">
                                                <i class="ki-duotone ki-document fs-2x text-warning">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </span>
                                        </div>
                                        <h4 class="text-gray-600 mb-3">{{ trans('global.no_documents_found') }}</h4>
                                        <p class="text-gray-500">{{ trans('global.no_documents_description') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                    <!-- Recent Trips Section -->
                    {{-- <div class="col-md-6">
                        <div class="card card-flush h-md-100">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Recent Trips</h2>
                                </div>
                                <div class="card-toolbar">
                                    <button class="btn btn-sm btn-light">View All</button>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="d-flex flex-column text-center gap-5 mt-5">
                                    <div class="text-gray-600">No recent trips found</div>
                                    <button class="btn btn-light-primary">View Trip History</button>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Document Modals -->
@if(!empty($driverDocuments))
    @foreach($driverDocuments as $index => $document)
        <div class="modal fade" id="documentModal{{ $index }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title">{{ $document['name'] }}</h2>
                        <div class="btn btn-icon btn-sm btn-light-primary ms-2" data-bs-dismiss="modal">
                            <i class="ki-duotone ki-cross fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">
                            <img src="{{ $document['image'] }}" alt="{{ $document['name'] }}" class="img-fluid rounded" style="max-height: 500px;">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ trans('global.close') }}</button>
                        <a href="{{ $document['image'] }}" target="_blank" class="btn btn-primary">
                            <i class="ki-duotone ki-arrow-top-right fs-5 me-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            {{ trans('global.open_in_new_tab') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif

@endsection