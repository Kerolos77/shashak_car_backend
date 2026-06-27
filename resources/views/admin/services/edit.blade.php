@extends('layouts.admin')

@section('title', $pageTitle)
@section('pageName', $pageTitle)

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> الخدمات </li>
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
        <li class="breadcrumb-item text-dark">تعديل</li>
    @endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('custom/marketopia-drop-zone/css/style.css') }}" />
@endpush

@push('scripts')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('custom/marketopia-drop-zone/js/script.js') }}"></script>
    <script>
        $(function() {
            var select2 = $('.select2');
            if (select2.length) {
                select2.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Select value',
                        dropdownParent: $this.parent()
                    });
                });
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            const commissionTypeCheckbox = document.getElementById('commissionTypeCheckbox');
            const adminCommissionInput = document.getElementById('adminCommissionInput');

            function toggleAdminCommissionInput() {
                if (commissionTypeCheckbox.checked) {
                    adminCommissionInput.classList.remove('d-none');
                } else {
                    adminCommissionInput.classList.add('d-none');
                }
            }

            commissionTypeCheckbox.addEventListener('change', toggleAdminCommissionInput);
            toggleAdminCommissionInput();
        });

        // Models Repeater Logic
        $(document).on('click', '#add-model-btn', function() {
            var newRow = `
                <div class="model-row mb-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input type="text" name="models[]" class="form-control form-control-solid" placeholder="Model Name">
                        </div>
                        <div class="col-md-5">
                            <input type="number" name="min_years[]" class="form-control form-control-solid" placeholder="Minimum Year" min="1900" max="2100">
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-light-danger w-100 remove-model-btn" type="button">
                                <i class="ki-outline ki-trash fs-5"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            $('#models-container').append(newRow);
        });

        $(document).on('click', '.remove-model-btn', function() {
            $(this).closest('.model-row').remove();
        });
    </script>
@endpush

@section('content')
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

                <form action="{{ route('admin.services.update', $row->id) }}" method="POST" enctype="multipart/form-data" class="form">
                    @csrf
                    @method('PUT')

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">
                            {{ __('cruds.service.fields.title') }}
                        </label>
                        <div class="col-lg-8">
                            <input type="text" name="title" class="form-control form-control-solid" 
                                   placeholder="{{ __('cruds.service.fields.title') }}" 
                                   value="{{ old('title', $row->title) }}" required>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">
                            {{ __('cruds.service.fields.km_charge') }}
                        </label>
                        <div class="col-lg-8">
                            <input type="number" name="km_charge" step="0.5" 
                                   class="form-control form-control-solid" 
                                   placeholder="{{ __('cruds.service.fields.km_charge') }}"
                                   value="{{ old('km_charge', $row->km_charge) }}">
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">
                            {{ __('cruds.service.fields.status') }}
                        </label>
                        <div class="col-lg-8 d-flex align-items-center">
                            <div class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" id="enable" name="enable" 
                                       {{ old('enable', $row->enable) ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable">
                                    {{ __('cruds.service.fields.enable') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">
                            {{ __('cruds.service.fields.offer_rate') }}
                        </label>
                        <div class="col-lg-8 d-flex align-items-center">
                            <div class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" id="offer_rate" name="offer_rate" 
                                       {{ old('offer_rate', $row->offer_rate) ? 'checked' : '' }}>
                                <label class="form-check-label" for="offer_rate">
                                    {{ __('cruds.service.fields.offer_rate') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">
                            {{ __('cruds.service.fields.intercity_type') }}
                        </label>
                        <div class="col-lg-8 d-flex align-items-center">
                            <div class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" id="intercity_type" name="intercity_type" 
                                       {{ old('intercity_type', $row->intercity_type) ? 'checked' : '' }}>
                                <label class="form-check-label" for="intercity_type">
                                    {{ __('cruds.service.fields.intercity_type') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">
                            نوع الخدمة (Service Type)
                        </label>
                        <div class="col-lg-8">
                            <select name="service_type" class="form-select form-select-solid" required>
                                <option value="ride" {{ old('service_type', $row->service_type) == 'ride' ? 'selected' : '' }}>توصيل أشخاص (Ride)</option>
                                <option value="travel" {{ old('service_type', $row->service_type) == 'travel' ? 'selected' : '' }}>سفر (Travel)</option>
                                <option value="shipping" {{ old('service_type', $row->service_type) == 'shipping' ? 'selected' : '' }}>شحن (Shipping)</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">
                            {{ __('cruds.service.fields.image') }}
                        </label>
                        <div class="col-lg-8">
                            <div class="marketopia-dropzone" id="dropzone">
                                <input type="file" hidden name="images[]" id="fileInput" accept="image/*" multiple>
                                <div class="mdz-message" id="mdzMessage">
                                    Drop files here or click to upload
                                    <span class="note">(This is just a demo dropzone. Selected files are <span
                                            class="fw-medium">not</span> actually uploaded.)</span>
                                </div>
                                <div id="previewContainer" class="d-flex flex-wrap gap-3 mt-3">
                                    {{-- @if($row->images)
                                        @foreach($row->images as $image)
                                            <div class="preview-image position-relative">
                                                <img src="{{ asset('storage/'.$image) }}" alt="Service Image" class="img-thumbnail" style="height: 100px;">
                                                <!-- يمكنك اضافة زر حذف هنا اذا رغبت -->
                                            </div>
                                        @endforeach
                                    @endif --}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">
                            {{ __('cruds.service.fields.commission_type') }}
                        </label>
                        <div class="col-lg-8 d-flex align-items-center">
                            <div class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" id="commissionTypeCheckbox" name="commission_type" 
                                       {{ old('commission_type', $row->commission_type) ? 'checked' : '' }}>
                                <label class="form-check-label" for="commissionTypeCheckbox">
                                    {{ __('cruds.service.fields.commission_type') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-6 {{ old('commission_type', $row->commission_type) ? '' : 'd-none' }}" id="adminCommissionInput">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">
                            {{ __('cruds.service.fields.admin_commission') }}
                        </label>
                        <div class="col-lg-8">
                            <input type="number" name="admin_commission" step="0.5" 
                                   class="form-control form-control-solid" 
                                   placeholder="{{ __('cruds.service.fields.admin_commission') }}"
                                   value="{{ old('admin_commission', $row->admin_commission) }}">
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">
                            Models
                        </label>
                        <div class="col-lg-8">
                            <div id="models-container">
                                @forelse($row->models as $model)
                                    <div class="model-row mb-3">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <input type="text" name="models[]" class="form-control form-control-solid" placeholder="Model Name" value="{{ $model->model_name }}">
                                            </div>
                                            <div class="col-md-5">
                                                <input type="number" name="min_years[]" class="form-control form-control-solid" placeholder="Minimum Year" min="1900" max="2100" value="{{ $model->min_year }}">
                                            </div>
                                            <div class="col-md-1">
                                                <button class="btn btn-light-danger w-100 remove-model-btn" type="button">
                                                    <i class="ki-outline ki-trash fs-5"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="model-row mb-3">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <input type="text" name="models[]" class="form-control form-control-solid" placeholder="Model Name">
                                            </div>
                                            <div class="col-md-5">
                                                <input type="number" name="min_years[]" class="form-control form-control-solid" placeholder="Minimum Year" min="1900" max="2100">
                                            </div>
                                            <div class="col-md-1">
                                                <button class="btn btn-light-danger w-100 remove-model-btn" type="button">
                                                    <i class="ki-outline ki-trash fs-5"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                            <button type="button" class="btn btn-light-primary btn-sm mt-2" id="add-model-btn">
                                <i class="ki-outline ki-plus fs-7"></i> Add Model
                            </button>
                        </div>
                    </div>

                    <!--begin::Actions-->
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <a href="{{ route('admin.services.index') }}" class="btn btn-light btn-active-light-primary me-2">
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
