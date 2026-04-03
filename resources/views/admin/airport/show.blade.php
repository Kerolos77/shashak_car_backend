@extends('layouts.admin')

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> المطارات </li>
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
        <li class="breadcrumb-item text-dark">عرض التفاصيل</li>
    @endsection

@section('content')
<div class="row">
    <div class="card bg-blueGray-100">
        <div class="card-header">
            <div class="card-header-container">
                <h6 class="card-title">
                    {{ trans('global.view') }}
                    {{ trans('cruds.airport.title_singular') }}:
                    {{ trans('cruds.airport.fields.id') }}
                    {{ $airport->id }}
                </h6>
            </div>
        </div>

        <div class="card-body">
            <div class="pt-3">
                <table class="table table-view">
                    <tbody class="bg-white">
                        <tr>
                            <th>
                                {{ trans('cruds.airport.fields.id') }}
                            </th>
                            <td>
                                {{ $airport->id }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.airport.fields.airport_lat') }}
                            </th>
                            <td>
                                {{ $airport->airport_lat }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.airport.fields.airport_lng') }}
                            </th>
                            <td>
                                {{ $airport->airport_lng }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.airport.fields.airport_name') }}
                            </th>
                            <td>
                                {{ $airport->airport_name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.airport.fields.city_location') }}
                            </th>
                            <td>
                                {{ $airport->city_location }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.airport.fields.country') }}
                            </th>
                            <td>
                                {{ $airport->country }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.airport.fields.state') }}
                            </th>
                            <td>
                                {{ $airport->state }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="form-group">
                @can('airport_edit')
                    <a href="{{ route('admin.airports.edit', $airport) }}" class="btn btn-indigo mr-2">
                        {{ trans('global.edit') }}
                    </a>
                @endcan
                <a href="{{ route('admin.airports.index') }}" class="btn btn-secondary">
                    {{ trans('global.back') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection