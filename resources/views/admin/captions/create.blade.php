@extends('layouts.admin')

@section('title', __('global.captions'))

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> التسميات التوضيحية </li>
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
                    <span class="card-label fw-bold fs-3 mb-1">{{ __('global.captions') }}</span>
                </h3>
            </div>
            <!--end::Card header-->

          

            <!--begin::Card body-->
            <div class="card-body py-0">
                <form action="{{ route('admin.captions.store') }}" method="POST" enctype="multipart/form-data" class="form">
                    @csrf
                    @method('POST')

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">
                            {{ __('cruds.caption.fields.service') }}
                        </label>
                        <div class="col-lg-8">
                            <select name="service_id" class="form-select form-select-solid select2" required>
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}">{{ $service->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">
                            {{ __('cruds.caption.fields.lang') }}
                        </label>
                        <div class="col-lg-8">
                            <select name="lang" class="form-select form-select-solid select2" required>
                                <option value="ar">عربي</option>
                                <option value="en">English</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">
                            {{ __('cruds.caption.fields.caption') }}
                        </label>
                        <div class="col-lg-8">
                            <textarea name="caption" class="form-control form-control-solid" rows="3" placeholder="{{ __('cruds.caption.fields.caption') }}"></textarea>
                        </div>
                    </div>

                    <!--begin::Actions-->
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <a href="{{ route('admin.captions.index') }}" class="btn btn-light btn-active-light-primary me-2">
                            {{ __('global.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">{{ __('global.save') }}</span>
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
