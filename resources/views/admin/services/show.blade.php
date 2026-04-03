@extends('layouts.admin')

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> الخدمات </li>
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
                    <span class="card-label fw-bold fs-3">{{ trans('global.view') }} {{ trans('cruds.service.title_singular') }}</span>
                </h3>
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Table-->
                <table class="table table-bordered align-middle table-row-dashed fs-6 gy-5">
                    <tbody>
                        <tr>
                            <th class="fw-semibold w-25 text-gray-700">{{ trans('cruds.service.fields.id') }}</th>
                            <td>{{ $row->id }}</td>
                        </tr>
                        <tr>
                            <th class="fw-semibold text-gray-700">{{ trans('cruds.service.fields.title') }}</th>
                            <td>{{ $row->title }}</td>
                        </tr>
                        <tr>
                            <th class="fw-semibold text-gray-700">{{ trans('cruds.service.fields.admin_commission') }}</th>
                            <td>{{ $row->admin_commission ? $row->admin_commission . '%' : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="fw-semibold text-gray-700">{{ trans('cruds.service.fields.enable') }}</th>
                            <td>
                                <span class="badge {{ $row->enable ? 'badge-light-success' : 'badge-light-danger' }}">
                                    {{ $row->enable ? trans('global.active') : trans('global.inactive') }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th class="fw-semibold text-gray-700">{{ trans('cruds.service.fields.intercity_type') }}</th>
                            <td>
                                <span class="badge {{ $row->intercity_type ? 'badge-light-primary' : 'badge-light-info' }}">
                                    {{ $row->intercity_type ? trans('global.out_city') : trans('global.inter_city') }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th class="fw-semibold text-gray-700">{{ trans('cruds.service.fields.km_charge') }}</th>
                            <td>{{ $row->km_charge ? number_format($row->km_charge, 2) . ' EGP' : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="fw-semibold text-gray-700">{{ trans('cruds.service.fields.offer_rate') }}</th>
                            <td>
                                <span class="badge {{ $row->offer_rate ? 'badge-light-warning' : 'badge-light-secondary' }}">
                                    {{ $row->offer_rate ? trans('global.yes') : trans('global.no') }}
                                </span>
                            </td>
                        </tr>
                        @if($row->thumbnail && count($row->thumbnail) > 0)
                        <tr>
                            <th class="fw-semibold text-gray-700">{{ trans('cruds.service.fields.image') }}</th>
                            <td>
                                <div class="d-flex flex-wrap gap-3">
                                    @foreach($row->thumbnail as $image)
                                        <img src="{{ CheckPhoto($image['url']) }}" alt="{{ $row->title }}" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
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
                    <a href="{{ route('admin.services.edit', $row) }}" class="btn btn-primary me-2">
                        {{ trans('global.edit') }}
                    </a>
                    <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">
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