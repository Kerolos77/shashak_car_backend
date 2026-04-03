<form wire:submit.prevent="submit" class="pt-3">

    <div class="form-group {{ $errors->has('so.order_id') ? 'invalid' : '' }}">
        <label class="form-label" for="order">{{ trans('cruds.so.fields.order') }}</label>
        <x-select-list class="form-control" id="order" name="order" :options="$this->listsForFields['order']" wire:model="so.order_id" />
        <div class="validation-message">
            {{ $errors->first('so.order_id') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.so.fields.order_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('so.order_type') ? 'invalid' : '' }}">
        <label class="form-label" for="order_type">{{ trans('cruds.so.fields.order_type') }}</label>
        <input class="form-control" type="text" name="order_type" id="order_type" wire:model.defer="so.order_type">
        <div class="validation-message">
            {{ $errors->first('so.order_type') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.so.fields.order_type_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('so.status') ? 'invalid' : '' }}">
        <label class="form-label" for="status">{{ trans('cruds.so.fields.status') }}</label>
        <input class="form-control" type="text" name="status" id="status" wire:model.defer="so.status">
        <div class="validation-message">
            {{ $errors->first('so.status') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.so.fields.status_helper') }}
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.sos.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>