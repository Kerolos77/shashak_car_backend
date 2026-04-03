@extends('layouts.admin')

@section('title', __('global.show') . ' ' . __('cruds.role.title_singular'))

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted"> الأدوار </li>
    <span class="bullet bg-gray-300 w-5px h-2px"></span>
    <li class="breadcrumb-item text-dark">عرض</li>
@endsection

@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxxl">
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header flex-wrap py-5">
                <div class="d-flex align-items-center">
                    <i class="ki-duotone ki-element-11 fs-2x text-primary me-3">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <h3 class="text-gray-800 m-0">{{ __('global.show') }} {{ __('cruds.role.title_singular') }}</h3>
                </div>
                <div class="card-toolbar">
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-light">
                        <i class="fa fa-arrow-left me-1"></i> {{ trans('global.back') }}
                    </a>
                </div>
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body pt-0">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-5">
                            <label class="form-label fw-bold">{{ trans('cruds.role.fields.title') }}</label>
                            <div class="form-control-plaintext">{{ $role->title }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-5">
                            <label class="form-label fw-bold">Created At</label>
                            <div class="form-control-plaintext">{{ $role->created_at ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="form-label fw-bold">{{ trans('cruds.role.fields.permissions') }}</label>
                    @if($role->permissions->count() > 0)
                        <div class="row">
                            @foreach($role->permissions as $permission)
                                <div class="col-md-4 col-sm-6 mb-2">
                                    <span class="badge badge-light-primary">{{ $permission->title }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3">
                            <small class="text-muted">Total: {{ $role->permissions->count() }} permissions</small>
                        </div>
                    @else
                        <div class="text-muted">No permissions assigned</div>
                    @endif
                </div>

                <div class="d-flex justify-content-start gap-3">
                    @can('role_edit')
                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-warning">
                        <i class="fa fa-edit me-1"></i> {{ trans('global.edit') }}
                    </a>
                    @endcan

                    @can('role_delete')
                    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this role?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fa fa-trash me-1"></i> {{ trans('global.delete') }}
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
</div>
@endsection