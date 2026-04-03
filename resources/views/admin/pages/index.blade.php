@extends('layouts.admin')

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> الصفحات </li>
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
        <li class="breadcrumb-item text-dark">عرض الكل</li>
    @endsection

@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxxl">
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header flex-wrap py-5">
                <div class="row w-100 align-items-center">
                    <div class="col-md-9 col-12 mb-3 mb-md-0">
                        <div class="d-flex align-items-center">
                            <i class="ki-duotone ki-document fs-2x text-primary me-3"></i>
                            <h3 class="text-gray-800 m-0">{{ app()->getLocale()=='en'?$pageTitle:'الصفحات' }}</h3>
                        </div>
                    </div>
                    <div class="col-md-3 col-12 text-md-end">
                        <a href="{{ route('admin.pages.create') }}" class="btn btn-primary w-100 w-md-auto">
                            <i class="ki-duotone ki-plus fs-2"></i> {{ __('global.add') }} {{ __('global.page') }}
                        </a>
                    </div>
                </div>
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body pt-0">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_pages_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="text-center">#</th>
                            <th class="text-center">{{ __('global.name') }}</th>
                            <th class="text-center">{{ __('global.content_en') }}</th>
                            <th class="text-center">{{ __('global.content_ar') }}</th>
                            <th class="text-center">{{ __('global.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @forelse ($rows ?? [] as $item)
                            <tr>
                                <td class="text-center">{{ $item->id }}</td>
                                <td class="text-center">{{ $item->name }}</td>
                                <td class="text-center">{{ Str::limit($item->content_en, 50) }}</td>
                                <td class="text-center">{{ Str::limit($item->content_ar, 50) }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('admin.pages.edit', $item->id) }}"
                                           class="btn btn-sm btn-icon btn-light-primary me-2" title="{{ __('global.edit') }}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.pages.show', $item->id) }}"
                                           class="btn btn-sm btn-icon btn-light-success me-2" title="{{ __('global.show') }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.pages.destroy', $item->id) }}" method="POST"
                                              id="delete-form-{{ $item->id }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-icon btn-light-danger"
                                                onclick="confirmDelete({{ $item->id }})" title="{{ __('global.delete') }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <div class="text-muted">No records found.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!--end::Card body-->
        </div>
    </div>
</div>

@endsection