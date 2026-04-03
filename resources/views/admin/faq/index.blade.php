@extends('layouts.admin')

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> الأسئلة الشائعة </li>
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
        <li class="breadcrumb-item text-dark">عرض الكل</li>
    @endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow-lg">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0">{{ trans('cruds.faq.title') }}</h3>
            @can('faq_create')
                <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> {{ trans('global.add') }}
                </a>
            @endcan
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="thead-light">
                        <tr>
                          
                            <th>{{ trans('cruds.faq.fields.id') }}</th>
                            <th>{{ trans('cruds.faq.fields.title') }}</th>
                            <th>{{ trans('cruds.faq.fields.description') }}</th>
                            <th>{{ trans('cruds.faq.fields.enable') }}</th>
                            <th width="150">{{ trans('global.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($faqs as $faq)
                            <tr>
                            
                                <td>{{ $faq->id }}</td>
                                <td>{{ $faq->title }}</td>
                                <td>{{ Str::limit($faq->description, 50) }}</td>
                              <td>
    @if($faq->enable)
        <span class="badge badge-light-success" data-bs-toggle="tooltip" title="{{ trans('global.active_status') }}">
            <i class="fas fa-check-circle me-1"></i>
            {{-- {{ trans('global.active') }} --}}
        </span>
    @else
        <span class="badge badge-light-danger" data-bs-toggle="tooltip" title="{{ trans('global.inactive_status') }}">
            <i class="fas fa-times-circle me-1"></i>
            {{-- {{ trans('global.inactive') }} --}}
        </span>
    @endif
</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        @can('faq_show')
                                            <a href="{{ route('admin.faqs.show', $faq->id) }}" class="btn btn-sm btn-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endcan
                                        @can('faq_edit')
                                            <a href="{{ route('admin.faqs.edit', $faq->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan
                                        @can('faq_delete')
                                            <form action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('{{ trans('global.areYouSure') }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-info-circle mr-2"></i> No FAQs found
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection

