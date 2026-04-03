<form wire:submit.prevent="submit" class="pt-3">

    <div class="form-group {{ $errors->has('reviewDriver.comment') ? 'invalid' : '' }}">
        <label class="form-label" for="comment">{{ trans('cruds.reviewDriver.fields.comment') }}</label>
        <input class="form-control" type="text" name="comment" id="comment" wire:model.defer="reviewDriver.comment">
        <div class="validation-message">
            {{ $errors->first('reviewDriver.comment') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.reviewDriver.fields.comment_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('reviewDriver.customer_id') ? 'invalid' : '' }}">
        <label class="form-label" for="customer">{{ trans('cruds.reviewDriver.fields.customer') }}</label>
        <x-select-list class="form-control" id="customer" name="customer" :options="$this->listsForFields['customer']" wire:model="reviewDriver.customer_id" />
        <div class="validation-message">
            {{ $errors->first('reviewDriver.customer_id') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.reviewDriver.fields.customer_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('reviewDriver.driver_id') ? 'invalid' : '' }}">
        <label class="form-label" for="driver">{{ trans('cruds.reviewDriver.fields.driver') }}</label>
        <x-select-list class="form-control" id="driver" name="driver" :options="$this->listsForFields['driver']" wire:model="reviewDriver.driver_id" />
        <div class="validation-message">
            {{ $errors->first('reviewDriver.driver_id') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.reviewDriver.fields.driver_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('reviewDriver.rating') ? 'invalid' : '' }}">
        <label class="form-label" for="rating">{{ trans('cruds.reviewDriver.fields.rating') }}</label>
        <input class="form-control" type="text" name="rating" id="rating" wire:model.defer="reviewDriver.rating">
        <div class="validation-message">
            {{ $errors->first('reviewDriver.rating') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.reviewDriver.fields.rating_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('reviewDriver.type') ? 'invalid' : '' }}">
        <label class="form-label" for="type">{{ trans('cruds.reviewDriver.fields.type') }}</label>
        <input class="form-control" type="text" name="type" id="type" wire:model.defer="reviewDriver.type">
        <div class="validation-message">
            {{ $errors->first('reviewDriver.type') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.reviewDriver.fields.type_helper') }}
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.review-drivers.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>