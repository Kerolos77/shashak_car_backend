<form wire:submit.prevent="submit" class="pt-3">

    <div class="form-group {{ $errors->has('chat.customer_id') ? 'invalid' : '' }}">
        <label class="form-label" for="customer">{{ trans('cruds.chat.fields.customer') }}</label>
        <x-select-list class="form-control" id="customer" name="customer" :options="$this->listsForFields['customer']" wire:model="chat.customer_id" />
        <div class="validation-message">
            {{ $errors->first('chat.customer_id') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.chat.fields.customer_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('chat.customer_name') ? 'invalid' : '' }}">
        <label class="form-label" for="customer_name">{{ trans('cruds.chat.fields.customer_name') }}</label>
        <input class="form-control" type="text" name="customer_name" id="customer_name" wire:model.defer="chat.customer_name">
        <div class="validation-message">
            {{ $errors->first('chat.customer_name') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.chat.fields.customer_name_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('chat.customer_profile_image') ? 'invalid' : '' }}">
        <label class="form-label" for="customer_profile_image">{{ trans('cruds.chat.fields.customer_profile_image') }}</label>
        <input class="form-control" type="text" name="customer_profile_image" id="customer_profile_image" wire:model.defer="chat.customer_profile_image">
        <div class="validation-message">
            {{ $errors->first('chat.customer_profile_image') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.chat.fields.customer_profile_image_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('chat.driver_id') ? 'invalid' : '' }}">
        <label class="form-label" for="driver">{{ trans('cruds.chat.fields.driver') }}</label>
        <x-select-list class="form-control" id="driver" name="driver" :options="$this->listsForFields['driver']" wire:model="chat.driver_id" />
        <div class="validation-message">
            {{ $errors->first('chat.driver_id') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.chat.fields.driver_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('chat.driver_name') ? 'invalid' : '' }}">
        <label class="form-label" for="driver_name">{{ trans('cruds.chat.fields.driver_name') }}</label>
        <input class="form-control" type="text" name="driver_name" id="driver_name" wire:model.defer="chat.driver_name">
        <div class="validation-message">
            {{ $errors->first('chat.driver_name') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.chat.fields.driver_name_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('chat.driver_profile_image') ? 'invalid' : '' }}">
        <label class="form-label" for="driver_profile_image">{{ trans('cruds.chat.fields.driver_profile_image') }}</label>
        <input class="form-control" type="text" name="driver_profile_image" id="driver_profile_image" wire:model.defer="chat.driver_profile_image">
        <div class="validation-message">
            {{ $errors->first('chat.driver_profile_image') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.chat.fields.driver_profile_image_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('chat.last_message') ? 'invalid' : '' }}">
        <label class="form-label" for="last_message">{{ trans('cruds.chat.fields.last_message') }}</label>
        <input class="form-control" type="text" name="last_message" id="last_message" wire:model.defer="chat.last_message">
        <div class="validation-message">
            {{ $errors->first('chat.last_message') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.chat.fields.last_message_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('chat.last_sender') ? 'invalid' : '' }}">
        <label class="form-label" for="last_sender">{{ trans('cruds.chat.fields.last_sender') }}</label>
        <input class="form-control" type="text" name="last_sender" id="last_sender" wire:model.defer="chat.last_sender">
        <div class="validation-message">
            {{ $errors->first('chat.last_sender') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.chat.fields.last_sender_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('chat.order_id') ? 'invalid' : '' }}">
        <label class="form-label" for="order">{{ trans('cruds.chat.fields.order') }}</label>
        <x-select-list class="form-control" id="order" name="order" :options="$this->listsForFields['order']" wire:model="chat.order_id" />
        <div class="validation-message">
            {{ $errors->first('chat.order_id') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.chat.fields.order_helper') }}
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.chats.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>