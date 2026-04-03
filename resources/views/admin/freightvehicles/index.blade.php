@extends('layouts.admin')

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> مركبات الشحن </li>
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
                            <i class="ki-duotone ki-truck fs-2x text-primary me-3"></i>
                            <h3 class="text-gray-800 m-0">{{ app()->getLocale()=='en'?$pageTitle:'مركبات الشحن' }}</h3>
                        </div>
                    </div>
                    <div class="col-md-3 col-12 text-md-end">
                        <a href="{{ route('admin.freight-vehicles.create') }}" class="btn btn-primary w-100 w-md-auto">
                            <i class="ki-duotone ki-plus fs-2"></i> {{ __('global.add') }} {{ __('cruds.freightVehicle.title_singular') }}
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

                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_freight_vehicles_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="text-center">#</th>
                            <th class="text-center">{{ __('cruds.freightVehicle.fields.image') }}</th>
                            <th class="text-center">{{ __('cruds.freightVehicle.fields.name') }}</th>
                            <th class="text-center">{{ __('cruds.freightVehicle.fields.enable') }}</th>
                            <th class="text-center">{{ __('cruds.freightVehicle.fields.height') }}</th>
                            <th class="text-center">{{ __('cruds.freightVehicle.fields.width') }}</th>
                            <th class="text-center">{{ __('cruds.freightVehicle.fields.km_charge') }}</th>
                            <th class="text-center">{{ __('global.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @forelse ($rows ?? [] as $item)
                            <tr>
                                <td class="text-center">{{ $item->id }}</td>
                                <td class="text-center">
                                    @if($item->thumbnail && count($item->thumbnail) > 0)
                                        <img src="{{ CheckPhoto($item->thumbnail[0]['url']) }}" width="60" height="60" alt="" class="img-thumbnail">
                                    @else
                                        <div class="symbol symbol-60px symbol-circle bg-light">
                                            <i class="ki-duotone ki-truck fs-2x text-muted">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center">{{ $item->name }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $item->enable ? 'badge-light-success' : 'badge-light-danger' }}">
                                        {{ $item->enable ? __('global.active') : __('global.inactive') }}
                                    </span>
                                </td>
                                <td class="text-center">{{ $item->height ? number_format($item->height, 2) . ' cm' : 'N/A' }}</td>
                                <td class="text-center">{{ $item->width ? number_format($item->width, 2) . ' cm' : 'N/A' }}</td>
                                <td class="text-center">{{ $item->km_charge ? number_format($item->km_charge, 2) . ' EGP' : 'N/A' }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('admin.freight-vehicles.edit', $item->id) }}"
                                           class="btn btn-sm btn-icon btn-light-primary me-2" title="{{ __('global.edit') }}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.freight-vehicles.show', $item->id) }}"
                                           class="btn btn-sm btn-icon btn-light-success me-2" title="{{ __('global.show') }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.freight-vehicles.destroy', $item->id) }}" method="POST"
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

@push('scripts')
<script>
function confirmDelete(id) {
    Swal.fire({
        title: '{{ __("global.are_you_sure") }}',
        text: '{{ __("global.you_wont_be_able_to_revert_this") }}',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '{{ __("global.yes_delete_it") }}',
        cancelButtonText: '{{ __("global.cancel") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
</script>
@endpush  
{{-- 

<div class="row">
    <div class="card bg-white">
        <div class="card-header border-b border-blueGray-200">
            <div class="card-header-container">
                <h6 class="card-title">
                    {{ trans('cruds.freightVehicle.title_singular') }}
                    {{ trans('global.list') }}
                </h6>

                @can('freight_vehicle_create')
                    <a class="btn btn-indigo" href="{{ route('admin.freight-vehicles.create') }}">
                        {{ trans('global.add') }} {{ trans('cruds.freightVehicle.title_singular') }}
                    </a>
                @endcan
            </div>
        </div>
        @livewire('freight-vehicle.index')

    </div>
</div>
@endsection --}}