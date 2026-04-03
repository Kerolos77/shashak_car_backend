<form wire:submit.prevent="submit" class="pt-3">

    <div class="form-group {{ $errors->has('coupon.amount') ? 'invalid' : '' }}">
        <label class="form-label required" for="amount">{{ trans('cruds.coupon.fields.amount') }}</label>
        <input class="form-control" type="text" name="amount" id="amount" required wire:model.defer="coupon.amount">
        <div class="validation-message">
            {{ $errors->first('coupon.amount') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.coupon.fields.amount_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('coupon.code') ? 'invalid' : '' }}">
        <label class="form-label required" for="code">{{ trans('cruds.coupon.fields.code') }}</label>
        <input class="form-control" type="text" name="code" id="code" required wire:model.defer="coupon.code">
        <div class="validation-message">
            {{ $errors->first('coupon.code') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.coupon.fields.code_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('coupon.is_public') ? 'invalid' : '' }}">
        <label class="form-label" for="is_public">{{ trans('cruds.coupon.fields.is_public') }}</label>
        <input class="form-control" type="text" name="is_public" id="is_public" wire:model.defer="coupon.is_public">
        <div class="validation-message">
            {{ $errors->first('coupon.is_public') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.coupon.fields.is_public_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('coupon.title') ? 'invalid' : '' }}">
        <label class="form-label" for="title">{{ trans('cruds.coupon.fields.title') }}</label>
        <input class="form-control" type="text" name="title" id="title" wire:model.defer="coupon.title">
        <div class="validation-message">
            {{ $errors->first('coupon.title') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.coupon.fields.title_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('coupon.type') ? 'invalid' : '' }}">
        <label class="form-label" for="type">{{ trans('cruds.coupon.fields.type') }}</label>
        <input class="form-control" type="text" name="type" id="type" wire:model.defer="coupon.type">
        <div class="validation-message">
            {{ $errors->first('coupon.type') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.coupon.fields.type_helper') }}
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>