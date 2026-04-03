<form wire:submit.prevent="submit" class="pt-3">

    <div class="form-group {{ $errors->has('driverDocument.documents') ? 'invalid' : '' }}">
        <label class="form-label" for="documents">{{ trans('cruds.driverDocument.fields.documents') }}</label>
        <textarea class="form-control" name="documents" id="documents" wire:model.defer="driverDocument.documents" rows="4"></textarea>
        <div class="validation-message">
            {{ $errors->first('driverDocument.documents') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.driverDocument.fields.documents_helper') }}
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.driver-documents.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>