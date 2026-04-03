<form wire:submit.prevent="submit" class="pt-3">

    <div class="form-group {{ $errors->has('reviewCustomer.comment') ? 'invalid' : '' }}">
        <label class="form-label" for="comment">{{ trans('cruds.reviewCustomer.fields.comment') }}</label>
        <input class="form-control" type="text" name="comment" id="comment" wire:model.defer="reviewCustomer.comment">
        <div class="validation-message">
            {{ $errors->first('reviewCustomer.comment') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.reviewCustomer.fields.comment_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('reviewCustomer.customer_id') ? 'invalid' : '' }}">
        <label class="form-label" for="customer">{{ trans('cruds.reviewCustomer.fields.customer') }}</label>
        <x-select-list class="form-control" id="customer" name="customer" :options="$this->listsForFields['customer']" wire:model="reviewCustomer.customer_id" />
        <div class="validation-message">
            {{ $errors->first('reviewCustomer.customer_id') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.reviewCustomer.fields.customer_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('reviewCustomer.driver_id') ? 'invalid' : '' }}">
        <label class="form-label" for="driver">{{ trans('cruds.reviewCustomer.fields.driver') }}</label>
        <x-select-list class="form-control" id="driver" name="driver" :options="$this->listsForFields['driver']" wire:model="reviewCustomer.driver_id" />
        <div class="validation-message">
            {{ $errors->first('reviewCustomer.driver_id') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.reviewCustomer.fields.driver_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('reviewCustomer.date') ? 'invalid' : '' }}">
        <label class="form-label" for="date">{{ trans('cruds.reviewCustomer.fields.date') }}</label>
        <input class="form-control" type="text" name="date" id="date" wire:model.defer="reviewCustomer.date">
        <div class="validation-message">
            {{ $errors->first('reviewCustomer.date') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.reviewCustomer.fields.date_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('reviewCustomer.rating') ? 'invalid' : '' }}">
        <label class="form-label" for="rating">{{ trans('cruds.reviewCustomer.fields.rating') }}</label>
        <input class="form-control" type="text" name="rating" id="rating" wire:model.defer="reviewCustomer.rating">
        <div class="validation-message">
            {{ $errors->first('reviewCustomer.rating') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.reviewCustomer.fields.rating_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('reviewCustomer.type') ? 'invalid' : '' }}">
        <label class="form-label" for="type">{{ trans('cruds.reviewCustomer.fields.type') }}</label>
        <input class="form-control" type="text" name="type" id="type" wire:model.defer="reviewCustomer.type">
        <div class="validation-message">
            {{ $errors->first('reviewCustomer.type') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.reviewCustomer.fields.type_helper') }}
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.review-customers.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>