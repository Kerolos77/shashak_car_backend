@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="card bg-blueGray-100">
        <div class="card-header">
            <div class="card-header-container">
                <h6 class="card-title">
                    {{ trans('global.view') }}
                    {{ trans('cruds.vehicleType.title_singular') }}:
                    {{ trans('cruds.vehicleType.fields.id') }}
                    {{ $vehicleType->id }}
                </h6>
            </div>
        </div>

        <div class="card-body">
            <div class="pt-3">
                <table class="table table-view">
                    <tbody class="bg-white">
                        <tr>
                            <th>
                                {{ trans('cruds.vehicleType.fields.id') }}
                            </th>
                            <td>
                                {{ $vehicleType->id }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.vehicleType.fields.enable') }}
                            </th>
                            <td>
                                <input class="disabled:opacity-50 disabled:cursor-not-allowed" type="checkbox" disabled {{ $vehicleType->enable ? 'checked' : '' }}>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.vehicleType.fields.name') }}
                            </th>
                            <td>
                                {{ $vehicleType->name }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="form-group">
                @can('vehicle_type_edit')
                    <a href="{{ route('admin.vehicle-types.edit', $vehicleType) }}" class="btn btn-indigo mr-2">
                        {{ trans('global.edit') }}
                    </a>
                @endcan
                <a href="{{ route('admin.vehicle-types.index') }}" class="btn btn-secondary">
                    {{ trans('global.back') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection