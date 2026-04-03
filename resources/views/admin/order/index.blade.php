@extends('layouts.admin')

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> الطلبات </li>
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
        <li class="breadcrumb-item text-dark">عرض الكل</li>
    @endsection

@section('content')
<div class="row">
    <div class="card bg-white">
        <div class="card-header border-b border-blueGray-200">
            <div class="card-header-container">
                <h6 class="card-title">
                    {{ trans('cruds.order.title_singular') }}
                    {{ trans('global.list') }}
                </h6>

                @can('order_create')
                    <a class="btn btn-indigo" href="{{ route('admin.orders.create') }}">
                        {{ trans('global.add') }} {{ trans('cruds.order.title_singular') }}
                    </a>
                @endcan
            </div>
        </div>
        @livewire('order.index')

    </div>
</div>
@endsection