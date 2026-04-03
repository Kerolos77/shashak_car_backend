@extends('layouts.admin')

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> الصفحات </li>
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
        <li class="breadcrumb-item text-dark">إضافة جديد</li>
    @endsection

@section('content')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
@endpush
@push('scripts')
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
    <script src="{{ asset('assets/js/forms-editors.js') }}"></script>
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
                <form action="{{ route('admin.pages.store') }}" method="POST" enctype="multipart/form-data" class="form">
                    @csrf
                    @method('POST')

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">
                            {{ __('global.name') }}
                        </label>
                        <div class="col-lg-8">
                            <input type="text" name="name" class="form-control form-control-solid" 
                                   placeholder="{{ __('global.name') }}" value="{{ old('name') }}">
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">
                            {{ __('global.content_en') }}
                        </label>
                        <div class="col-lg-8">
                            <div class="content_en"></div>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">
                            {{ __('global.content_ar') }}
                        </label>
                        <div class="col-lg-8">
                            <div class="content_ar"></div>
                        </div>
                    </div>

                    <!--begin::Actions-->
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <a href="{{ route('admin.pages.index') }}" class="btn btn-light btn-active-light-primary me-2">
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
