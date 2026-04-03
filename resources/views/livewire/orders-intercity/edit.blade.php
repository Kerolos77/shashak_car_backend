<form wire:submit.prevent="submit" class="pt-3">

    <div class="form-group {{ $errors->has('ordersIntercity.accepted_driver') ? 'invalid' : '' }}">
        <label class="form-label" for="accepted_driver">{{ trans('cruds.ordersIntercity.fields.accepted_driver') }}</label>
        <input class="form-control" type="text" name="accepted_driver" id="accepted_driver" wire:model.defer="ordersIntercity.accepted_driver">
        <div class="validation-message">
            {{ $errors->first('ordersIntercity.accepted_driver') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.ordersIntercity.fields.accepted_driver_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('ordersIntercity.admin_commission') ? 'invalid' : '' }}">
        <label class="form-label" for="admin_commission">{{ trans('cruds.ordersIntercity.fields.admin_commission') }}</label>
        <input class="form-control" type="text" name="admin_commission" id="admin_commission" wire:model.defer="ordersIntercity.admin_commission">
        <div class="validation-message">
            {{ $errors->first('ordersIntercity.admin_commission') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.ordersIntercity.fields.admin_commission_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('ordersIntercity.comments') ? 'invalid' : '' }}">
        <label class="form-label" for="comments">{{ trans('cruds.ordersIntercity.fields.comments') }}</label>
        <input class="form-control" type="text" name="comments" id="comments" wire:model.defer="ordersIntercity.comments">
        <div class="validation-message">
            {{ $errors->first('ordersIntercity.comments') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.ordersIntercity.fields.comments_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('ordersIntercity.destination_city') ? 'invalid' : '' }}">
        <label class="form-label" for="destination_city">{{ trans('cruds.ordersIntercity.fields.destination_city') }}</label>
        <input class="form-control" type="text" name="destination_city" id="destination_city" wire:model.defer="ordersIntercity.destination_city">
        <div class="validation-message">
            {{ $errors->first('ordersIntercity.destination_city') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.ordersIntercity.fields.destination_city_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('ordersIntercity.destination_location_lat_lng') ? 'invalid' : '' }}">
        <label class="form-label" for="destination_location_lat_lng">{{ trans('cruds.ordersIntercity.fields.destination_location_lat_lng') }}</label>
        <input class="form-control" type="text" name="destination_location_lat_lng" id="destination_location_lat_lng" wire:model.defer="ordersIntercity.destination_location_lat_lng">
        <div class="validation-message">
            {{ $errors->first('ordersIntercity.destination_location_lat_lng') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.ordersIntercity.fields.destination_location_lat_lng_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('ordersIntercity.destination_location_name') ? 'invalid' : '' }}">
        <label class="form-label" for="destination_location_name">{{ trans('cruds.ordersIntercity.fields.destination_location_name') }}</label>
        <input class="form-control" type="text" name="destination_location_name" id="destination_location_name" wire:model.defer="ordersIntercity.destination_location_name">
        <div class="validation-message">
            {{ $errors->first('ordersIntercity.destination_location_name') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.ordersIntercity.fields.destination_location_name_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('ordersIntercity.distance') ? 'invalid' : '' }}">
        <label class="form-label" for="distance">{{ trans('cruds.ordersIntercity.fields.distance') }}</label>
        <input class="form-control" type="text" name="distance" id="distance" wire:model.defer="ordersIntercity.distance">
        <div class="validation-message">
            {{ $errors->first('ordersIntercity.distance') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.ordersIntercity.fields.distance_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('ordersIntercity.distance_type') ? 'invalid' : '' }}">
        <label class="form-label" for="distance_type">{{ trans('cruds.ordersIntercity.fields.distance_type') }}</label>
        <input class="form-control" type="text" name="distance_type" id="distance_type" wire:model.defer="ordersIntercity.distance_type">
        <div class="validation-message">
            {{ $errors->first('ordersIntercity.distance_type') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.ordersIntercity.fields.distance_type_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('ordersIntercity.driver_id') ? 'invalid' : '' }}">
        <label class="form-label" for="driver">{{ trans('cruds.ordersIntercity.fields.driver') }}</label>
        <x-select-list class="form-control" id="driver" name="driver" :options="$this->listsForFields['driver']" wire:model="ordersIntercity.driver_id" />
        <div class="validation-message">
            {{ $errors->first('ordersIntercity.driver_id') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.ordersIntercity.fields.driver_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('ordersIntercity.final_rate') ? 'invalid' : '' }}">
        <label class="form-label" for="final_rate">{{ trans('cruds.ordersIntercity.fields.final_rate') }}</label>
        <input class="form-control" type="text" name="final_rate" id="final_rate" wire:model.defer="ordersIntercity.final_rate">
        <div class="validation-message">
            {{ $errors->first('ordersIntercity.final_rate') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.ordersIntercity.fields.final_rate_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('ordersIntercity.intercity_service') ? 'invalid' : '' }}">
        <label class="form-label" for="intercity_service">{{ trans('cruds.ordersIntercity.fields.intercity_service') }}</label>
        <textarea class="form-control" name="intercity_service" id="intercity_service" wire:model.defer="ordersIntercity.intercity_service" rows="4"></textarea>
        <div class="validation-message">
            {{ $errors->first('ordersIntercity.intercity_service') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.ordersIntercity.fields.intercity_service_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('ordersIntercity.intercityservice_id') ? 'invalid' : '' }}">
        <label class="form-label" for="intercityservice">{{ trans('cruds.ordersIntercity.fields.intercityservice') }}</label>
        <x-select-list class="form-control" id="intercityservice" name="intercityservice" :options="$this->listsForFields['intercityservice']" wire:model="ordersIntercity.intercityservice_id" />
        <div class="validation-message">
            {{ $errors->first('ordersIntercity.intercityservice_id') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.ordersIntercity.fields.intercityservice_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('ordersIntercity.number_of_passenger') ? 'invalid' : '' }}">
        <label class="form-label" for="number_of_passenger">{{ trans('cruds.ordersIntercity.fields.number_of_passenger') }}</label>
        <input class="form-control" type="text" name="number_of_passenger" id="number_of_passenger" wire:model.defer="ordersIntercity.number_of_passenger">
        <div class="validation-message">
            {{ $errors->first('ordersIntercity.number_of_passenger') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.ordersIntercity.fields.number_of_passenger_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('ordersIntercity.offer_rate') ? 'invalid' : '' }}">
        <label class="form-label" for="offer_rate">{{ trans('cruds.ordersIntercity.fields.offer_rate') }}</label>
        <input class="form-control" type="text" name="offer_rate" id="offer_rate" wire:model.defer="ordersIntercity.offer_rate">
        <div class="validation-message">
            {{ $errors->first('ordersIntercity.offer_rate') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.ordersIntercity.fields.offer_rate_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('ordersIntercity.otp') ? 'invalid' : '' }}">
        <label class="form-label" for="otp">{{ trans('cruds.ordersIntercity.fields.otp') }}</label>
        <input class="form-control" type="text" name="otp" id="otp" wire:model.defer="ordersIntercity.otp">
        <div class="validation-message">
            {{ $errors->first('ordersIntercity.otp') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.ordersIntercity.fields.otp_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('ordersIntercity.parcel_dimension') ? 'invalid' : '' }}">
        <label class="form-label" for="parcel_dimension">{{ trans('cruds.ordersIntercity.fields.parcel_dimension') }}</label>
        <input class="form-control" type="text" name="parcel_dimension" id="parcel_dimension" wire:model.defer="ordersIntercity.parcel_dimension">
        <div class="validation-message">
            {{ $errors->first('ordersIntercity.parcel_dimension') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.ordersIntercity.fields.parcel_dimension_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('ordersIntercity.parcel_image') ? 'invalid' : '' }}">
        <label class="form-label" for="parcel_image">{{ trans('cruds.ordersIntercity.fields.parcel_image') }}</label>
        <input class="form-control" type="text" name="parcel_image" id="parcel_image" wire:model.defer="ordersIntercity.parcel_image">
        <div class="validation-message">
            {{ $errors->first('ordersIntercity.parcel_image') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.ordersIntercity.fields.parcel_image_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('ordersIntercity.parcel_weight') ? 'invalid' : '' }}">
        <label class="form-label" for="parcel_weight">{{ trans('cruds.ordersIntercity.fields.parcel_weight') }}</label>
        <input class="form-control" type="text" name="parcel_weight" id="parcel_weight" wire:model.defer="ordersIntercity.parcel_weight">
        <div class="validation-message">
            {{ $errors->first('ordersIntercity.parcel_weight') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.ordersIntercity.fields.parcel_weight_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('ordersIntercity.payment_status') ? 'invalid' : '' }}">
        <label class="form-label" for="payment_status">{{ trans('cruds.ordersIntercity.fields.payment_status') }}</label>
        <input class="form-control" type="text" name="payment_status" id="payment_status" wire:model.defer="ordersIntercity.payment_status">
        <div class="validation-message">
            {{ $errors->first('ordersIntercity.payment_status') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.ordersIntercity.fields.payment_status_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('ordersIntercity.payment_type') ? 'invalid' : '' }}">
        <label class="form-label" for="payment_type">{{ trans('cruds.ordersIntercity.fields.payment_type') }}</label>
        <input class="form-control" type="text" name="payment_type" id="payment_type" wire:model.defer="ordersIntercity.payment_type">
        <div class="validation-message">
            {{ $errors->first('ordersIntercity.payment_type') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.ordersIntercity.fields.payment_type_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('ordersIntercity.position') ? 'invalid' : '' }}">
        <label class="form-label" for="position">{{ trans('cruds.ordersIntercity.fields.position') }}</label>
        <input class="form-control" type="text" name="position" id="position" wire:model.defer="ordersIntercity.position">
        <div class="validation-message">
            {{ $errors->first('ordersIntercity.position') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.ordersIntercity.fields.position_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('ordersIntercity.rejected_driver') ? 'invalid' : '' }}">
        <label class="form-label" for="rejected_driver">{{ trans('cruds.ordersIntercity.fields.rejected_driver') }}</label>
        <input class="form-control" type="text" name="rejected_driver" id="rejected_driver" wire:model.defer="ordersIntercity.rejected_driver">
        <div class="validation-message">
            {{ $errors->first('ordersIntercity.rejected_driver') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.ordersIntercity.fields.rejected_driver_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('ordersIntercity.source_city') ? 'invalid' : '' }}">
        <label class="form-label" for="source_city">{{ trans('cruds.ordersIntercity.fields.source_city') }}</label>
        <input class="form-control" type="text" name="source_city" id="source_city" wire:model.defer="ordersIntercity.source_city">
        <div class="validation-message">
            {{ $errors->first('ordersIntercity.source_city') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.ordersIntercity.fields.source_city_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('ordersIntercity.source_location_lat_lng') ? 'invalid' : '' }}">
        <label class="form-label" for="source_location_lat_lng">{{ trans('cruds.ordersIntercity.fields.source_location_lat_lng') }}</label>
        <input class="form-control" type="text" name="source_location_lat_lng" id="source_location_lat_lng" wire:model.defer="ordersIntercity.source_location_lat_lng">
        <div class="validation-message">
            {{ $errors->first('ordersIntercity.source_location_lat_lng') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.ordersIntercity.fields.source_location_lat_lng_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('ordersIntercity.source_location_name') ? 'invalid' : '' }}">
        <label class="form-label" for="source_location_name">{{ trans('cruds.ordersIntercity.fields.source_location_name') }}</label>
        <input class="form-control" type="text" name="source_location_name" id="source_location_name" wire:model.defer="ordersIntercity.source_location_name">
        <div class="validation-message">
            {{ $errors->first('ordersIntercity.source_location_name') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.ordersIntercity.fields.source_location_name_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('ordersIntercity.status') ? 'invalid' : '' }}">
        <label class="form-label" for="status">{{ trans('cruds.ordersIntercity.fields.status') }}</label>
        <input class="form-control" type="text" name="status" id="status" wire:model.defer="ordersIntercity.status">
        <div class="validation-message">
            {{ $errors->first('ordersIntercity.status') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.ordersIntercity.fields.status_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('ordersIntercity.tax_list') ? 'invalid' : '' }}">
        <label class="form-label" for="tax_list">{{ trans('cruds.ordersIntercity.fields.tax_list') }}</label>
        <input class="form-control" type="text" name="tax_list" id="tax_list" wire:model.defer="ordersIntercity.tax_list">
        <div class="validation-message">
            {{ $errors->first('ordersIntercity.tax_list') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.ordersIntercity.fields.tax_list_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('ordersIntercity.user_id') ? 'invalid' : '' }}">
        <label class="form-label" for="user">{{ trans('cruds.ordersIntercity.fields.user') }}</label>
        <x-select-list class="form-control" id="user" name="user" :options="$this->listsForFields['user']" wire:model="ordersIntercity.user_id" />
        <div class="validation-message">
            {{ $errors->first('ordersIntercity.user_id') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.ordersIntercity.fields.user_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('ordersIntercity.when_dates') ? 'invalid' : '' }}">
        <label class="form-label" for="when_dates">{{ trans('cruds.ordersIntercity.fields.when_dates') }}</label>
        <input class="form-control" type="text" name="when_dates" id="when_dates" wire:model.defer="ordersIntercity.when_dates">
        <div class="validation-message">
            {{ $errors->first('ordersIntercity.when_dates') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.ordersIntercity.fields.when_dates_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('ordersIntercity.when_time') ? 'invalid' : '' }}">
        <label class="form-label" for="when_time">{{ trans('cruds.ordersIntercity.fields.when_time') }}</label>
        <input class="form-control" type="text" name="when_time" id="when_time" wire:model.defer="ordersIntercity.when_time">
        <div class="validation-message">
            {{ $errors->first('ordersIntercity.when_time') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.ordersIntercity.fields.when_time_helper') }}
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.orders-intercities.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>