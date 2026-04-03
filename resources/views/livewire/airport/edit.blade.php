<form wire:submit.prevent="submit" class="pt-3">

    <div class="form-group {{ $errors->has('airport.airport_lat') ? 'invalid' : '' }}">
        <label class="form-label" for="airport_lat">{{ trans('cruds.airport.fields.airport_lat') }}</label>
        <input class="form-control" type="text" name="airport_lat" id="airport_lat" wire:model.defer="airport.airport_lat">
        <div class="validation-message">
            {{ $errors->first('airport.airport_lat') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.airport.fields.airport_lat_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('airport.airport_lng') ? 'invalid' : '' }}">
        <label class="form-label" for="airport_lng">{{ trans('cruds.airport.fields.airport_lng') }}</label>
        <input class="form-control" type="text" name="airport_lng" id="airport_lng" wire:model.defer="airport.airport_lng">
        <div class="validation-message">
            {{ $errors->first('airport.airport_lng') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.airport.fields.airport_lng_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('airport.airport_name') ? 'invalid' : '' }}">
        <label class="form-label" for="airport_name">{{ trans('cruds.airport.fields.airport_name') }}</label>
        <input class="form-control" type="text" name="airport_name" id="airport_name" wire:model.defer="airport.airport_name">
        <div class="validation-message">
            {{ $errors->first('airport.airport_name') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.airport.fields.airport_name_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('airport.city_location') ? 'invalid' : '' }}">
        <label class="form-label" for="city_location">{{ trans('cruds.airport.fields.city_location') }}</label>
        <input class="form-control" type="text" name="city_location" id="city_location" wire:model.defer="airport.city_location">
        <div class="validation-message">
            {{ $errors->first('airport.city_location') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.airport.fields.city_location_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('airport.country') ? 'invalid' : '' }}">
        <label class="form-label" for="country">{{ trans('cruds.airport.fields.country') }}</label>
        <input class="form-control" type="text" name="country" id="country" wire:model.defer="airport.country">
        <div class="validation-message">
            {{ $errors->first('airport.country') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.airport.fields.country_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('airport.state') ? 'invalid' : '' }}">
        <label class="form-label" for="state">{{ trans('cruds.airport.fields.state') }}</label>
        <input class="form-control" type="text" name="state" id="state" wire:model.defer="airport.state">
        <div class="validation-message">
            {{ $errors->first('airport.state') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.airport.fields.state_helper') }}
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.airports.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>