@extends('layouts.admin')

@section('title', __('global.edit') . ' ' . __('cruds.user.title_singular'))

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> {{ trans('cruds.user.title') }} </li>
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
        <li class="breadcrumb-item text-dark">{{ trans('global.edit') }}</li>
    @endsection

@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxxl">
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header flex-wrap py-5">
                <div class="d-flex align-items-center">
                    <i class="ki-duotone ki-user fs-2x text-primary me-3">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                    </i>
                    <h3 class="text-gray-800 m-0">
                        {{ __('global.edit') }} {{ __('cruds.user.title_singular') }} #{{ $user->id }}
                    </h3>
                </div>
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body pt-0">
                @livewire('user.edit', ['user' => $user])
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
</div>
@endsection
