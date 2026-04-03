<div>
    <div class="d-flex align-items-start align-items-sm-center gap-4">
        <img src="https://ui-avatars.com/api/?background=7367f0&color=fff&name={{ auth()->user()->full_name }}" alt="user-avatar" class="d-block w-px-100 h-px-100 rounded" id="uploadedAvatar">
        <div class="button-wrapper">
            <label for="upload" class="btn btn-primary me-2 mb-3 waves-effect waves-light" tabindex="0">
                <span class="d-none d-sm-block">Upload new photo</span>
                <i class="ti ti-upload d-block d-sm-none"></i>
                <input type="file" id="upload" class="account-file-input" hidden="" accept="image/png, image/jpeg">
            </label>
            <button type="button" class="btn btn-label-secondary account-image-reset mb-3 waves-effect">
                <i class="ti ti-refresh-dot d-block d-sm-none"></i>
                <span class="d-none d-sm-block">Reset</span>
            </button>

            <div class="text-muted">Allowed JPG, GIF or PNG. Max size of 800K</div>
        </div>
    </div>
    <br>
    <div class="flex flex-wrap">
        <form wire:submit.prevent="updateProfileInformation" class="w-full">
            <div class="row">
                <div class="mb-3 col-md-6 fv-plugins-icon-container">
                    <label class="form-label" for="full_name">{{ __('global.user_name') }}</label>
                    <input class="form-control" id="full_name" type="text" wire:model.defer="state.full_name" autocomplete="full_name">
                    @error('state.full_name')
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3 col-md-6 fv-plugins-icon-container">
                    <label class="form-label" for="email">{{ __('global.login_email') }}</label>
                    <input class="form-control" id="email" type="text" wire:model.defer="state.email" autocomplete="email">
                    @error('state.state.email')
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mt-2">
                <button class="btn btn-primary me-2 waves-effect waves-light">
                    {{ __('global.save') }}

                </button>
                <div x-data="{ shown: false, timeout: null }" x-init="@this.on('saved', () => { clearTimeout(timeout); shown = true; timeout = setTimeout(() => { shown = false }, 2000);  })" x-show.transition.out.opacity.duration.1500ms="shown" x-transition:leave.opacity.duration.1500ms class="text-sm" style="display: none;">
                    {{ __('global.saved') }}
                </div>
                <button type="reset" class="btn btn-label-secondary waves-effect">{{ __('global.cancel') }}</button>
              </div>
        </form>
    </div>
</div>