<form wire:submit.prevent="submit" class="pt-3">

    <div class="form-group {{ $errors->has('walletTransaction.amount') ? 'invalid' : '' }}">
        <label class="form-label" for="amount">{{ trans('cruds.walletTransaction.fields.amount') }}</label>
        <input class="form-control" type="text" name="amount" id="amount" wire:model.defer="walletTransaction.amount">
        <div class="validation-message">
            {{ $errors->first('walletTransaction.amount') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.walletTransaction.fields.amount_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('walletTransaction.note') ? 'invalid' : '' }}">
        <label class="form-label" for="note">{{ trans('cruds.walletTransaction.fields.note') }}</label>
        <input class="form-control" type="text" name="note" id="note" wire:model.defer="walletTransaction.note">
        <div class="validation-message">
            {{ $errors->first('walletTransaction.note') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.walletTransaction.fields.note_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('walletTransaction.order_type') ? 'invalid' : '' }}">
        <label class="form-label" for="order_type">{{ trans('cruds.walletTransaction.fields.order_type') }}</label>
        <input class="form-control" type="text" name="order_type" id="order_type" wire:model.defer="walletTransaction.order_type">
        <div class="validation-message">
            {{ $errors->first('walletTransaction.order_type') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.walletTransaction.fields.order_type_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('walletTransaction.payment_type') ? 'invalid' : '' }}">
        <label class="form-label" for="payment_type">{{ trans('cruds.walletTransaction.fields.payment_type') }}</label>
        <input class="form-control" type="text" name="payment_type" id="payment_type" wire:model.defer="walletTransaction.payment_type">
        <div class="validation-message">
            {{ $errors->first('walletTransaction.payment_type') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.walletTransaction.fields.payment_type_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('walletTransaction.transaction') ? 'invalid' : '' }}">
        <label class="form-label" for="transaction">{{ trans('cruds.walletTransaction.fields.transaction') }}</label>
        <input class="form-control" type="text" name="transaction" id="transaction" wire:model.defer="walletTransaction.transaction">
        <div class="validation-message">
            {{ $errors->first('walletTransaction.transaction') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.walletTransaction.fields.transaction_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('walletTransaction.user_id') ? 'invalid' : '' }}">
        <label class="form-label" for="user">{{ trans('cruds.walletTransaction.fields.user') }}</label>
        <x-select-list class="form-control" id="user" name="user" :options="$this->listsForFields['user']" wire:model="walletTransaction.user_id" />
        <div class="validation-message">
            {{ $errors->first('walletTransaction.user_id') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.walletTransaction.fields.user_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('walletTransaction.user_type') ? 'invalid' : '' }}">
        <label class="form-label" for="user_type">{{ trans('cruds.walletTransaction.fields.user_type') }}</label>
        <input class="form-control" type="text" name="user_type" id="user_type" wire:model.defer="walletTransaction.user_type">
        <div class="validation-message">
            {{ $errors->first('walletTransaction.user_type') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.walletTransaction.fields.user_type_helper') }}
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.wallet-transactions.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>