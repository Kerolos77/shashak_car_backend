<form wire:submit.prevent="submit" class="form">
    <!-- Card Body -->
    <div class="card-body">
        <!-- Description Field -->
        <div class="mb-5 {{ $errors->has('faq.description') ? 'has-error' : '' }}">
            <label for="description" class="form-label required fs-6 fw-bold mb-2">
                {{ trans('cruds.faq.fields.description') }}
            </label>
            <input 
                type="text" 
                class="form-control form-control-lg form-control-solid" 
                id="description" 
                name="description" 
                wire:model.defer="faq.description"
                placeholder="{{ trans('cruds.faq.fields.description') }}"
            />
            
            @if($errors->has('faq.description'))
                <div class="fv-plugins-message-container invalid-feedback">
                    {{ $errors->first('faq.description') }}
                </div>
            @endif
            
            <div class="text-muted mt-1 fs-7">
                {{ trans('cruds.faq.fields.description_helper') }}
            </div>
        </div>

        <!-- Enable Field -->
        <div class="mb-5 {{ $errors->has('faq.enable') ? 'has-error' : '' }}">
            <div class="form-check form-switch form-check-custom form-check-solid">
                <input 
                    class="form-check-input" 
                    type="checkbox" 
                    id="enable" 
                    name="enable" 
                    wire:model.defer="faq.enable"
                />
                <label class="form-check-label fw-bold" for="enable">
                    {{ trans('cruds.faq.fields.enable') }}
                </label>
            </div>
            
            @if($errors->has('faq.enable'))
                <div class="fv-plugins-message-container invalid-feedback">
                    {{ $errors->first('faq.enable') }}
                </div>
            @endif
            
            <div class="text-muted mt-1 fs-7">
                {{ trans('cruds.faq.fields.enable_helper') }}
            </div>
        </div>

        <!-- Title Field -->
        <div class="mb-5 {{ $errors->has('faq.title') ? 'has-error' : '' }}">
            <label for="title" class="form-label required fs-6 fw-bold mb-2">
                {{ trans('cruds.faq.fields.title') }}
            </label>
            <input 
                type="text" 
                class="form-control form-control-lg form-control-solid" 
                id="title" 
                name="title" 
                wire:model.defer="faq.title"
                placeholder="{{ trans('cruds.faq.fields.title') }}"
            />
            
            @if($errors->has('faq.title'))
                <div class="fv-plugins-message-container invalid-feedback">
                    {{ $errors->first('faq.title') }}
                </div>
            @endif
            
            <div class="text-muted mt-1 fs-7">
                {{ trans('cruds.faq.fields.title_helper') }}
            </div>
        </div>
    </div>

    <!-- Card Footer -->
    <div class="card-footer d-flex justify-content-end py-6 px-9">
        <a href="{{ route('admin.faqs.index') }}" class="btn btn-light me-3">
            {{ trans('global.cancel') }}
        </a>
        <button type="submit" class="btn btn-primary">
            <span class="indicator-label">{{ trans('global.save') }}</span>
            <span class="indicator-progress">
                {{ trans('global.saving') }}...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
        </button>
    </div>
</form>