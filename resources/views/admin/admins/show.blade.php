@extends('layouts.admin')

@section('title', $pageTitle)

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> المشرفون </li>
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
                    <span class="card-label fw-bold fs-3">{{ $pageTitle }}</span>
                </h3>
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Table-->
                <table class="table table-bordered align-middle table-row-dashed fs-6 gy-5">
                    <tbody>
                        <tr>
                            <th class="fw-semibold w-25 text-gray-700">{{ trans('cruds.admin.fields.id') }}</th>
                            <td>{{ $row->id }}</td>
                        </tr>
                        <tr>
                            <th class="fw-semibold text-gray-700">{{ trans('cruds.admin.fields.full_name') }}</th>
                            <td>{{ $row->full_name }}</td>
                        </tr>
                        <tr>
                            <th class="fw-semibold text-gray-700">{{ trans('cruds.admin.fields.email') }}</th>
                            <td>{{ $row->email }}</td>
                        </tr>
                        {{-- Uncomment if needed --}}
                        {{--
                        <tr>
                            <th class="fw-semibold text-gray-700">{{ trans('cruds.admin.fields.country') }}</th>
                            <td>{{ $row->country->cca3 ?? '' }}</td>
                        </tr>
                        <tr>
                            <th class="fw-semibold text-gray-700">{{ trans('cruds.admin.fields.phone_number') }}</th>
                            <td>{{ $row->phone_number }}</td>
                        </tr>
                        <tr>
                            <th class="fw-semibold text-gray-700">{{ trans('cruds.admin.fields.wallet_amount') }}</th>
                            <td>{{ $row->wallet_amount }} EGP</td>
                        </tr>
                        <tr>
                            <th class="fw-semibold text-gray-700">{{ trans('cruds.admin.fields.pending_wallet') }}</th>
                            <td>{{ $row->pending_wallet }} EGP</td>
                        </tr>
                        --}}
                    </tbody>
                </table>
                <!--end::Table-->

                <!--begin::Actions-->
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.admins.edit', $row) }}" class="btn btn-primary me-2">
                        {{ trans('global.edit') }}
                    </a>
                    <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">
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
