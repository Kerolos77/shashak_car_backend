@extends('layouts.admin')

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> الطلبات </li>
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
        <li class="breadcrumb-item text-dark">تعديل</li>
    @endsection

@section('content')
<div class="row">
    <div class="card bg-blueGray-100">
        <div class="card-header">
            <div class="card-header-container">
                <h6 class="card-title">
                    {{ trans('global.edit') }}
                    {{ trans('cruds.order.title_singular') }}:
                    {{ trans('cruds.order.fields.id') }}
                    {{ $order->id }}
                </h6>
            </div>
        </div>

        <div class="card-body">
            @livewire('order.edit', [$order])
        </div>
    </div>
</div>
@endsection