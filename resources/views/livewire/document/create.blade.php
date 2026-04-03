<form wire:submit.prevent="submit" class="pt-3">

    <div class="form-group {{ $errors->has('document.back_side') ? 'invalid' : '' }}">
        <input class="form-control" type="checkbox" name="back_side" id="back_side" wire:model.defer="document.back_side">
        <label class="form-label inline ml-1" for="back_side">{{ trans('cruds.document.fields.back_side') }}</label>
        <div class="validation-message">
            {{ $errors->first('document.back_side') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.document.fields.back_side_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('document.enable') ? 'invalid' : '' }}">
        <input class="form-control" type="checkbox" name="enable" id="enable" wire:model.defer="document.enable">
        <label class="form-label inline ml-1" for="enable">{{ trans('cruds.document.fields.enable') }}</label>
        <div class="validation-message">
            {{ $errors->first('document.enable') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.document.fields.enable_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('document.expire_at') ? 'invalid' : '' }}">
        <input class="form-control" type="checkbox" name="expire_at" id="expire_at" wire:model.defer="document.expire_at">
        <label class="form-label inline ml-1" for="expire_at">{{ trans('cruds.document.fields.expire_at') }}</label>
        <div class="validation-message">
            {{ $errors->first('document.expire_at') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.document.fields.expire_at_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('document.front_side') ? 'invalid' : '' }}">
        <input class="form-control" type="checkbox" name="front_side" id="front_side" wire:model.defer="document.front_side">
        <label class="form-label inline ml-1" for="front_side">{{ trans('cruds.document.fields.front_side') }}</label>
        <div class="validation-message">
            {{ $errors->first('document.front_side') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.document.fields.front_side_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('document.title') ? 'invalid' : '' }}">
        <label class="form-label" for="title">{{ trans('cruds.document.fields.title') }}</label>
        <input class="form-control" type="text" name="title" id="title" wire:model.defer="document.title">
        <div class="validation-message">
            {{ $errors->first('document.title') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.document.fields.title_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('document.is_deleted') ? 'invalid' : '' }}">
        <input class="form-control" type="checkbox" name="is_deleted" id="is_deleted" wire:model.defer="document.is_deleted">
        <label class="form-label inline ml-1" for="is_deleted">{{ trans('cruds.document.fields.is_deleted') }}</label>
        <div class="validation-message">
            {{ $errors->first('document.is_deleted') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.document.fields.is_deleted_helper') }}
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.documents.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>