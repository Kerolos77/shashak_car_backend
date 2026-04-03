<form wire:submit.prevent="submit" class="pt-3">

    <div class="form-group {{ $errors->has('cmsPage.name') ? 'invalid' : '' }}">
        <label class="form-label" for="name">{{ trans('cruds.cmsPage.fields.name') }}</label>
        <input class="form-control" type="text" name="name" id="name" wire:model.defer="cmsPage.name">
        <div class="validation-message">
            {{ $errors->first('cmsPage.name') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.cmsPage.fields.name_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('cmsPage.publish') ? 'invalid' : '' }}">
        <input class="form-control" type="checkbox" name="publish" id="publish" wire:model.defer="cmsPage.publish">
        <label class="form-label inline ml-1" for="publish">{{ trans('cruds.cmsPage.fields.publish') }}</label>
        <div class="validation-message">
            {{ $errors->first('cmsPage.publish') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.cmsPage.fields.publish_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('cmsPage.slug') ? 'invalid' : '' }}">
        <label class="form-label" for="slug">{{ trans('cruds.cmsPage.fields.slug') }}</label>
        <input class="form-control" type="text" name="slug" id="slug" wire:model.defer="cmsPage.slug">
        <div class="validation-message">
            {{ $errors->first('cmsPage.slug') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.cmsPage.fields.slug_helper') }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('cmsPage.description') ? 'invalid' : '' }}">
        <label class="form-label" for="description">{{ trans('cruds.cmsPage.fields.description') }}</label>
        <textarea class="form-control" name="description" id="description" wire:model.defer="cmsPage.description" rows="4"></textarea>
        <div class="validation-message">
            {{ $errors->first('cmsPage.description') }}
        </div>
        <div class="help-block">
            {{ trans('cruds.cmsPage.fields.description_helper') }}
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-indigo mr-2" type="submit">
            {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.cms-pages.index') }}" class="btn btn-secondary">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>