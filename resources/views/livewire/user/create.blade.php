<form wire:submit.prevent="submit" class="pt-3">
    <div class="row">
        <!-- Name -->
        <div class="col-md-6 mb-5">
            <label class="form-label required fw-semibold fs-6">{{ trans('cruds.user.fields.name') }}</label>
            <input type="text" 
                   class="form-control form-control-solid @error('user.name') is-invalid @enderror" 
                   wire:model.defer="user.name" 
                   required>
            @error('user.name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email -->
        <div class="col-md-6 mb-5">
            <label class="form-label required fw-semibold fs-6">{{ trans('cruds.user.fields.email') }}</label>
            <input type="email" 
                   class="form-control form-control-solid @error('user.email') is-invalid @enderror" 
                   wire:model.defer="user.email" 
                   required>
            @error('user.email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="col-md-6 mb-5">
            <label class="form-label required fw-semibold fs-6">{{ trans('cruds.user.fields.password') }}</label>
            <input type="password" 
                   class="form-control form-control-solid @error('password') is-invalid @enderror" 
                   wire:model.defer="password" 
                   required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Roles -->
        <div class="col-md-6 mb-5">
            <label class="form-label required fw-semibold fs-6">{{ trans('cruds.user.fields.roles') }}</label>
            <x-select-list 
                class="form-control form-control-solid @error('roles') is-invalid @enderror" 
                wire:model="roles" 
                :options="$this->listsForFields['roles']" 
                multiple 
            />
            @error('roles')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Locale -->
        <div class="col-md-6 mb-5">
            <label class="form-label fw-semibold fs-6">{{ trans('cruds.user.fields.locale') }}</label>
            <input type="text" 
                   class="form-control form-control-solid @error('user.locale') is-invalid @enderror" 
                   wire:model.defer="user.locale">
            @error('user.locale')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Country Code -->
        <div class="col-md-6 mb-5">
            <label class="form-label fw-semibold fs-6">{{ trans('cruds.user.fields.country_code') }}</label>
            <input type="text" 
                   class="form-control form-control-solid @error('user.country_code') is-invalid @enderror" 
                   wire:model.defer="user.country_code">
            @error('user.country_code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- FCM Token -->
        <div class="col-md-6 mb-5">
            <label class="form-label fw-semibold fs-6">{{ trans('cruds.user.fields.fcm_token') }}</label>
            <input type="text" 
                   class="form-control form-control-solid @error('user.fcm_token') is-invalid @enderror" 
                   wire:model.defer="user.fcm_token">
            @error('user.fcm_token')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Is Active (checkbox) -->
        <div class="col-md-6 mb-5 d-flex align-items-center">
            <div class="form-check form-switch form-check-custom form-check-solid">
                <input class="form-check-input" 
                       type="checkbox" 
                       id="is_active" 
                       wire:model.defer="user.is_active">
                <label class="form-check-label" for="is_active">{{ trans('cruds.user.fields.is_active') }}</label>
            </div>
            @error('user.is_active')
                <div class="text-danger ms-4">{{ $message }}</div>
            @enderror
        </div>

        <!-- Login Type -->
        <div class="col-md-6 mb-5">
            <label class="form-label fw-semibold fs-6">{{ trans('cruds.user.fields.login_type') }}</label>
            <input type="text" 
                   class="form-control form-control-solid @error('user.login_type') is-invalid @enderror" 
                   wire:model.defer="user.login_type">
            @error('user.login_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Phone Number -->
        <div class="col-md-6 mb-5">
            <label class="form-label fw-semibold fs-6">{{ trans('cruds.user.fields.phone_number') }}</label>
            <input type="text" 
                   class="form-control form-control-solid @error('user.phone_number') is-invalid @enderror" 
                   wire:model.defer="user.phone_number">
            @error('user.phone_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Profile Pic -->
        <div class="col-md-6 mb-5">
            <label class="form-label fw-semibold fs-6">{{ trans('cruds.user.fields.profile_pic') }}</label>
            <input type="text" 
                   class="form-control form-control-solid @error('user.profile_pic') is-invalid @enderror" 
                   wire:model.defer="user.profile_pic">
            @error('user.profile_pic')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Reviews Count -->
        <div class="col-md-6 mb-5">
            <label class="form-label fw-semibold fs-6">{{ trans('cruds.user.fields.reviews_count') }}</label>
            <input type="text" 
                   class="form-control form-control-solid @error('user.reviews_count') is-invalid @enderror" 
                   wire:model.defer="user.reviews_count">
            @error('user.reviews_count')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Reviews Sum -->
        <div class="col-md-6 mb-5">
            <label class="form-label fw-semibold fs-6">{{ trans('cruds.user.fields.reviews_sum') }}</label>
            <input type="text" 
                   class="form-control form-control-solid @error('user.reviews_sum') is-invalid @enderror" 
                   wire:model.defer="user.reviews_sum">
            @error('user.reviews_sum')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Wallet Amount -->
        <div class="col-md-6 mb-5">
            <label class="form-label fw-semibold fs-6">{{ trans('cruds.user.fields.wallet_amount') }}</label>
            <input type="text" 
                   class="form-control form-control-solid @error('user.wallet_amount') is-invalid @enderror" 
                   wire:model.defer="user.wallet_amount">
            @error('user.wallet_amount')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="col-12 text-end mt-5">
            <button type="submit" class="btn btn-primary">
                {{ trans('global.save') }}
            </button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-light ms-2">
                {{ trans('global.cancel') }}
            </a>
        </div>
    </div>
</form>
