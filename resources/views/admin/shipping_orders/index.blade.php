@extends('layouts.admin')

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted">Shipping Orders</li>
    <span class="bullet bg-gray-300 w-5px h-2px"></span>
    <li class="breadcrumb-item text-dark">{{ trans('global.list') }}</li>
@endsection

@section('content')
@section('title', 'Shipping Orders')

<div class="card">
    <div class="card-header pt-6 pb-4 border-0 d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-4">
        <div class="d-flex align-items-center">
            <div class="bg-light-info p-3 rounded-circle me-3">
                <i class="ki-duotone ki-truck fs-2x text-info">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                    <span class="path4"></span>
                    <span class="path5"></span>
                </i>
            </div>
            <div>
                <h3 class="text-gray-900 fw-bold m-0 fs-3">
                    Shipping Orders
                </h3>
                <span class="text-gray-500 fs-7">Manage and monitor freight and shipping requests</span>
            </div>
        </div>
        
        <div>
            @can('order_create')
                <a class="btn btn-info" href="{{ route('admin.orders.create') }}">
                    <i class="ki-outline ki-plus fs-5 me-2"></i>
                    New Shipping Order
                </a>
            @endcan
        </div>
    </div>

    <div class="card-body pt-0">
        @livewire('shipping-order.index')
    </div>
</div>
@endsection
