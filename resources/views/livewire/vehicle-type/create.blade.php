<form wire:submit.prevent="submit" class="pt-3">

    <div class="form-group {{ $errors->has('vehicleType.enable') ? 'invalid' : '' }}">
        <input class="form-control" type="checkbox" name="enable" id="enable" wire:model.defer="vehicleType.enable">
        <label class="form-label inline ml-1" for="enable">{{ trans('cruds.vehicleType.fields.enable') }}</label>
        <div class="validation-message">
            {{ $errors->first('vehicleType.enable') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.vehicleType.fields.enable_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('vehicleType.name') ? 'invalid' : '' }}">
        <label class="form-label" for="name">{{ trans('cruds.vehicleType.fields.name') }}</label>
        <input class="form-control" type="text" name="name" id="name" wire:model.defer="vehicleType.name">
        <div class="validation-message">
            {{ $errors->first('vehicleType.name') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.vehicleType.fields.name_helper') }}
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.vehicle-types.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>