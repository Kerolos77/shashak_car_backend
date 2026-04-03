@extends('layouts.admin')

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> الصلاحيات </li>
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
        <li class="breadcrumb-item text-dark">إضافة جديد</li>
    @endsection

@section('content')
<div class="row">
    <div class="card bg-blueGray-100">
        <div class="card-header">
            <div class="card-header-container">
                <h6 class="card-title">
                    {{ trans('global.create') }}
                    {{ trans('cruds.permission.title_singular') }}
                </h6>
            </div>
        </div>

        <div class="card-body">
            @livewire('permission.create')
        </div>
    </div>
</div>
@endsection