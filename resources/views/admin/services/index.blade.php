@extends('layouts.admin')

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> الخدمات </li>
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
                            <i class="ki-duotone ki-briefcase fs-2x text-primary me-3"></i>
                            <h3 class="text-gray-800 m-0">{{ $pageTitle }}</h3>
                        </div>
                    </div>
                    <div class="col-md-3 col-12 text-md-end">
                        <a href="{{ route('admin.services.create') }}" class="btn btn-primary w-100 w-md-auto">
                            <i class="ki-duotone ki-plus fs-2"></i> {{ __('global.add') }} {{ __('cruds.service.title') }}
                        </a>
                    </div>
                </div>
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body pt-0">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_services_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="text-center">#</th>
                            <th class="text-center">{{ trans('global.image') }}</th>
                            <th class="text-center">{{ trans('global.title') }}</th>
                            <th class="text-center">{{ trans('global.admin_commission') }}</th>
                            <th class="text-center">{{ trans('global.enable') }}</th>
                            <th class="text-center">{{ trans('global.intercity_type') }}</th>
                            <th class="text-center">نوع الخدمة (Type)</th>
                            <th class="text-center">{{ trans('global.km_charge') }}</th>
                            <th class="text-center">{{ trans('global.offer_rate') }}</th>
                            <th class="text-center">{{ trans('global.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @forelse ($rows ?? [] as $item)
                            <tr>
                                <td class="text-center">{{ $item->id }}</td>
                                <td class="text-center">
                                    @if($item->thumbnail && is_array($item->thumbnail) && count($item->thumbnail) > 0)
                                        <img src="{{ CheckPhoto($item->thumbnail[0]['url']) }}" width="60" height="60" alt="" class="img-thumbnail">
                                    @else
                                        <div class="symbol symbol-60px symbol-circle bg-light">
                                            <i class="ki-outline ki-briefcase fs-2x text-muted">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center">{{ $item->title }}</td>
                                <td class="text-center">
                                    @if ($item->admin_commission === null)
                                        <span class="badge badge-light-danger">N/A</span>
                                    @else
                                        <span class="badge badge-light-success">{{ $item->admin_commission }}%</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $item->enable ? 'badge-light-success' : 'badge-light-danger' }}">
                                        {{ $item->enable ? trans('global.active') : trans('global.inactive') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $item->intercity_type ? 'badge-light-primary' : 'badge-light-info' }}">
                                        {{ $item->intercity_type ? trans('global.out_city') : trans('global.inter_city') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if ($item->service_type === 'ride')
                                        <span class="badge badge-light-success">توصيل (Ride)</span>
                                    @elseif ($item->service_type === 'travel')
                                        <span class="badge badge-light-warning">سفر (Travel)</span>
                                    @elseif ($item->service_type === 'shipping')
                                        <span class="badge badge-light-primary">شحن (Shipping)</span>
                                    @else
                                        <span class="badge badge-light-secondary">{{ $item->service_type }}</span>
                                    @endif
                                </td>
                                <td class="text-center">{{ $item->km_charge ? number_format($item->km_charge, 2) . ' EGP' : 'N/A' }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $item->offer_rate ? 'badge-light-warning' : 'badge-light-secondary' }}">
                                        {{ $item->offer_rate ? trans('global.yes') : trans('global.no') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center">
                                        <button type="button" class="btn btn-sm btn-icon btn-light-info me-2 show-models-btn"
                                                data-models="{{ json_encode($item->models->pluck('model_name')) }}"
                                                title="Show Models">
                                            <i class="ki-outline ki-category fs-5"></i>
                                        </button>
                                        <a href="{{ route('admin.services.edit', $item->id) }}"
                                           class="btn btn-sm btn-icon btn-light-primary me-2" title="{{ trans('global.edit') }}">
                                            <i class="ki-outline ki-pencil fs-5"></i>
                                        </a>
                                        <a href="{{ route('admin.services.show', $item->id) }}"
                                           class="btn btn-sm btn-icon btn-light-success me-2" title="{{ trans('global.show') }}">
                                            <i class="ki-outline ki-eye fs-5"></i>
                                        </a>
                                        <form action="{{ route('admin.services.destroy', $item->id) }}" method="POST"
                                              id="delete-form-{{ $item->id }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-icon btn-light-danger"
                                                onclick="confirmDelete({{ $item->id }})" title="{{ trans('global.delete') }}">
                                                <i class="ki-outline ki-trash fs-5"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="text-muted">{{ trans('global.no_services_found') }}</div>
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

<!-- Models Modal -->
<div class="modal fade" id="modelsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Service Models</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul id="modelsList" class="list-group">
                    <!-- Models will be injected here -->
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(id) {
    Swal.fire({
        title: '{{ trans("global.are_you_sure") }}',
        text: '{{ trans("global.you_wont_be_able_to_revert_this") }}',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '{{ trans("global.yes_delete_it") }}',
        cancelButtonText: '{{ trans("global.cancel") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}

$(document).ready(function() {
    $('.show-models-btn').click(function() {
        var models = $(this).data('models');
        var list = $('#modelsList');
        list.empty();
        
        if (models && models.length > 0) {
            models.forEach(function(model) {
                list.append('<li class="list-group-item">' + model + '</li>');
            });
        } else {
            list.append('<li class="list-group-item text-muted">No models found</li>');
        }
        
        var modal = new bootstrap.Modal(document.getElementById('modelsModal'));
        modal.show();
    });
});
</script>
@endpush

@endsection
