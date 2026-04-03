@extends('layouts.admin')

@section('title', trans('global.create') . ' ' . trans('cruds.faq.title_singular'))
@section('pageName', trans('global.create') . ' ' . trans('cruds.faq.title_singular'))

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> الأسئلة الشائعة </li>
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
        <li class="breadcrumb-item text-dark">إضافة جديد</li>
    @endsection

@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxxl">
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header border-0 py-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 mb-1">
                        {{ trans('global.create') }} {{ trans('cruds.faq.title_singular') }}
                    </span>
                </h3>
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body pt-0">
                <form action="{{ route('admin.faqs.store') }}" method="POST" class="form">
                    @csrf

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">
                            {{ trans('cruds.faq.fields.title') }}
                        </label>
                        <div class="col-lg-8">
                            <input type="text" name="title" class="form-control form-control-solid" 
                                   value="{{ old('title') }}" 
                                   placeholder="{{ trans('cruds.faq.fields.title') }}" required>
                            @error('title')
                                <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">
                            {{ trans('cruds.faq.fields.description') }}
                        </label>
                        <div class="col-lg-8">
                            <textarea name="description" class="form-control form-control-solid" 
                                      rows="5" placeholder="{{ trans('cruds.faq.fields.description') }}" 
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">
                            {{ trans('cruds.faq.fields.enable') }}
                        </label>
                        <div class="col-lg-8 d-flex align-items-center">
                            <div class="form-check form-check-solid form-switch form-check-custom">
                                <input class="form-check-input" type="checkbox" name="enable" 
                                       id="enable" value="1" {{ old('enable') ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable"></label>
                            </div>
                            @error('enable')
                                <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!--begin::Actions-->
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <a href="{{ route('admin.faqs.index') }}" class="btn btn-light btn-active-light-primary me-2">
                            {{ trans('global.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary" id="kt_form_submit">
                            <span class="indicator-label">{{ trans('global.save') }}</span>
                            <span class="indicator-progress">{{ trans('global.please_wait') }}
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

@push('scripts')
<script>
    // Form submission handler
    document.getElementById('kt_form_submit').addEventListener('click', function() {
        this.setAttribute('data-kt-indicator', 'on');
    });
</script>
@endpush