<form wire:submit.prevent="submit" class="pt-3">

    <div class="form-group {{ $errors->has('intercityService.enable') ? 'invalid' : '' }}">
        <input class="form-control" type="checkbox" name="enable" id="enable" wire:model.defer="intercityService.enable">
        <label class="form-label inline ml-1" for="enable">{{ trans('cruds.intercityService.fields.enable') }}</label>
        <div class="validation-message">
            {{ $errors->first('intercityService.enable') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.intercityService.fields.enable_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('intercityService.image') ? 'invalid' : '' }}">
        <label class="form-label" for="image">{{ trans('cruds.intercityService.fields.image') }}</label>
        <input class="form-control" type="text" name="image" id="image" wire:model.defer="intercityService.image">
        <div class="validation-message">
            {{ $errors->first('intercityService.image') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.intercityService.fields.image_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('intercityService.km_charge') ? 'invalid' : '' }}">
        <label class="form-label" for="km_charge">{{ trans('cruds.intercityService.fields.km_charge') }}</label>
        <input class="form-control" type="text" name="km_charge" id="km_charge" wire:model.defer="intercityService.km_charge">
        <div class="validation-message">
            {{ $errors->first('intercityService.km_charge') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.intercityService.fields.km_charge_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('intercityService.admin_commission') ? 'invalid' : '' }}">
        <label class="form-label" for="admin_commission">{{ trans('cruds.intercityService.fields.admin_commission') }}</label>
        <textarea class="form-control" name="admin_commission" id="admin_commission" wire:model.defer="intercityService.admin_commission" rows="4"></textarea>
        <div class="validation-message">
            {{ $errors->first('intercityService.admin_commission') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.intercityService.fields.admin_commission_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('intercityService.offer_rate') ? 'invalid' : '' }}">
        <input class="form-control" type="checkbox" name="offer_rate" id="offer_rate" wire:model.defer="intercityService.offer_rate">
        <label class="form-label inline ml-1" for="offer_rate">{{ trans('cruds.intercityService.fields.offer_rate') }}</label>
        <div class="validation-message">
            {{ $errors->first('intercityService.offer_rate') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.intercityService.fields.offer_rate_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('intercityService.name') ? 'invalid' : '' }}">
        <label class="form-label" for="name">{{ trans('cruds.intercityService.fields.name') }}</label>
        <input class="form-control" type="text" name="name" id="name" wire:model.defer="intercityService.name">
        <div class="validation-message">
            {{ $errors->first('intercityService.name') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.intercityService.fields.name_helper') }}
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.intercity-services.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>