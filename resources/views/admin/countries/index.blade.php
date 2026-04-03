@extends('layouts.admin')

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> الدول </li>
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
        <li class="breadcrumb-item text-dark">عرض الكل</li>
    @endsection

@section('title', $pageTitle)
@section('pageName', $pageTitle)
@section('content')

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxxl">
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header flex-wrap py-5">
                <div class="row w-100 align-items-center">
                    <div class="col-md-9 col-12 mb-3 mb-md-0">
                        <div class="d-flex align-items-center">
                            <i class="ki-duotone ki-flag fs-2x text-primary me-3">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <h3 class="text-gray-800 m-0">{{ $pageTitle }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body pt-0">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_countries_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="text-center">#</th>
                            <th class="text-center">{{ __('app.name') }}</th>
                            <th class="text-center">{{ __('app.continent') }}</th>
                            <th class="text-center">{{ __('app.sub_continent') }}</th>
                            <th class="text-center">{{ __('app.calling_code') }}</th>
                            <th class="text-center">{{ __('app.flag') }}</th>
                            <th class="text-center">{{ __('app.currency') }}</th>
                            <th class="text-center">{{ __('app.status') }}</th>
                            <th class="text-center">{{ __('app.action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @foreach ($countries as $country)
                        <tr>
                            <td class="text-center">{{ $country->id }}</td>
                            <td class="text-center">{{ $country->translate(app()->getLocale())->name }}</td>
                            <td class="text-center">
                                @if ($country->continent)
                                    {{ $country->continent->translate(app()->getLocale())->name }}
                                @else
                                    <span class="badge badge-light-danger">{{ __('app.not_found') }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($country->sub_continent)
                                    {{ $country->sub_continent->translate(app()->getLocale())->name }}
                                @else
                                    <span class="badge badge-light-danger">{{ __('app.not_found') }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                {{ $country->calling_code ?? __('app.not_found') }}
                            </td>
                            <td class="text-center">
                                {{ $country->flag ?? __('app.not_found') }}
                            </td>
                            <td class="text-center">
                                {{ $country->currency_code ?? __('app.not_found') }}
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $country->status == 1 ? 'badge-light-success' : 'badge-light-danger' }}">
                                    {{ $country->status == 1 ? __('app.active') : __('app.inactive') }}
                                </span>
                            </td>
                           <td class="text-center">
    <div class="d-flex justify-content-center">
        @if($country->status == 1)
        <form action="{{ route('admin.countries.deactivate', $country->id) }}" method="POST" class="d-inline">
            @csrf
            @method('PUT')
            <button type="submit" class="btn btn-sm btn-light-danger">
                <span class="indicator-label">
                    <!-- Using Tabler Icons -->
                  <i class="fa fa-times"></i> {{ __('app.deactivate') }}
                </span>
                <span class="indicator-progress">
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
        </form>
        @else
        <form action="{{ route('admin.countries.activate', $country->id) }}" method="POST" class="d-inline">
            @csrf
            @method('PUT')
            <button type="submit" class="btn btn-sm btn-light-success">
                <span class="indicator-label">
                    <!-- Using Tabler Icons -->
                  <i class="fa fa-check"></i>
                    {{ __('app.activate') }}
                </span>
                <span class="indicator-progress">
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
        </form>
        @endif
    </div>
</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center mt-4">
    {{ $countries->links('pagination::bootstrap-4') }}
</div>

            </div>
            <!--end::Card body-->
        </div>
    </div>
</div>

@endsection
