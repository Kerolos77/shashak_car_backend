@extends('layouts.admin')

@section('title', __('global.create') . ' ' . __('cruds.role.title_singular'))

@section('breadcrumbs')
        <li class="breadcrumb-item text-muted"> الأدوار </li>
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
        <li class="breadcrumb-item text-dark">إضافة جديد</li>
    @endsection

@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxxl">
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header flex-wrap py-5">
                <div class="d-flex align-items-center">
                    <i class="ki-duotone ki-element-4 fs-2x text-primary me-3">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                    </i>
                    <h3 class="text-gray-800 m-0">{{ __('global.create') }} {{ __('cruds.role.title_singular') }}</h3>
                </div>
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body pt-0">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa fa-exclamation-triangle me-2"></i>
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('admin.roles.store') }}" method="POST" class="pt-5">
                    @csrf
                    
                    <div class="mb-5">
                        <label class="form-label required">{{ trans('cruds.role.fields.title') }}</label>
                        <input type="text" 
                               name="title" 
                               class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title') }}" 
                               required>
                        @error('title')
                            <div class="invalid-feedback d-block">
                                <i class="fa fa-exclamation-circle me-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">{{ trans('cruds.role.fields.title_helper') }}</div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label">{{ trans('cruds.role.fields.permissions') }}</label>
                        <select name="permissions[]" 
                                id="permissions" 
                                class="form-control @error('permissions') is-invalid @enderror" 
                                multiple 
                                style="height: 200px;">
                            @foreach($permissions as $permission)
                                <option value="{{ $permission->id }}" 
                                        {{ in_array($permission->id, old('permissions', [])) ? 'selected' : '' }}>
                                    {{ $permission->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('permissions')
                            <div class="invalid-feedback d-block">
                                <i class="fa fa-exclamation-circle me-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        @error('permissions.*')
                            <div class="invalid-feedback d-block">
                                <i class="fa fa-exclamation-circle me-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">{{ trans('cruds.role.fields.permissions_helper') }}</div>
                        <small class="text-muted">Total permissions available: {{ $permissions->count() }}</small>
                    </div>

                    <div class="d-flex justify-content-start gap-3">
                        <button class="btn btn-primary" type="submit">
                            <i class="fa fa-save me-1"></i> {{ trans('global.save') }}
                        </button>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-light">
                            {{ trans('global.cancel') }}
                        </a>
                    </div>
                </form>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
</div>
@endsection