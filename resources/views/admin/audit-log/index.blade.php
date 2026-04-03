@extends('layouts.admin')

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> سجل التدقيق </li>
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
        <li class="breadcrumb-item text-dark">عرض الكل</li>
    @endsection

@section('content')
<div class="row">
    <div class="card bg-white">
        <div class="card-header border-b border-blueGray-200">
            <div class="card-header-container">
                <h6 class="card-title">
                    {{ trans('cruds.auditLog.title_singular') }}
                    {{ trans('global.list') }}
                </h6>

                @can('audit_log_create')
                    <a class="btn btn-indigo" href="{{ route('admin.audit-logs.create') }}">
                        {{ trans('global.add') }} {{ trans('cruds.auditLog.title_singular') }}
                    </a>
                @endcan
            </div>
        </div>
        @livewire('audit-log.index')

    </div>
</div>
@endsection