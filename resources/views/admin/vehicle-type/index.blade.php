@extends('layouts.admin')

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> أنواع المركبات </li>
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
                            <i class="ki-duotone ki-truck fs-2x text-primary me-3"></i>
                            <h3 class="text-gray-800 m-0">{{ $pageTitle }}</h3>
                        </div>
                    </div>
                    <div class="col-md-3 col-12 text-md-end">
                        <button class="btn btn-primary w-100 w-md-auto" data-bs-toggle="modal" data-bs-target="#addVehicleTypenModal">
                            <i class="ki-duotone ki-plus fs-2"></i> {{ __('app.add_vehicle_types') }}
                        </button>
                    </div>
                </div>
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body pt-0">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_vehicle_types_table">
                    <thead>
                        <tr class="text-center text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th>#</th>
                            <th>{{ __('app.name') }}</th>
                            <th>{{ __('app.action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @foreach ($vehicleTypes as $type)
                        <tr>
                            <td class="text-center">{{ $type->id }}</td>
                            <td class="text-center">{{ $type->name }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    @if ($type->enable)
                                        <form action="{{ route('admin.vehicle-types.deactivate', $type->id) }}" method="POST" class="me-2">
                                            @csrf @method('PUT')
            <button type="submit" class="btn btn-sm btn-light-danger">
<span class="indicator-label">
                    <!-- Using Tabler Icons -->
                  <i class="fa fa-times"></i> {{ __('app.deactivate') }}
                </span>
                <span class="indicator-progress">
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.vehicle-types.activate', $type->id) }}" method="POST" class="me-2">
                                            @csrf @method('PUT')
                                          <button type="submit" class="btn btn-sm btn-light-success">
                <span class="indicator-label">
                    <!-- Using Tabler Icons -->
                  <i class="fa fa-check"></i>
                    {{ __('app.activate') }}
                </span>
                <span class="indicator-progress">
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!--end::Card body-->
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addVehicleTypenModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-5">
            <div class="modal-header border-0">
                <h3 class="modal-title">{{ __('app.add_vehicle_types') }}</h3>
                <button type="button" class="btn btn-sm btn-icon btn-active-light-primary" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i>
                </button>
            </div>
            <form action="{{ route('admin.vehicle-types.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('app.name') }}</label>
                        <input type="text" name="name" class="form-control" placeholder="{{ __('app.name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="enable">
                            <span class="form-check-label">{{ __('app.enabled') }}</span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="submit" class="btn btn-primary">{{ __('global.save') }}</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('global.discard') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
