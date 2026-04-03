<form wire:submit.prevent="submit" class="pt-3">

    <div class="form-group {{ $errors->has('referral.referral_by') ? 'invalid' : '' }}">
        <label class="form-label" for="referral_by">{{ trans('cruds.referral.fields.referral_by') }}</label>
        <input class="form-control" type="text" name="referral_by" id="referral_by" wire:model.defer="referral.referral_by">
        <div class="validation-message">
            {{ $errors->first('referral.referral_by') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.referral.fields.referral_by_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('referral.referral_code') ? 'invalid' : '' }}">
        <label class="form-label" for="referral_code">{{ trans('cruds.referral.fields.referral_code') }}</label>
        <input class="form-control" type="text" name="referral_code" id="referral_code" wire:model.defer="referral.referral_code">
        <div class="validation-message">
            {{ $errors->first('referral.referral_code') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.referral.fields.referral_code_helper') }}
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.referrals.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>