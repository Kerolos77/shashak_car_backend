<form wire:submit.prevent="submit" class="form">
    <!--begin::Card body-->
    <div class="card-body">
        <!-- Description Field -->
        <div class="mb-10 {{ $errors->has('faq.description') ? 'has-error' : '' }}">
            <label for="description" class="form-label required">{{ trans('cruds.faq.fields.description') }}</label>
            <input type="text" 
                   class="form-control form-control-solid" 
                   id="description" 
                   name="description" 
                   wire:model.defer="faq.description"
                   placeholder="{{ trans('cruds.faq.fields.description') }}" />
            
            @if($errors->has('faq.description'))
                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                    <div data-field="description" data-validator="notEmpty">{{ $errors->first('faq.description') }}</div>
                </div>
            @endif
            
            <div class="text-muted fs-7 mt-1">{{ trans('cruds.faq.fields.description_helper') }}</div>
        </div>

        <!-- Enable Field -->
        <div class="mb-10 {{ $errors->has('faq.enable') ? 'has-error' : '' }}">
            <div class="form-check form-check-custom form-check-solid">
                <input class="form-check-input" 
                       type="checkbox" 
                       id="enable" 
                       name="enable" 
                       wire:model.defer="faq.enable" />
                <label class="form-check-label" for="enable">
                    {{ trans('cruds.faq.fields.enable') }}
                </label>
            </div>
            
            @if($errors->has('faq.enable'))
                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                    <div data-field="enable">{{ $errors->first('faq.enable') }}</div>
                </div>
            @endif
            
            <div class="text-muted fs-7 mt-1">{{ trans('cruds.faq.fields.enable_helper') }}</div>
        </div>

        <!-- Title Field -->
        <div class="mb-10 {{ $errors->has('faq.title') ? 'has-error' : '' }}">
            <label for="title" class="form-label required">{{ trans('cruds.faq.fields.title') }}</label>
            <input type="text" 
                   class="form-control form-control-solid" 
                   id="title" 
                   name="title" 
                   wire:model.defer="faq.title"
                   placeholder="{{ trans('cruds.faq.fields.title') }}" />
            
            @if($errors->has('faq.title'))
                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                    <div data-field="title" data-validator="notEmpty">{{ $errors->first('faq.title') }}</div>
                </div>
            @endif
            
            <div class="text-muted fs-7 mt-1">{{ trans('cruds.faq.fields.title_helper') }}</div>
        </div>
    </div>
    <!--end::Card body-->

    <!--begin::Card footer-->
    <div class="card-footer d-flex justify-content-end py-6 px-9">
        <a href="{{ route('admin.faqs.index') }}" class="btn btn-light me-3">
            {{ trans('global.cancel') }}
        </a>
        <button type="submit" class="btn btn-primary">
            <span class="indicator-label">{{ trans('global.save') }}</span>
            <span class="indicator-progress">
                Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
        </button>
    </div>
    <!--end::Card footer-->
</form>