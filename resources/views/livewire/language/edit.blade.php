<form wire:submit.prevent="submit" class="pt-3">

    <div class="form-group {{ $errors->has('language.enable') ? 'invalid' : '' }}">
        <input class="form-control" type="checkbox" name="enable" id="enable" wire:model.defer="language.enable">
        <label class="form-label inline ml-1" for="enable">{{ trans('cruds.language.fields.enable') }}</label>
        <div class="validation-message">
            {{ $errors->first('language.enable') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.language.fields.enable_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('language.code') ? 'invalid' : '' }}">
        <label class="form-label" for="code">{{ trans('cruds.language.fields.code') }}</label>
        <input class="form-control" type="text" name="code" id="code" wire:model.defer="language.code">
        <div class="validation-message">
            {{ $errors->first('language.code') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.language.fields.code_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('language.is_deleted') ? 'invalid' : '' }}">
        <input class="form-control" type="checkbox" name="is_deleted" id="is_deleted" wire:model.defer="language.is_deleted">
        <label class="form-label inline ml-1" for="is_deleted">{{ trans('cruds.language.fields.is_deleted') }}</label>
        <div class="validation-message">
            {{ $errors->first('language.is_deleted') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.language.fields.is_deleted_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('language.is_rtl') ? 'invalid' : '' }}">
        <input class="form-control" type="checkbox" name="is_rtl" id="is_rtl" wire:model.defer="language.is_rtl">
        <label class="form-label inline ml-1" for="is_rtl">{{ trans('cruds.language.fields.is_rtl') }}</label>
        <div class="validation-message">
            {{ $errors->first('language.is_rtl') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.language.fields.is_rtl_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('language.name') ? 'invalid' : '' }}">
        <label class="form-label" for="name">{{ trans('cruds.language.fields.name') }}</label>
        <input class="form-control" type="text" name="name" id="name" wire:model.defer="language.name">
        <div class="validation-message">
            {{ $errors->first('language.name') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.language.fields.name_helper') }}
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.languages.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>