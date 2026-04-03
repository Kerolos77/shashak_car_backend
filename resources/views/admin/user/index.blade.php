@extends('layouts.admin')

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> {{ trans('cruds.user.title') }} </li>
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
        <li class="breadcrumb-item text-dark">{{ trans('global.list') }}</li>
    @endsection

@section('title', __('cruds.user.title'))

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxxl">

            <div class="card">

                <div class="card-header flex-wrap py-5">
                    <div class="row w-100 align-items-center">
                        <!-- Title -->
                        <div class="col-md-9 col-12 mb-3 mb-md-0">
                            <div class="d-flex align-items-center">
                                <i class="ki-duotone ki-user-square fs-2x text-primary me-3">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>



                                <h3 class="text-gray-800 m-0">{{ __('cruds.user.title') }}</h3>
                            </div>
                        </div>

                        <!-- Semester Select -->



                        <!-- Add User Button -->
                        <div class="col-md-3 col-12 text-md-end">
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary w-100 w-md-auto">
                                <i class="ki-duotone ki-plus fs-2"></i> {{ trans('global.add') }}
                                {{ trans('cruds.user.title_singular') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0">
                    @livewire('user.index') <!-- Calls the Livewire component -->
                </div>
            </div>
        </div>
    </div>
@endsection
