@extends('layouts.admin')

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> {{ trans('cruds.user.title') }} </li>
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
        <li class="breadcrumb-item text-dark">{{ trans('global.create') }}</li>
    @endsection

@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        <!--begin::Card-->
        <div class="card">
            <div class="card-header border-0 pt-6">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 mb-1">
                        {{ trans('global.create') }} {{ trans('cruds.user.title_singular') }}
                    </span>
                </h3>
            </div>
            <div class="card-body pt-0">
               @livewire('user.create')
            </div>
        </div>
        <!--end::Card-->
    </div>
</div>
@endsection
