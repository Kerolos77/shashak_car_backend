<div>
    <div class="card-controls sm:flex">
        <div class="w-full sm:w-1/2">
            Per page:
            <select wire:model="perPage" class="form-select w-full sm:w-1/6">
                @foreach($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>

            @can('on_boarding_delete')
                <button class="btn btn-rose ml-3 disabled:opacity-50 disabled:cursor-not-allowed" type="button" wire:click="confirm('deleteSelected')" wire:loading.attr="disabled" {{ $this->selectedCount ? '' : 'disabled' }}>
                    {{ __('Delete Selected') }}
                </button>
            @endcan

            @if(file_exists(app_path('Http/Livewire/ExcelExport.php')))
                <livewire:excel-export model="OnBoarding" format="csv" />
                <livewire:excel-export model="OnBoarding" format="xlsx" />
                <livewire:excel-export model="OnBoarding" format="pdf" />
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
                            {{ trans('cruds.onBoarding.fields.id') }}
                            @include('components.table.sort', ['field' => 'id'])
                        </th>
                        <th>
                            {{ trans('cruds.onBoarding.fields.description') }}
                            @include('components.table.sort', ['field' => 'description'])
                        </th>
                        <th>
                            {{ trans('cruds.onBoarding.fields.image') }}
                            @include('components.table.sort', ['field' => 'image'])
                        </th>
                        <th>
                            {{ trans('cruds.onBoarding.fields.title') }}
                            @include('components.table.sort', ['field' => 'title'])
                        </th>
                        <th>
                            {{ trans('cruds.onBoarding.fields.type') }}
                            @include('components.table.sort', ['field' => 'type'])
                        </th>
                        <th>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($onBoardings as $onBoarding)
                        <tr>
                            <td>
                                <input type="checkbox" value="{{ $onBoarding->id }}" wire:model="selected">
                            </td>
                            <td>
                                {{ $onBoarding->id }}
                            </td>
                            <td>
                                {{ $onBoarding->description }}
                            </td>
                            <td>
                                {{ $onBoarding->image }}
                            </td>
                            <td>
                                {{ $onBoarding->title }}
                            </td>
                            <td>
                                {{ $onBoarding->type }}
                            </td>
                            <td>
                                <div class="flex justify-end">
                                    @can('on_boarding_show')
                                        <a class="btn btn-sm btn-info mr-2" href="{{ route('admin.on-boardings.show', $onBoarding) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan
                                    @can('on_boarding_edit')
                                        <a class="btn btn-sm btn-success mr-2" href="{{ route('admin.on-boardings.edit', $onBoarding) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan
                                    @can('on_boarding_delete')
                                        <button class="btn btn-sm btn-rose mr-2" type="button" wire:click="confirm('delete', {{ $onBoarding->id }})" wire:loading.attr="disabled">
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
            {{ $onBoardings->links() }}
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