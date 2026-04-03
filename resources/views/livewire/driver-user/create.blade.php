<form wire:submit.prevent="submit" class="pt-3">

    <div class="form-group {{ $errors->has('driverUser.country_code') ? 'invalid' : '' }}">
        <label class="form-label" for="country_code">{{ trans('cruds.driverUser.fields.country_code') }}</label>
        <input class="form-control" type="text" name="country_code" id="country_code" wire:model.defer="driverUser.country_code">
        <div class="validation-message">
            {{ $errors->first('driverUser.country_code') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.driverUser.fields.country_code_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('driverUser.document_verification') ? 'invalid' : '' }}">
        <input class="form-control" type="checkbox" name="document_verification" id="document_verification" wire:model.defer="driverUser.document_verification">
        <label class="form-label inline ml-1" for="document_verification">{{ trans('cruds.driverUser.fields.document_verification') }}</label>
        <div class="validation-message">
            {{ $errors->first('driverUser.document_verification') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.driverUser.fields.document_verification_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('driverUser.email') ? 'invalid' : '' }}">
        <label class="form-label" for="email">{{ trans('cruds.driverUser.fields.email') }}</label>
        <input class="form-control" type="email" name="email" id="email" wire:model.defer="driverUser.email">
        <div class="validation-message">
            {{ $errors->first('driverUser.email') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.driverUser.fields.email_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('driverUser.fcm_token') ? 'invalid' : '' }}">
        <label class="form-label" for="fcm_token">{{ trans('cruds.driverUser.fields.fcm_token') }}</label>
        <textarea class="form-control" name="fcm_token" id="fcm_token" wire:model.defer="driverUser.fcm_token" rows="4"></textarea>
        <div class="validation-message">
            {{ $errors->first('driverUser.fcm_token') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.driverUser.fields.fcm_token_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('driverUser.full_name') ? 'invalid' : '' }}">
        <label class="form-label" for="full_name">{{ trans('cruds.driverUser.fields.full_name') }}</label>
        <input class="form-control" type="text" name="full_name" id="full_name" wire:model.defer="driverUser.full_name">
        <div class="validation-message">
            {{ $errors->first('driverUser.full_name') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.driverUser.fields.full_name_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('driverUser.is_online') ? 'invalid' : '' }}">
        <input class="form-control" type="checkbox" name="is_online" id="is_online" wire:model.defer="driverUser.is_online">
        <label class="form-label inline ml-1" for="is_online">{{ trans('cruds.driverUser.fields.is_online') }}</label>
        <div class="validation-message">
            {{ $errors->first('driverUser.is_online') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.driverUser.fields.is_online_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('driverUser.login_type') ? 'invalid' : '' }}">
        <label class="form-label" for="login_type">{{ trans('cruds.driverUser.fields.login_type') }}</label>
        <input class="form-control" type="text" name="login_type" id="login_type" wire:model.defer="driverUser.login_type">
        <div class="validation-message">
            {{ $errors->first('driverUser.login_type') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.driverUser.fields.login_type_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('driverUser.phone_number') ? 'invalid' : '' }}">
        <label class="form-label" for="phone_number">{{ trans('cruds.driverUser.fields.phone_number') }}</label>
        <input class="form-control" type="number" name="phone_number" id="phone_number" wire:model.defer="driverUser.phone_number" step="1">
        <div class="validation-message">
            {{ $errors->first('driverUser.phone_number') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.driverUser.fields.phone_number_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('driverUser.profile_pic') ? 'invalid' : '' }}">
        <label class="form-label" for="profile_pic">{{ trans('cruds.driverUser.fields.profile_pic') }}</label>
        <input class="form-control" type="text" name="profile_pic" id="profile_pic" wire:model.defer="driverUser.profile_pic">
        <div class="validation-message">
            {{ $errors->first('driverUser.profile_pic') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.driverUser.fields.profile_pic_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('driverUser.reviews_count') ? 'invalid' : '' }}">
        <label class="form-label" for="reviews_count">{{ trans('cruds.driverUser.fields.reviews_count') }}</label>
        <input class="form-control" type="text" name="reviews_count" id="reviews_count" wire:model.defer="driverUser.reviews_count">
        <div class="validation-message">
            {{ $errors->first('driverUser.reviews_count') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.driverUser.fields.reviews_count_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('driverUser.reviews_sum') ? 'invalid' : '' }}">
        <label class="form-label" for="reviews_sum">{{ trans('cruds.driverUser.fields.reviews_sum') }}</label>
        <input class="form-control" type="text" name="reviews_sum" id="reviews_sum" wire:model.defer="driverUser.reviews_sum">
        <div class="validation-message">
            {{ $errors->first('driverUser.reviews_sum') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.driverUser.fields.reviews_sum_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('driverUser.rotation') ? 'invalid' : '' }}">
        <label class="form-label" for="rotation">{{ trans('cruds.driverUser.fields.rotation') }}</label>
        <input class="form-control" type="text" name="rotation" id="rotation" wire:model.defer="driverUser.rotation">
        <div class="validation-message">
            {{ $errors->first('driverUser.rotation') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.driverUser.fields.rotation_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('driverUser.service') ? 'invalid' : '' }}">
        <label class="form-label" for="service">{{ trans('cruds.driverUser.fields.service') }}</label>
        <input class="form-control" type="text" name="service" id="service" wire:model.defer="driverUser.service">
        <div class="validation-message">
            {{ $errors->first('driverUser.service') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.driverUser.fields.service_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('driverUser.wallet_amount') ? 'invalid' : '' }}">
        <label class="form-label" for="wallet_amount">{{ trans('cruds.driverUser.fields.wallet_amount') }}</label>
        <input class="form-control" type="text" name="wallet_amount" id="wallet_amount" wire:model.defer="driverUser.wallet_amount">
        <div class="validation-message">
            {{ $errors->first('driverUser.wallet_amount') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.driverUser.fields.wallet_amount_helper') }}
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.driver-users.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>