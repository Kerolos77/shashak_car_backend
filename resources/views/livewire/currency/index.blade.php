<div>
    <div class="card-controls sm:flex">
        <div class="w-full sm:w-1/2">
            Per page:
            <select wire:model="perPage" class="form-select w-full sm:w-1/6">
                @foreach($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>

            @can('currency_delete')
                <button class="btn btn-rose ml-3 disabled:opacity-50 disabled:cursor-not-allowed" type="button" wire:click="confirm('deleteSelected')" wire:loading.attr="disabled" {{ $this->selectedCount ? '' : 'disabled' }}>
                    {{ __('Delete Selected') }}
                </button>
            @endcan

            @if(file_exists(app_path('Http/Livewire/ExcelExport.php')))
                <livewire:excel-export model="Currency" format="csv" />
                <livewire:excel-export model="Currency" format="xlsx" />
                <livewire:excel-export model="Currency" format="pdf" />
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
                            {{ trans('cruds.currency.fields.id') }}
                            @include('components.table.sort', ['field' => 'id'])
                        </th>
                        <th>
                            {{ trans('cruds.currency.fields.code') }}
                            @include('components.table.sort', ['field' => 'code'])
                        </th>
                        <th>
                            {{ trans('cruds.currency.fields.decimal_digits') }}
                            @include('components.table.sort', ['field' => 'decimal_digits'])
                        </th>
                        <th>
                            {{ trans('cruds.currency.fields.enable') }}
                            @include('components.table.sort', ['field' => 'enable'])
                        </th>
                        <th>
                            {{ trans('cruds.currency.fields.name') }}
                            @include('components.table.sort', ['field' => 'name'])
                        </th>
                        <th>
                            {{ trans('cruds.currency.fields.symbol') }}
                            @include('components.table.sort', ['field' => 'symbol'])
                        </th>
                        <th>
                            {{ trans('cruds.currency.fields.symbol_at_right') }}
                            @include('components.table.sort', ['field' => 'symbol_at_right'])
                        </th>
                        <th>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($currencies as $currency)
                        <tr>
                            <td>
                                <input type="checkbox" value="{{ $currency->id }}" wire:model="selected">
                            </td>
                            <td>
                                {{ $currency->id }}
                            </td>
                            <td>
                                {{ $currency->code }}
                            </td>
                            <td>
                                {{ $currency->decimal_digits }}
                            </td>
                            <td>
                                <input class="disabled:opacity-50 disabled:cursor-not-allowed" type="checkbox" disabled {{ $currency->enable ? 'checked' : '' }}>
                            </td>
                            <td>
                                {{ $currency->name }}
                            </td>
                            <td>
                                {{ $currency->symbol }}
                            </td>
                            <td>
                                <input class="disabled:opacity-50 disabled:cursor-not-allowed" type="checkbox" disabled {{ $currency->symbol_at_right ? 'checked' : '' }}>
                            </td>
                            <td>
                                <div class="flex justify-end">
                                    @can('currency_show')
                                        <a class="btn btn-sm btn-info mr-2" href="{{ route('admin.currencies.show', $currency) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan
                                    @can('currency_edit')
                                        <a class="btn btn-sm btn-success mr-2" href="{{ route('admin.currencies.edit', $currency) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan
                                    @can('currency_delete')
                                        <button class="btn btn-sm btn-rose mr-2" type="button" wire:click="confirm('delete', {{ $currency->id }})" wire:loading.attr="disabled">
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
            {{ $currencies->links() }}
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