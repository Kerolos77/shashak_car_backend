@extends('layouts.admin')

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> مركبات الشحن </li>
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
        <li class="breadcrumb-item text-dark">تعديل</li>
    @endsection

@section('content')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('custom/marketopia-drop-zone/css/style.css') }}" />
@endpush

@push('scripts')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('custom/marketopia-drop-zone/js/script.js') }}"></script>
    <script>
        $(function () {
            $('.select2').each(function () {
                $(this).wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Select value',
                    dropdownParent: $(this).parent()
                });
            });
        });
    </script>
@endpush

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxxl">
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header border-0 py-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 mb-1">{{ $pageTitle }}</span>
                </h3>
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body pt-0">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('admin.freight-vehicles.update', $row->id) }}" method="POST" enctype="multipart/form-data" class="form">
                    @csrf
                    @method('PUT')

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">
                            {{ __('cruds.freightVehicle.fields.name') }}
                        </label>
                        <div class="col-lg-8">
                            <input type="text" name="name" class="form-control form-control-solid"
                                   placeholder="{{ __('cruds.freightVehicle.fields.name') }}" 
                                   value="{{ old('name', $row->name) }}" required>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">
                            {{ __('cruds.freightVehicle.fields.km_charge') }}
                        </label>
                        <div class="col-lg-8">
                            <input type="number" step="0.5" name="km_charge" class="form-control form-control-solid"
                                   placeholder="{{ __('cruds.freightVehicle.fields.km_charge') }}" 
                                   value="{{ old('km_charge', $row->km_charge) }}">
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">
                            {{ __('cruds.freightVehicle.fields.height') }}
                        </label>
                        <div class="col-lg-8">
                            <input type="number" step="0.01" name="height" class="form-control form-control-solid"
                                   placeholder="{{ __('cruds.freightVehicle.fields.height') }}" 
                                   value="{{ old('height', $row->height) }}">
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">
                            {{ __('cruds.freightVehicle.fields.width') }}
                        </label>
                        <div class="col-lg-8">
                            <input type="number" step="0.01" name="width" class="form-control form-control-solid"
                                   placeholder="{{ __('cruds.freightVehicle.fields.width') }}" 
                                   value="{{ old('width', $row->width) }}">
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">
                            {{ __('cruds.freightVehicle.fields.description') }}
                        </label>
                        <div class="col-lg-8">
                            <textarea name="description" class="form-control form-control-solid" rows="3"
                                      placeholder="{{ __('cruds.freightVehicle.fields.description') }}">{{ old('description', $row->description) }}</textarea>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">
                            {{ __('cruds.freightVehicle.fields.enable') }}
                        </label>
                        <div class="col-lg-8 d-flex align-items-center">
                            <div class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" id="enable" name="enable" 
                                       {{ old('enable', $row->enable) ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable">
                                    {{ __('cruds.freightVehicle.fields.enable') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">
                            {{ __('cruds.freightVehicle.fields.image') }}
                        </label>
                        <div class="col-lg-8">
                            <div class="marketopia-dropzone" id="dropzone">
                                <input type="file" hidden name="images[]" id="fileInput" accept="image/*" multiple>
                                <div class="mdz-message" id="mdzMessage">
                                    Drop files here or click to upload
                                    <span class="note">(This is just a demo dropzone. Selected files are <span class="fw-medium">not</span> actually uploaded.)</span>
                                </div>
                                <div id="previewContainer" class="d-flex flex-wrap gap-3 mt-3">
                                    @if($row->thumbnail && count($row->thumbnail) > 0)
                                        @foreach($row->thumbnail as $image)
                                            <div class="preview-image position-relative">
                                                <img src="{{ CheckPhoto($image['url']) }}" alt="Freight Vehicle Image" class="img-thumbnail" style="height: 100px;">
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--begin::Actions-->
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <a href="{{ route('admin.freight-vehicles.index') }}" class="btn btn-light btn-active-light-primary me-2">
                            {{ __('global.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">{{ __('global.update') }}</span>
                            <span class="indicator-progress">{{ __('global.please_wait') }}
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </form>
        </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
</div>

@endsection
