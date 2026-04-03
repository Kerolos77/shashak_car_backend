<form wire:submit.prevent="submit" class="pt-5">
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa fa-check-circle me-2"></i>
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa fa-exclamation-triangle me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="mb-5">
        <label class="form-label required" for="title">{{ trans('cruds.role.fields.title') }}</label>
        <input type="text" id="title" class="form-control @error('role.title') is-invalid @enderror" wire:model.defer="role.title" required>
        @error('role.title')
            <div class="invalid-feedback d-block">
                <i class="fa fa-exclamation-circle me-1"></i>
                {{ $message }}
            </div>
        @enderror
        <div class="form-text">{{ trans('cruds.role.fields.title_helper') }}</div>
    </div>

    <div class="mb-5">
        <label class="form-label required" for="permissions">{{ trans('cruds.role.fields.permissions') }}</label>
        <select 
            id="permissions" 
            class="form-control @error('permissions') is-invalid @enderror" 
            wire:model="permissions" 
            multiple 
            required
            style="height: 200px;"
        >
            @foreach($this->listsForFields['permissions'] as $id => $title)
                <option value="{{ $id }}" @if(in_array($id, $permissions)) selected @endif>{{ $title }}</option>
            @endforeach
        </select>
        @error('permissions')
            <div class="invalid-feedback d-block">
                <i class="fa fa-exclamation-circle me-1"></i>
                {{ $message }}
            </div>
        @enderror
        @error('permissions.*')
            <div class="invalid-feedback d-block">
                <i class="fa fa-exclamation-circle me-1"></i>
                {{ $message }}
            </div>
        @enderror
        <div class="form-text">{{ trans('cruds.role.fields.permissions_helper') }}</div>
    </div>

    <div class="d-flex justify-content-start gap-3">
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-save me-1"></i> {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-light">
            {{ trans('global.cancel') }}
        </a>
    </div>
</form>
