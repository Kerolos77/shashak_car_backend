@extends('layouts.admin')

@section('title', $pageTitle)

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted"> السائقون </li>
    <span class="bullet bg-gray-300 w-5px h-2px"></span>
    <li class="breadcrumb-item text-dark">تعديل</li>
@endsection

@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxxl">
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header flex-wrap py-5">
                <div class="d-flex align-items-center">
                    <i class="ki-duotone ki-user fs-2x text-primary me-3">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                    </i>
                    <h3 class="text-gray-800 m-0">
                        {{ __('global.edit') }} {{ __('cruds.driver.title_singular') }} #{{ $row->id }}
                    </h3>
                </div>
                <div class="card-toolbar">
                    <a href="{{ route('admin.drivers.show', $row) }}" class="btn btn-light me-3">
                        <i class="ki-duotone ki-eye fs-2 me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        {{ __('global.show') }}
                    </a>
                    <a href="{{ route('admin.drivers.index') }}" class="btn btn-light">
                        <i class="ki-duotone ki-arrow-left fs-2 me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        {{ __('global.back') }}
                    </a>
                </div>
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body pt-0">
                <form action="{{ route('admin.drivers.update', $row) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <!-- Driver Profile Information -->
                        <div class="col-md-6">
                            <div class="card card-flush mb-7">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>{{ __('cruds.driver.fields.driver_information') }}</h2>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="mb-5">
                                        <label class="form-label required">{{ __('cruds.driver.fields.status') }}</label>
                                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                            <option value="pending" {{ old('status', $row->status) == 'pending' ? 'selected' : '' }}>{{ __('cruds.driver.fields.pending') }}</option>
                                            <option value="active" {{ old('status', $row->status) == 'active' ? 'selected' : '' }}>{{ __('cruds.driver.fields.active') }}</option>
                                            <option value="blocked" {{ old('status', $row->status) == 'blocked' ? 'selected' : '' }}>{{ __('cruds.driver.fields.blocked') }}</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-5">
                                        <label class="form-label required">{{ __('cruds.driver.fields.birth_date') }}</label>
                                        <input type="date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror" 
                                               value="{{ old('birth_date', $row->birth_date) }}" required>
                                        @error('birth_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-5">
                                        <label class="form-label required">{{ __('cruds.driver.fields.id_number') }}</label>
                                        <input type="text" name="id_number" class="form-control @error('id_number') is-invalid @enderror" 
                                               value="{{ old('id_number', $row->id_number) }}" required>
                                        @error('id_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-5">
                                        <label class="form-label">{{ __('cruds.driver.fields.criminal_record_image') }}</label>
                                        @if($row->criminal_record_image)
                                            <div class="mb-3">
                                                <img src="{{ asset($row->criminal_record_image) }}" alt="Criminal Record" class="img-thumbnail" style="max-width: 200px;">
                                            </div>
                                        @endif
                                        <input type="file" name="criminal_record_image" class="form-control @error('criminal_record_image') is-invalid @enderror" 
                                               accept="image/*">
                                        @error('criminal_record_image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- User Information -->
                        <div class="col-md-6">
                            <div class="card card-flush mb-7">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>{{ __('cruds.user.fields.user_information') }}</h2>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="mb-5">
                                        <label class="form-label required">{{ __('cruds.user.fields.name') }}</label>
                                        <input type="text" name="user[full_name]" class="form-control @error('user.full_name') is-invalid @enderror" 
                                               value="{{ old('user.full_name', $row->user->full_name) }}" required>
                                        @error('user.full_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-5">
                                        <label class="form-label required">{{ __('cruds.user.fields.email') }}</label>
                                        <input type="email" name="user[email]" class="form-control @error('user.email') is-invalid @enderror" 
                                               value="{{ old('user.email', $row->user->email) }}" required>
                                        @error('user.email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-5">
                                        <label class="form-label">{{ __('cruds.user.fields.phone_number') }}</label>
                                        <input type="text" name="user[phone_number]" class="form-control @error('user.phone_number') is-invalid @enderror" 
                                               value="{{ old('user.phone_number', $row->user->phone_number) }}">
                                        @error('user.phone_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-5">
                                        <label class="form-label">{{ __('cruds.user.fields.wallet_amount') }}</label>
                                        <input type="number" name="user[wallet_amount]" class="form-control @error('user.wallet_amount') is-invalid @enderror" 
                                               value="{{ old('user.wallet_amount', $row->user->wallet_amount) }}" step="0.01">
                                        @error('user.wallet_amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.drivers.index') }}" class="btn btn-light me-3">
                            {{ __('global.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ki-duotone ki-check fs-2 me-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            {{ __('global.update') }}
                        </button>
                    </div>
                </form>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
</div>
@endsection
