@extends('layouts.admin')

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> التسميات التوضيحية </li>
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
        <li class="breadcrumb-item text-dark">عرض الكل</li>
    @endsection

@section('content')
@section('title', __('global.captions'))


<div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxxl">
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header flex-wrap py-5">
                <div class="row w-100 align-items-center">
                    <!-- Title -->
                    <div class="col-md-9 col-12 mb-3 mb-md-0">
                        <div class="d-flex align-items-center">
                            <i class="ki-duotone ki-element-4 fs-2x text-primary me-3">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>


                            <h3 class="text-gray-800 m-0">{{ __('global.captions') }}</h3>
                        </div>
                    </div>

                    <!-- Semester Select -->



                    <!-- Add User Button -->
                    <div class="col-md-3 col-12 text-md-end">
                        <a href="{{ route('admin.captions.create') }}" class="btn btn-primary w-100 w-md-auto">
                            <i class="ki-duotone ki-plus fs-2"></i> Add Caption
                        </a>
                    </div>
                </div>
            </div>

            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body pt-0" id="users-table-container">


                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_users_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="text-center">#</th>
                            <th class="text-center">{{ __('cruds.caption.fields.caption') }}</th>
                            <th class="text-center">{{ __('cruds.caption.fields.service') }}</th>
                            <th class="text-center">{{ __('cruds.caption.fields.lang') }}</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @forelse ($rows ?? [] as $item)
                            <tr>
                                <td class="text-center">{{ $item->id }}</td>
                                <td class="text-center">{{ $item->caption }}</td>
                                <td class="text-center">{{ @$item->service->title }}</td>
                                <td class="text-center">{{ $item->lang }}</td>

                                <td class="text-center">
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('admin.captions.edit', $item->id) }}"
                                           class="btn btn-sm btn-icon btn-light-primary me-2" title="{{ __('global.edit') }}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.captions.show', $item->id) }}"
                                           class="btn btn-sm btn-icon btn-light-success me-2" title="{{ __('global.show') }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.captions.destroy', $item->id) }}" method="POST"
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
                <!--end::Table-->
                <!--begin::Pagination-->

                <!--end::Pagination-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
    <!--end::Content container-->
</div>
@endsection
