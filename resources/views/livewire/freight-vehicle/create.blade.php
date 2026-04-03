<form wire:submit.prevent="submit" class="pt-3">

    <div class="form-group {{ $errors->has('freightVehicle.description') ? 'invalid' : '' }}">
        <label class="form-label" for="description">{{ trans('cruds.freightVehicle.fields.description') }}</label>
        <textarea class="form-control" name="description" id="description" wire:model.defer="freightVehicle.description" rows="4"></textarea>
        <div class="validation-message">
            {{ $errors->first('freightVehicle.description') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.freightVehicle.fields.description_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('freightVehicle.enable') ? 'invalid' : '' }}">
        <input class="form-control" type="checkbox" name="enable" id="enable" wire:model.defer="freightVehicle.enable">
        <label class="form-label inline ml-1" for="enable">{{ trans('cruds.freightVehicle.fields.enable') }}</label>
        <div class="validation-message">
            {{ $errors->first('freightVehicle.enable') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.freightVehicle.fields.enable_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('freightVehicle.height') ? 'invalid' : '' }}">
        <label class="form-label" for="height">{{ trans('cruds.freightVehicle.fields.height') }}</label>
        <input class="form-control" type="text" name="height" id="height" wire:model.defer="freightVehicle.height">
        <div class="validation-message">
            {{ $errors->first('freightVehicle.height') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.freightVehicle.fields.height_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('freightVehicle.image') ? 'invalid' : '' }}">
        <label class="form-label" for="image">{{ trans('cruds.freightVehicle.fields.image') }}</label>
        <input class="form-control" type="text" name="image" id="image" wire:model.defer="freightVehicle.image">
        <div class="validation-message">
            {{ $errors->first('freightVehicle.image') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.freightVehicle.fields.image_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('freightVehicle.km_charge') ? 'invalid' : '' }}">
        <label class="form-label" for="km_charge">{{ trans('cruds.freightVehicle.fields.km_charge') }}</label>
        <input class="form-control" type="text" name="km_charge" id="km_charge" wire:model.defer="freightVehicle.km_charge">
        <div class="validation-message">
            {{ $errors->first('freightVehicle.km_charge') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.freightVehicle.fields.km_charge_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('freightVehicle.length') ? 'invalid' : '' }}">
        <label class="form-label" for="length">{{ trans('cruds.freightVehicle.fields.length') }}</label>
        <input class="form-control" type="text" name="length" id="length" wire:model.defer="freightVehicle.length">
        <div class="validation-message">
            {{ $errors->first('freightVehicle.length') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.freightVehicle.fields.length_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('freightVehicle.name') ? 'invalid' : '' }}">
        <label class="form-label" for="name">{{ trans('cruds.freightVehicle.fields.name') }}</label>
        <input class="form-control" type="text" name="name" id="name" wire:model.defer="freightVehicle.name">
        <div class="validation-message">
            {{ $errors->first('freightVehicle.name') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.freightVehicle.fields.name_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('freightVehicle.width') ? 'invalid' : '' }}">
        <label class="form-label" for="width">{{ trans('cruds.freightVehicle.fields.width') }}</label>
        <input class="form-control" type="text" name="width" id="width" wire:model.defer="freightVehicle.width">
        <div class="validation-message">
            {{ $errors->first('freightVehicle.width') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.freightVehicle.fields.width_helper') }}
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.freight-vehicles.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>