<div>
    <div class="card-controls sm:flex">
        <div class="w-full sm:w-1/2">
            Per page:
            <select wire:model="perPage" class="form-select w-full sm:w-1/6">
                @foreach($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>

            @can('freight_vehicle_delete')
                <button class="btn btn-rose ml-3 disabled:opacity-50 disabled:cursor-not-allowed" type="button" wire:click="confirm('deleteSelected')" wire:loading.attr="disabled" {{ $this->selectedCount ? '' : 'disabled' }}>
                    {{ __('Delete Selected') }}
                </button>
            @endcan

            @if(file_exists(app_path('Http/Livewire/ExcelExport.php')))
                <livewire:excel-export model="FreightVehicle" format="csv" />
                <livewire:excel-export model="FreightVehicle" format="xlsx" />
                <livewire:excel-export model="FreightVehicle" format="pdf" />
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
                            {{ trans('cruds.freightVehicle.fields.id') }}
                            @include('components.table.sort', ['field' => 'id'])
                        </th>
                        <th>
                            {{ trans('cruds.freightVehicle.fields.description') }}
                            @include('components.table.sort', ['field' => 'description'])
                        </th>
                        <th>
                            {{ trans('cruds.freightVehicle.fields.enable') }}
                            @include('components.table.sort', ['field' => 'enable'])
                        </th>
                        <th>
                            {{ trans('cruds.freightVehicle.fields.height') }}
                            @include('components.table.sort', ['field' => 'height'])
                        </th>
                        <th>
                            {{ trans('cruds.freightVehicle.fields.image') }}
                            @include('components.table.sort', ['field' => 'image'])
                        </th>
                        <th>
                            {{ trans('cruds.freightVehicle.fields.km_charge') }}
                            @include('components.table.sort', ['field' => 'km_charge'])
                        </th>
                        <th>
                            {{ trans('cruds.freightVehicle.fields.length') }}
                            @include('components.table.sort', ['field' => 'length'])
                        </th>
                        <th>
                            {{ trans('cruds.freightVehicle.fields.name') }}
                            @include('components.table.sort', ['field' => 'name'])
                        </th>
                        <th>
                            {{ trans('cruds.freightVehicle.fields.width') }}
                            @include('components.table.sort', ['field' => 'width'])
                        </th>
                        <th>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($freightVehicles as $freightVehicle)
                        <tr>
                            <td>
                                <input type="checkbox" value="{{ $freightVehicle->id }}" wire:model="selected">
                            </td>
                            <td>
                                {{ $freightVehicle->id }}
                            </td>
                            <td>
                                {{ $freightVehicle->description }}
                            </td>
                            <td>
                                <input class="disabled:opacity-50 disabled:cursor-not-allowed" type="checkbox" disabled {{ $freightVehicle->enable ? 'checked' : '' }}>
                            </td>
                            <td>
                                {{ $freightVehicle->height }}
                            </td>
                            <td>
                                {{ $freightVehicle->image }}
                            </td>
                            <td>
                                {{ $freightVehicle->km_charge }}
                            </td>
                            <td>
                                {{ $freightVehicle->length }}
                            </td>
                            <td>
                                {{ $freightVehicle->name }}
                            </td>
                            <td>
                                {{ $freightVehicle->width }}
                            </td>
                            <td>
                                <div class="flex justify-end">
                                    @can('freight_vehicle_show')
                                        <a class="btn btn-sm btn-info mr-2" href="{{ route('admin.freight-vehicles.show', $freightVehicle) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan
                                    @can('freight_vehicle_edit')
                                        <a class="btn btn-sm btn-success mr-2" href="{{ route('admin.freight-vehicles.edit', $freightVehicle) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan
                                    @can('freight_vehicle_delete')
                                        <button class="btn btn-sm btn-rose mr-2" type="button" wire:click="confirm('delete', {{ $freightVehicle->id }})" wire:loading.attr="disabled">
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
            {{ $freightVehicles->links() }}
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