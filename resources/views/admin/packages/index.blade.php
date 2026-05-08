@extends('layouts.admin')

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted">{{ trans('package.package.title') ?? 'الباقات' }}</li>
    <span class="bullet bg-gray-300 w-5px h-2px"></span>
    <li class="breadcrumb-item text-dark">{{ trans('global.list') }}</li>
@endsection

@section('title', $pageTitle)
@section('pageName', $pageTitle)

@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxxl">
        <div class="card">
            <div class="card-header flex-wrap py-5">
                <div class="row w-100 align-items-center">
                    <div class="col-md-9 col-12 mb-3 mb-md-0">
                        <div class="d-flex align-items-center">
                            <i class="ki-outline ki-shop fs-2x text-primary me-3"></i>
                            <h3 class="text-gray-800 m-0">{{ $pageTitle }}</h3>
                        </div>
                    </div>
                    <div class="col-md-3 col-12 text-md-end">
                        <a href="{{ route('admin.packages.create') }}" class="btn btn-primary w-100 w-md-auto">
                            <i class="ki-outline ki-plus fs-2"></i> {{ __('global.add') }} {{ __('cruds.package.title_singular') }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body pt-0">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <table class="table align-middle table-row-dashed fs-6 gy-5">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="text-center">#</th>
                            <th class="text-center">{{ trans('global.image') ?? 'الصورة' }}</th>
                            <th class="text-center">{{ trans('cruds.package.fields.name') }}</th>
                            <th class="text-center">{{ trans('cruds.package.fields.user_type') }}</th>
                            <th class="text-center">{{ trans('cruds.package.fields.price_points') }}</th>
                            <th class="text-center">{{ trans('cruds.package.fields.price_cash') }}</th>
                            <th class="text-center">{{ trans('cruds.package.fields.duration_hours') }}</th>
                            <th class="text-center">{{ trans('cruds.package.fields.is_active') }}</th>
                            <th class="text-center">{{ trans('global.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @forelse ($rows ?? [] as $item)
                            <tr>
                                <td class="text-center">{{ $item->id }}</td>
                                <td class="text-center">
                                    <div class="symbol symbol-50px">
                                        <img src="{{ $item->photo }}" alt="{{ $item->name }}" class="rounded">
                                    </div>
                                </td>
                                <td class="text-center">{{ $item->name }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $item->user_type == 'driver' ? 'badge-light-primary' : 'badge-light-info' }}">
                                        {{ trans('cruds.package.fields.' . $item->user_type) }}
                                    </span>
                                </td>
                                <td class="text-center">{{ $item->price_points }}</td>
                                <td class="text-center">{{ number_format($item->price_cash, 2) }} ج.م</td>
                                <td class="text-center">{{ $item->duration_hours }} {{ trans('global.hours') ?? 'ساعة' }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $item->is_active ? 'badge-light-success' : 'badge-light-danger' }}">
                                        {{ $item->is_active ? trans('global.active') : trans('global.inactive') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('admin.packages.edit', $item->id) }}"
                                           class="btn btn-sm btn-icon btn-light-primary me-2" title="{{ trans('global.edit') }}">
                                            <i class="ki-outline ki-pencil fs-5"></i>
                                        </a>
                                        <form action="{{ route('admin.packages.destroy', $item->id) }}" method="POST"
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
                                <td colspan="8" class="text-center py-4 text-muted">
                                    {{ trans('global.no_results') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $rows->links() }}
                </div>
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
</script>
@endpush
@endsection
