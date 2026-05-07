@extends('layouts.admin')

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted">{{ trans('cruds.package.title') }}</li>
    <span class="bullet bg-gray-300 w-5px h-2px"></span>
    <li class="breadcrumb-item text-dark">{{ trans('global.edit') }}</li>
@endsection

@section('title', $pageTitle)
@section('pageName', $pageTitle)

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card mb-4">
            <div class="card-header py-3">
                <h4 class="mb-0">{{ trans('global.edit') }} {{ trans('cruds.package.title_singular') }}</h4>
            </div>
            <form action="{{ route('admin.packages.update', $row->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">{{ trans('cruds.package.fields.name') }}</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $row->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">{{ trans('cruds.package.fields.user_type') }}</label>
                            <select name="user_type" class="form-select @error('user_type') is-invalid @enderror" required>
                                <option value="driver" {{ old('user_type', $row->user_type) == 'driver' ? 'selected' : '' }}>{{ trans('cruds.package.fields.driver') }}</option>
                                <option value="user" {{ old('user_type', $row->user_type) == 'user' ? 'selected' : '' }}>{{ trans('cruds.package.fields.user') }}</option>
                            </select>
                            @error('user_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">{{ trans('cruds.package.fields.description') }}</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $row->description) }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label required">{{ trans('cruds.package.fields.duration_hours') }}</label>
                            <input type="number" name="duration_hours" class="form-control @error('duration_hours') is-invalid @enderror" value="{{ old('duration_hours', $row->duration_hours) }}" required>
                            @error('duration_hours') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label required">{{ trans('cruds.package.fields.discount_percentage') }}</label>
                            <div class="input-group">
                                <input type="number" step="0.01" name="discount_percentage" class="form-control @error('discount_percentage') is-invalid @enderror" value="{{ old('discount_percentage', $row->discount_percentage) }}" required>
                                <span class="input-group-text">%</span>
                            </div>
                            @error('discount_percentage') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label required">{{ trans('cruds.package.fields.is_active') }}</label>
                            <div class="form-check form-switch form-check-custom form-check-solid pt-2">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ $row->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">{{ trans('global.active') }}</label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">{{ trans('cruds.package.fields.price_points') }}</label>
                            <input type="number" name="price_points" class="form-control @error('price_points') is-invalid @enderror" value="{{ old('price_points', $row->price_points) }}" required>
                            @error('price_points') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">{{ trans('cruds.package.fields.price_cash') }}</label>
                            <div class="input-group">
                                <input type="number" step="0.01" name="price_cash" class="form-control @error('price_cash') is-invalid @enderror" value="{{ old('price_cash', $row->price_cash) }}" required>
                                <span class="input-group-text">ج.م</span>
                            </div>
                            @error('price_cash') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary px-5">{{ trans('global.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
