<form wire:submit.prevent="submit" class="pt-3">

    <div class="form-group {{ $errors->has('onBoarding.description') ? 'invalid' : '' }}">
        <label class="form-label" for="description">{{ trans('cruds.onBoarding.fields.description') }}</label>
        <textarea class="form-control" name="description" id="description" wire:model.defer="onBoarding.description" rows="4"></textarea>
        <div class="validation-message">
            {{ $errors->first('onBoarding.description') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.onBoarding.fields.description_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('onBoarding.image') ? 'invalid' : '' }}">
        <label class="form-label" for="image">{{ trans('cruds.onBoarding.fields.image') }}</label>
        <input class="form-control" type="text" name="image" id="image" wire:model.defer="onBoarding.image">
        <div class="validation-message">
            {{ $errors->first('onBoarding.image') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.onBoarding.fields.image_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('onBoarding.title') ? 'invalid' : '' }}">
        <label class="form-label" for="title">{{ trans('cruds.onBoarding.fields.title') }}</label>
        <input class="form-control" type="text" name="title" id="title" wire:model.defer="onBoarding.title">
        <div class="validation-message">
            {{ $errors->first('onBoarding.title') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.onBoarding.fields.title_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('onBoarding.type') ? 'invalid' : '' }}">
        <label class="form-label" for="type">{{ trans('cruds.onBoarding.fields.type') }}</label>
        <input class="form-control" type="text" name="type" id="type" wire:model.defer="onBoarding.type">
        <div class="validation-message">
            {{ $errors->first('onBoarding.type') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.onBoarding.fields.type_helper') }}
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.on-boardings.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>