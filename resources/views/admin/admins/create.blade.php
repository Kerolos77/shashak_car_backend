@extends('layouts.admin')

@section('title', $pageTitle)
@section('pageName', $pageTitle)

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> المشرفون </li>
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
                    <span class="card-label fw-bold fs-3 mb-1">{{ $pageTitle }}</span>
                </h3>
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body pt-0">
                <form action="{{ route('admin.admins.store') }}" method="POST" enctype="multipart/form-data" class="form">
                    @csrf
                    @method('POST')

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">
                            {{ __('cruds.admin.fields.full_name') }}
                        </label>
                        <div class="col-lg-8">
                            <input type="text" name="full_name" class="form-control form-control-solid" placeholder="{{ __('cruds.admin.fields.full_name') }}">
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">
                            {{ __('cruds.admin.fields.email') }}
                        </label>
                        <div class="col-lg-8">
                            <input type="email" name="email" class="form-control form-control-solid" placeholder="{{ __('cruds.admin.fields.email') }}">
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">
                            {{ __('cruds.admin.fields.password') }}
                        </label>
                        <div class="col-lg-8">
                            <input type="password" name="password" class="form-control form-control-solid" placeholder="{{ __('cruds.admin.fields.password') }}">
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">
                            {{ __('cruds.admin.fields.confirm_password') }}
                        </label>
                        <div class="col-lg-8">
                            <input type="password" name="confirm_password" class="form-control form-control-solid" placeholder="{{ __('cruds.admin.fields.confirm_password') }}">
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">
                            {{ __('cruds.admin.fields.roles') }}
                        </label>
                        <div class="col-lg-8">
                            <select id="multicol-roles" name="roles[]" class="form-select form-select-solid select2" multiple>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!--begin::Actions-->
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <a href="{{ route('admin.admins.index') }}" class="btn btn-light btn-active-light-primary me-2">
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
