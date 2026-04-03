@extends('layouts.admin')

@section('breadcrumbs')
        <li class="breadcrumb-item text-muted"> الأدوار </li>
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
        <li class="breadcrumb-item text-dark">عرض الكل</li>
    @endsection

@section('title', 'Roles')

@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxxl">
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header flex-wrap py-5">
                <div class="row w-100 align-items-center">
                    <div class="col-md-9 col-12 mb-3 mb-md-0">
                        <div class="d-flex align-items-center">
  <i class="ki-duotone ki-element-11 fs-2x text-primary me-3">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <h3 class="text-gray-800 m-0">{{ trans('global.roles') }}</h3>
                        </div>
                    </div>
                    <div class="col-md-3 col-12 text-md-end">
                        @can('role_create')
                        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary w-100 w-md-auto">
                            <i class="ki-duotone ki-plus fs-2"></i> {{ __('global.add') }} {{ __('cruds.role.title_singular') }}
                        </a>
                        @endcan
                    </div>
                </div>
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body pt-0">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped table-row-bordered gy-5 gs-7">
                        <thead>
                            <tr class="fw-semibold fs-6 text-gray-800">
                                <th>ID</th>
                                <th>Title</th>
                                <th>Permissions</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $role)
                                <tr>
                                    <td>{{ $role->id }}</td>
                                    <td>{{ $role->title }}</td>
                                    <td>
                                        @if($role->permissions->count() > 0)
                                            <span class="badge badge-light-primary">{{ $role->permissions->count() }} permissions</span>
                                        @else
                                            <span class="badge badge-light-secondary">No permissions</span>
                                        @endif
                                    </td>
                                    <td>{{ $role->created_at ?? 'N/A' }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            @can('role_show')
                                            <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-sm btn-light-info">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            @endcan

                                            @can('role_edit')
                                            <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-light-warning">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            @endcan

                                            @can('role_delete')
                                            <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this role?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="text-muted">No roles found.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <!--end::Card body-->
        </div>
   </div>
</div>
@endsection