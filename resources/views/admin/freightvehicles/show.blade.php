@extends('layouts.admin')

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> مركبات الشحن </li>
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
        <li class="breadcrumb-item text-dark">عرض التفاصيل</li>
    @endsection

@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxxl">
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header border-0 pt-6">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3">{{ trans('global.view') }} {{ trans('cruds.freightVehicle.title_singular') }}</span>
                </h3>
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Table-->
                <table class="table table-bordered align-middle table-row-dashed fs-6 gy-5">
                    <tbody>
                        <tr>
                            <th class="fw-semibold w-25 text-gray-700">{{ trans('cruds.freightVehicle.fields.id') }}</th>
                            <td>{{ $row->id }}</td>
                        </tr>
                        <tr>
                            <th class="fw-semibold text-gray-700">{{ trans('cruds.freightVehicle.fields.name') }}</th>
                            <td>{{ $row->name }}</td>
                        </tr>
                        <tr>
                            <th class="fw-semibold text-gray-700">{{ trans('cruds.freightVehicle.fields.description') }}</th>
                            <td>{{ $row->description ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="fw-semibold text-gray-700">{{ trans('cruds.freightVehicle.fields.enable') }}</th>
                            <td>
                                <span class="badge {{ $row->enable ? 'badge-light-success' : 'badge-light-danger' }}">
                                    {{ $row->enable ? trans('global.active') : trans('global.inactive') }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th class="fw-semibold text-gray-700">{{ trans('cruds.freightVehicle.fields.height') }}</th>
                            <td>{{ $row->height ? number_format($row->height, 2) . ' cm' : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="fw-semibold text-gray-700">{{ trans('cruds.freightVehicle.fields.width') }}</th>
                            <td>{{ $row->width ? number_format($row->width, 2) . ' cm' : 'N/A' }}</td>
                        </tr>
                        @if($row->length)
                        <tr>
                            <th class="fw-semibold text-gray-700">{{ trans('cruds.freightVehicle.fields.length') }}</th>
                            <td>{{ number_format($row->length, 2) }} cm</td>
                        </tr>
                        @endif
                        <tr>
                            <th class="fw-semibold text-gray-700">{{ trans('cruds.freightVehicle.fields.km_charge') }}</th>
                            <td>{{ $row->km_charge ? number_format($row->km_charge, 2) . ' EGP' : 'N/A' }}</td>
                        </tr>
                        @if($row->thumbnail && count($row->thumbnail) > 0)
                        <tr>
                            <th class="fw-semibold text-gray-700">{{ trans('cruds.freightVehicle.fields.image') }}</th>
                            <td>
                                <div class="d-flex flex-wrap gap-3">
                                    @foreach($row->thumbnail as $image)
                                        <img src="{{ CheckPhoto($image['url']) }}" alt="Freight Vehicle Image" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                <!--end::Table-->

                <!--begin::Actions-->
                <div class="d-flex justify-content-end mt-4">
                    @can('freight_vehicle_edit')
                        <a href="{{ route('admin.freight-vehicles.edit', $row) }}" class="btn btn-primary me-2">
                            {{ trans('global.edit') }}
                        </a>
                    @endcan
                    <a href="{{ route('admin.freight-vehicles.index') }}" class="btn btn-secondary">
                        {{ trans('global.back') }}
                    </a>
                </div>
                <!--end::Actions-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
</div>

@endsection