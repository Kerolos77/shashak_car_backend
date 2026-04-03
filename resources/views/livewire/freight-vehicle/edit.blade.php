<form wire:submit.prevent="submit" class="pt-3">
    @csrf

    <x-form-group for="name" :label="trans('cruds.freightVehicle.fields.name')" :error="$errors->first('freightVehicle.name')">
        <input class="form-control" id="name" type="text" wire:model.defer="freightVehicle.name">
    </x-form-group>

    <x-form-group for="description" :label="trans('cruds.freightVehicle.fields.description')" :error="$errors->first('freightVehicle.description')">
        <textarea class="form-control" id="description" rows="4" wire:model.defer="freightVehicle.description"></textarea>
    </x-form-group>

    <x-form-group for="enable">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="enable" wire:model.defer="freightVehicle.enable">
            <label class="form-check-label" for="enable">{{ trans('cruds.freightVehicle.fields.enable') }}</label>
        </div>
    </x-form-group>

    <x-form-group for="height" :label="trans('cruds.freightVehicle.fields.height')" :error="$errors->first('freightVehicle.height')">
        <input class="form-control" id="height" type="text" wire:model.defer="freightVehicle.height">
    </x-form-group>

    <x-form-group for="width" :label="trans('cruds.freightVehicle.fields.width')" :error="$errors->first('freightVehicle.width')">
        <input class="form-control" id="width" type="text" wire:model.defer="freightVehicle.width">
    </x-form-group>

    <x-form-group for="length" :label="trans('cruds.freightVehicle.fields.length')" :error="$errors->first('freightVehicle.length')">
        <input class="form-control" id="length" type="text" wire:model.defer="freightVehicle.length">
    </x-form-group>

    <x-form-group for="image" :label="trans('cruds.freightVehicle.fields.image')" :error="$errors->first('freightVehicle.image')">
        <input class="form-control" id="image" type="text" wire:model.defer="freightVehicle.image">
    </x-form-group>

    <x-form-group for="km_charge" :label="trans('cruds.freightVehicle.fields.km_charge')" :error="$errors->first('freightVehicle.km_charge')">
        <input class="form-control" id="km_charge" type="text" wire:model.defer="freightVehicle.km_charge">
    </x-form-group>

    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.freight-vehicles.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>
