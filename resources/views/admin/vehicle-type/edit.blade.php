@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="card bg-blueGray-100">
        <div class="card-header">
            <div class="card-header-container">
                <h6 class="card-title">
                    {{ trans('global.edit') }}
                    {{ trans('cruds.vehicleType.title_singular') }}:
                    {{ trans('cruds.vehicleType.fields.id') }}
                    {{ $vehicleType->id }}
                </h6>
            </div>
        </div>

        <div class="card-body">
            @livewire('vehicle-type.edit', [$vehicleType])
        </div>
    </div>
</div>
@endsection