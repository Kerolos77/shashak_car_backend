<form wire:submit.prevent="submit" class="pt-3">

    <div class="form-group {{ $errors->has('currency.code') ? 'invalid' : '' }}">
        <label class="form-label" for="code">{{ trans('cruds.currency.fields.code') }}</label>
        <input class="form-control" type="text" name="code" id="code" wire:model.defer="currency.code">
        <div class="validation-message">
            {{ $errors->first('currency.code') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.currency.fields.code_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('currency.decimal_digits') ? 'invalid' : '' }}">
        <label class="form-label required" for="decimal_digits">{{ trans('cruds.currency.fields.decimal_digits') }}</label>
        <input class="form-control" type="text" name="decimal_digits" id="decimal_digits" required wire:model.defer="currency.decimal_digits">
        <div class="validation-message">
            {{ $errors->first('currency.decimal_digits') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.currency.fields.decimal_digits_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('currency.enable') ? 'invalid' : '' }}">
        <input class="form-control" type="checkbox" name="enable" id="enable" wire:model.defer="currency.enable">
        <label class="form-label inline ml-1" for="enable">{{ trans('cruds.currency.fields.enable') }}</label>
        <div class="validation-message">
            {{ $errors->first('currency.enable') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.currency.fields.enable_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('currency.name') ? 'invalid' : '' }}">
        <label class="form-label required" for="name">{{ trans('cruds.currency.fields.name') }}</label>
        <input class="form-control" type="text" name="name" id="name" required wire:model.defer="currency.name">
        <div class="validation-message">
            {{ $errors->first('currency.name') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.currency.fields.name_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('currency.symbol') ? 'invalid' : '' }}">
        <label class="form-label required" for="symbol">{{ trans('cruds.currency.fields.symbol') }}</label>
        <input class="form-control" type="text" name="symbol" id="symbol" required wire:model.defer="currency.symbol">
        <div class="validation-message">
            {{ $errors->first('currency.symbol') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.currency.fields.symbol_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('currency.symbol_at_right') ? 'invalid' : '' }}">
        <input class="form-control" type="checkbox" name="symbol_at_right" id="symbol_at_right" required wire:model.defer="currency.symbol_at_right">
        <label class="form-label inline ml-1 required" for="symbol_at_right">{{ trans('cruds.currency.fields.symbol_at_right') }}</label>
        <div class="validation-message">
            {{ $errors->first('currency.symbol_at_right') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.currency.fields.symbol_at_right_helper') }}
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.currencies.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>