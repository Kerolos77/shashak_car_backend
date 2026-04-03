<form wire:submit.prevent="submit" class="pt-3">

    <div class="form-group {{ $errors->has('tax.country') ? 'invalid' : '' }}">
        <label class="form-label" for="country">{{ trans('cruds.tax.fields.country') }}</label>
        <input class="form-control" type="text" name="country" id="country" wire:model.defer="tax.country">
        <div class="validation-message">
            {{ $errors->first('tax.country') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.tax.fields.country_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('tax.enable') ? 'invalid' : '' }}">
        <input class="form-control" type="checkbox" name="enable" id="enable" wire:model.defer="tax.enable">
        <label class="form-label inline ml-1" for="enable">{{ trans('cruds.tax.fields.enable') }}</label>
        <div class="validation-message">
            {{ $errors->first('tax.enable') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.tax.fields.enable_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('tax.tax') ? 'invalid' : '' }}">
        <label class="form-label" for="tax">{{ trans('cruds.tax.fields.tax') }}</label>
        <input class="form-control" type="text" name="tax" id="tax" wire:model.defer="tax.tax">
        <div class="validation-message">
            {{ $errors->first('tax.tax') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.tax.fields.tax_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('tax.title') ? 'invalid' : '' }}">
        <label class="form-label" for="title">{{ trans('cruds.tax.fields.title') }}</label>
        <input class="form-control" type="text" name="title" id="title" wire:model.defer="tax.title">
        <div class="validation-message">
            {{ $errors->first('tax.title') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.tax.fields.title_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('tax.type') ? 'invalid' : '' }}">
        <label class="form-label" for="type">{{ trans('cruds.tax.fields.type') }}</label>
        <input class="form-control" type="text" name="type" id="type" wire:model.defer="tax.type">
        <div class="validation-message">
            {{ $errors->first('tax.type') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.tax.fields.type_helper') }}
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.taxes.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>