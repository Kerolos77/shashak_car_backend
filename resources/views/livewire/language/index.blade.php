<div>
    <div class="card-controls sm:flex">
        <div class="w-full sm:w-1/2">
            Per page:
            <select wire:model="perPage" class="form-select w-full sm:w-1/6">
                @foreach($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>

            @can('language_delete')
                <button class="btn btn-rose ml-3 disabled:opacity-50 disabled:cursor-not-allowed" type="button" wire:click="confirm('deleteSelected')" wire:loading.attr="disabled" {{ $this->selectedCount ? '' : 'disabled' }}>
                    {{ __('Delete Selected') }}
                </button>
            @endcan

            @if(file_exists(app_path('Http/Livewire/ExcelExport.php')))
                <livewire:excel-export model="Language" format="csv" />
                <livewire:excel-export model="Language" format="xlsx" />
                <livewire:excel-export model="Language" format="pdf" />
            @endif




        </div>
        <div class="w-full sm:w-1/2 sm:text-right">
            Search:
            <input type="text" wire:model.debounce.300ms="search" class="w-full sm:w-1/3 inline-block" />
        </div>
    </div>
    <div wire:loading.delay>
        Loading...
    </div>

    <div class="overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table table-index w-full">
                <thead>
                    <tr>
                        <th class="w-9">
                        </th>
                        <th class="w-28">
                            {{ trans('cruds.language.fields.id') }}
                            @include('components.table.sort', ['field' => 'id'])
                        </th>
                        <th>
                            {{ trans('cruds.language.fields.enable') }}
                            @include('components.table.sort', ['field' => 'enable'])
                        </th>
                        <th>
                            {{ trans('cruds.language.fields.code') }}
                            @include('components.table.sort', ['field' => 'code'])
                        </th>
                        <th>
                            {{ trans('cruds.language.fields.is_deleted') }}
                            @include('components.table.sort', ['field' => 'is_deleted'])
                        </th>
                        <th>
                            {{ trans('cruds.language.fields.is_rtl') }}
                            @include('components.table.sort', ['field' => 'is_rtl'])
                        </th>
                        <th>
                            {{ trans('cruds.language.fields.name') }}
                            @include('components.table.sort', ['field' => 'name'])
                        </th>
                        <th>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($languages as $language)
                        <tr>
                            <td>
                                <input type="checkbox" value="{{ $language->id }}" wire:model="selected">
                            </td>
                            <td>
                                {{ $language->id }}
                            </td>
                            <td>
                                <input class="disabled:opacity-50 disabled:cursor-not-allowed" type="checkbox" disabled {{ $language->enable ? 'checked' : '' }}>
                            </td>
                            <td>
                                {{ $language->code }}
                            </td>
                            <td>
                                <input class="disabled:opacity-50 disabled:cursor-not-allowed" type="checkbox" disabled {{ $language->is_deleted ? 'checked' : '' }}>
                            </td>
                            <td>
                                <input class="disabled:opacity-50 disabled:cursor-not-allowed" type="checkbox" disabled {{ $language->is_rtl ? 'checked' : '' }}>
                            </td>
                            <td>
                                {{ $language->name }}
                            </td>
                            <td>
                                <div class="flex justify-end">
                                    @can('language_show')
                                        <a class="btn btn-sm btn-info mr-2" href="{{ route('admin.languages.show', $language) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan
                                    @can('language_edit')
                                        <a class="btn btn-sm btn-success mr-2" href="{{ route('admin.languages.edit', $language) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan
                                    @can('language_delete')
                                        <button class="btn btn-sm btn-rose mr-2" type="button" wire:click="confirm('delete', {{ $language->id }})" wire:loading.attr="disabled">
                                            {{ trans('global.delete') }}
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10">No entries found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-body">
        <div class="pt-3">
            @if($this->selectedCount)
                <p class="text-sm leading-5">
                    <span class="font-medium">
                        {{ $this->selectedCount }}
                    </span>
                    {{ __('Entries selected') }}
                </p>
            @endif
            {{ $languages->links() }}
        </div>
    </div>
</div>

@push('scripts')
    <script>
        Livewire.on('confirm', e => {
    if (!confirm("{{ trans('global.areYouSure') }}")) {
        return
    }
@this[e.callback](...e.argv)
})
    </script>
@endpush