<form wire:submit.prevent="submit" class="pt-3">

    <div class="form-group {{ $errors->has('order.accepted_driver') ? 'invalid' : '' }}">
        <label class="form-label" for="accepted_driver">{{ trans('cruds.order.fields.accepted_driver') }}</label>
        <input class="form-control" type="text" name="accepted_driver" id="accepted_driver" wire:model.defer="order.accepted_driver">
        <div class="validation-message">
            {{ $errors->first('order.accepted_driver') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.order.fields.accepted_driver_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('order.admin_commission') ? 'invalid' : '' }}">
        <label class="form-label" for="admin_commission">{{ trans('cruds.order.fields.admin_commission') }}</label>
        <textarea class="form-control" name="admin_commission" id="admin_commission" wire:model.defer="order.admin_commission" rows="4"></textarea>
        <div class="validation-message">
            {{ $errors->first('order.admin_commission') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.order.fields.admin_commission_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('order.destination_location_name') ? 'invalid' : '' }}">
        <label class="form-label" for="destination_location_name">{{ trans('cruds.order.fields.destination_location_name') }}</label>
        <input class="form-control" type="text" name="destination_location_name" id="destination_location_name" wire:model.defer="order.destination_location_name">
        <div class="validation-message">
            {{ $errors->first('order.destination_location_name') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.order.fields.destination_location_name_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('order.destination_location_l_at_lng') ? 'invalid' : '' }}">
        <label class="form-label" for="destination_location_l_at_lng">{{ trans('cruds.order.fields.destination_location_l_at_lng') }}</label>
        <textarea class="form-control" name="destination_location_l_at_lng" id="destination_location_l_at_lng" wire:model.defer="order.destination_location_l_at_lng" rows="4"></textarea>
        <div class="validation-message">
            {{ $errors->first('order.destination_location_l_at_lng') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.order.fields.destination_location_l_at_lng_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('order.distance') ? 'invalid' : '' }}">
        <label class="form-label" for="distance">{{ trans('cruds.order.fields.distance') }}</label>
        <input class="form-control" type="text" name="distance" id="distance" wire:model.defer="order.distance">
        <div class="validation-message">
            {{ $errors->first('order.distance') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.order.fields.distance_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('order.distance_type') ? 'invalid' : '' }}">
        <label class="form-label" for="distance_type">{{ trans('cruds.order.fields.distance_type') }}</label>
        <input class="form-control" type="text" name="distance_type" id="distance_type" wire:model.defer="order.distance_type">
        <div class="validation-message">
            {{ $errors->first('order.distance_type') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.order.fields.distance_type_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('order.driver') ? 'invalid' : '' }}">
        <label class="form-label" for="driver">{{ trans('cruds.order.fields.driver') }}</label>
        <input class="form-control" type="text" name="driver" id="driver" wire:model.defer="order.driver">
        <div class="validation-message">
            {{ $errors->first('order.driver') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.order.fields.driver_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('order.final_rate') ? 'invalid' : '' }}">
        <label class="form-label" for="final_rate">{{ trans('cruds.order.fields.final_rate') }}</label>
        <input class="form-control" type="text" name="final_rate" id="final_rate" wire:model.defer="order.final_rate">
        <div class="validation-message">
            {{ $errors->first('order.final_rate') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.order.fields.final_rate_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('order.offer_rate') ? 'invalid' : '' }}">
        <label class="form-label" for="offer_rate">{{ trans('cruds.order.fields.offer_rate') }}</label>
        <input class="form-control" type="text" name="offer_rate" id="offer_rate" wire:model.defer="order.offer_rate">
        <div class="validation-message">
            {{ $errors->first('order.offer_rate') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.order.fields.offer_rate_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('order.otp') ? 'invalid' : '' }}">
        <label class="form-label" for="otp">{{ trans('cruds.order.fields.otp') }}</label>
        <input class="form-control" type="text" name="otp" id="otp" wire:model.defer="order.otp">
        <div class="validation-message">
            {{ $errors->first('order.otp') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.order.fields.otp_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('order.payment_status') ? 'invalid' : '' }}">
        <input class="form-control" type="checkbox" name="payment_status" id="payment_status" wire:model.defer="order.payment_status">
        <label class="form-label inline ml-1" for="payment_status">{{ trans('cruds.order.fields.payment_status') }}</label>
        <div class="validation-message">
            {{ $errors->first('order.payment_status') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.order.fields.payment_status_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('order.payment_type') ? 'invalid' : '' }}">
        <label class="form-label" for="payment_type">{{ trans('cruds.order.fields.payment_type') }}</label>
        <input class="form-control" type="text" name="payment_type" id="payment_type" wire:model.defer="order.payment_type">
        <div class="validation-message">
            {{ $errors->first('order.payment_type') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.order.fields.payment_type_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('order.position') ? 'invalid' : '' }}">
        <label class="form-label" for="position">{{ trans('cruds.order.fields.position') }}</label>
        <textarea class="form-control" name="position" id="position" wire:model.defer="order.position" rows="4"></textarea>
        <div class="validation-message">
            {{ $errors->first('order.position') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.order.fields.position_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('order.rejected_driver') ? 'invalid' : '' }}">
        <label class="form-label" for="rejected_driver">{{ trans('cruds.order.fields.rejected_driver') }}</label>
        <input class="form-control" type="text" name="rejected_driver" id="rejected_driver" wire:model.defer="order.rejected_driver">
        <div class="validation-message">
            {{ $errors->first('order.rejected_driver') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.order.fields.rejected_driver_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('order.service') ? 'invalid' : '' }}">
        <label class="form-label" for="service">{{ trans('cruds.order.fields.service') }}</label>
        <input class="form-control" type="text" name="service" id="service" wire:model.defer="order.service">
        <div class="validation-message">
            {{ $errors->first('order.service') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.order.fields.service_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('order.source_location_l_at_lng') ? 'invalid' : '' }}">
        <label class="form-label" for="source_location_l_at_lng">{{ trans('cruds.order.fields.source_location_l_at_lng') }}</label>
        <input class="form-control" type="text" name="source_location_l_at_lng" id="source_location_l_at_lng" wire:model.defer="order.source_location_l_at_lng">
        <div class="validation-message">
            {{ $errors->first('order.source_location_l_at_lng') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.order.fields.source_location_l_at_lng_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('order.source_location_name') ? 'invalid' : '' }}">
        <label class="form-label" for="source_location_name">{{ trans('cruds.order.fields.source_location_name') }}</label>
        <input class="form-control" type="text" name="source_location_name" id="source_location_name" wire:model.defer="order.source_location_name">
        <div class="validation-message">
            {{ $errors->first('order.source_location_name') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.order.fields.source_location_name_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('order.status') ? 'invalid' : '' }}">
        <label class="form-label" for="status">{{ trans('cruds.order.fields.status') }}</label>
        <input class="form-control" type="text" name="status" id="status" wire:model.defer="order.status">
        <div class="validation-message">
            {{ $errors->first('order.status') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.order.fields.status_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('order.tax_list') ? 'invalid' : '' }}">
        <label class="form-label" for="tax_list">{{ trans('cruds.order.fields.tax_list') }}</label>
        <input class="form-control" type="text" name="tax_list" id="tax_list" wire:model.defer="order.tax_list">
        <div class="validation-message">
            {{ $errors->first('order.tax_list') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.order.fields.tax_list_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('order.update_date') ? 'invalid' : '' }}">
        <label class="form-label" for="update_date">{{ trans('cruds.order.fields.update_date') }}</label>
        <input class="form-control" type="text" name="update_date" id="update_date" wire:model.defer="order.update_date">
        <div class="validation-message">
            {{ $errors->first('order.update_date') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.order.fields.update_date_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('order.user_id') ? 'invalid' : '' }}">
        <label class="form-label" for="user">{{ trans('cruds.order.fields.user') }}</label>
        <x-select-list class="form-control" id="user" name="user" :options="$this->listsForFields['user']" wire:model="order.user_id" />
        <div class="validation-message">
            {{ $errors->first('order.user_id') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.order.fields.user_helper') }}
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>