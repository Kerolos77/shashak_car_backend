<div>
    <div class="card-controls sm:flex">
        <div class="w-full sm:w-1/2">
            Per page:
            <select wire:model="perPage" class="form-select w-full sm:w-1/6">
                @foreach($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>

            @can('orders_intercity_delete')
                <button class="btn btn-rose ml-3 disabled:opacity-50 disabled:cursor-not-allowed" type="button" wire:click="confirm('deleteSelected')" wire:loading.attr="disabled" {{ $this->selectedCount ? '' : 'disabled' }}>
                    {{ __('Delete Selected') }}
                </button>
            @endcan

            @if(file_exists(app_path('Http/Livewire/ExcelExport.php')))
                <livewire:excel-export model="OrdersIntercity" format="csv" />
                <livewire:excel-export model="OrdersIntercity" format="xlsx" />
                <livewire:excel-export model="OrdersIntercity" format="pdf" />
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
                            {{ trans('cruds.ordersIntercity.fields.id') }}
                            @include('components.table.sort', ['field' => 'id'])
                        </th>
                        <th>
                            {{ trans('cruds.ordersIntercity.fields.accepted_driver') }}
                            @include('components.table.sort', ['field' => 'accepted_driver'])
                        </th>
                        <th>
                            {{ trans('cruds.ordersIntercity.fields.admin_commission') }}
                            @include('components.table.sort', ['field' => 'admin_commission'])
                        </th>
                        <th>
                            {{ trans('cruds.ordersIntercity.fields.comments') }}
                            @include('components.table.sort', ['field' => 'comments'])
                        </th>
                        <th>
                            {{ trans('cruds.ordersIntercity.fields.destination_city') }}
                            @include('components.table.sort', ['field' => 'destination_city'])
                        </th>
                        <th>
                            {{ trans('cruds.ordersIntercity.fields.destination_location_lat_lng') }}
                            @include('components.table.sort', ['field' => 'destination_location_lat_lng'])
                        </th>
                        <th>
                            {{ trans('cruds.ordersIntercity.fields.destination_location_name') }}
                            @include('components.table.sort', ['field' => 'destination_location_name'])
                        </th>
                        <th>
                            {{ trans('cruds.ordersIntercity.fields.distance') }}
                            @include('components.table.sort', ['field' => 'distance'])
                        </th>
                        <th>
                            {{ trans('cruds.ordersIntercity.fields.distance_type') }}
                            @include('components.table.sort', ['field' => 'distance_type'])
                        </th>
                        <th>
                            {{ trans('cruds.ordersIntercity.fields.driver') }}
                            @include('components.table.sort', ['field' => 'driver.name'])
                        </th>
                        <th>
                            {{ trans('cruds.ordersIntercity.fields.final_rate') }}
                            @include('components.table.sort', ['field' => 'final_rate'])
                        </th>
                        <th>
                            {{ trans('cruds.ordersIntercity.fields.intercity_service') }}
                            @include('components.table.sort', ['field' => 'intercity_service'])
                        </th>
                        <th>
                            {{ trans('cruds.ordersIntercity.fields.intercityservice') }}
                            @include('components.table.sort', ['field' => 'intercityservice.name'])
                        </th>
                        <th>
                            {{ trans('cruds.ordersIntercity.fields.number_of_passenger') }}
                            @include('components.table.sort', ['field' => 'number_of_passenger'])
                        </th>
                        <th>
                            {{ trans('cruds.ordersIntercity.fields.offer_rate') }}
                            @include('components.table.sort', ['field' => 'offer_rate'])
                        </th>
                        <th>
                            {{ trans('cruds.ordersIntercity.fields.otp') }}
                            @include('components.table.sort', ['field' => 'otp'])
                        </th>
                        <th>
                            {{ trans('cruds.ordersIntercity.fields.parcel_dimension') }}
                            @include('components.table.sort', ['field' => 'parcel_dimension'])
                        </th>
                        <th>
                            {{ trans('cruds.ordersIntercity.fields.parcel_image') }}
                            @include('components.table.sort', ['field' => 'parcel_image'])
                        </th>
                        <th>
                            {{ trans('cruds.ordersIntercity.fields.parcel_weight') }}
                            @include('components.table.sort', ['field' => 'parcel_weight'])
                        </th>
                        <th>
                            {{ trans('cruds.ordersIntercity.fields.payment_status') }}
                            @include('components.table.sort', ['field' => 'payment_status'])
                        </th>
                        <th>
                            {{ trans('cruds.ordersIntercity.fields.payment_type') }}
                            @include('components.table.sort', ['field' => 'payment_type'])
                        </th>
                        <th>
                            {{ trans('cruds.ordersIntercity.fields.position') }}
                            @include('components.table.sort', ['field' => 'position'])
                        </th>
                        <th>
                            {{ trans('cruds.ordersIntercity.fields.rejected_driver') }}
                            @include('components.table.sort', ['field' => 'rejected_driver'])
                        </th>
                        <th>
                            {{ trans('cruds.ordersIntercity.fields.source_city') }}
                            @include('components.table.sort', ['field' => 'source_city'])
                        </th>
                        <th>
                            {{ trans('cruds.ordersIntercity.fields.source_location_lat_lng') }}
                            @include('components.table.sort', ['field' => 'source_location_lat_lng'])
                        </th>
                        <th>
                            {{ trans('cruds.ordersIntercity.fields.source_location_name') }}
                            @include('components.table.sort', ['field' => 'source_location_name'])
                        </th>
                        <th>
                            {{ trans('cruds.ordersIntercity.fields.status') }}
                            @include('components.table.sort', ['field' => 'status'])
                        </th>
                        <th>
                            {{ trans('cruds.ordersIntercity.fields.tax_list') }}
                            @include('components.table.sort', ['field' => 'tax_list'])
                        </th>
                        <th>
                            {{ trans('cruds.ordersIntercity.fields.user') }}
                            @include('components.table.sort', ['field' => 'user.name'])
                        </th>
                        <th>
                            {{ trans('cruds.ordersIntercity.fields.when_dates') }}
                            @include('components.table.sort', ['field' => 'when_dates'])
                        </th>
                        <th>
                            {{ trans('cruds.ordersIntercity.fields.when_time') }}
                            @include('components.table.sort', ['field' => 'when_time'])
                        </th>
                        <th>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ordersIntercities as $ordersIntercity)
                        <tr>
                            <td>
                                <input type="checkbox" value="{{ $ordersIntercity->id }}" wire:model="selected">
                            </td>
                            <td>
                                {{ $ordersIntercity->id }}
                            </td>
                            <td>
                                {{ $ordersIntercity->accepted_driver }}
                            </td>
                            <td>
                                {{ $ordersIntercity->admin_commission }}
                            </td>
                            <td>
                                {{ $ordersIntercity->comments }}
                            </td>
                            <td>
                                {{ $ordersIntercity->destination_city }}
                            </td>
                            <td>
                                {{ $ordersIntercity->destination_location_lat_lng }}
                            </td>
                            <td>
                                {{ $ordersIntercity->destination_location_name }}
                            </td>
                            <td>
                                {{ $ordersIntercity->distance }}
                            </td>
                            <td>
                                {{ $ordersIntercity->distance_type }}
                            </td>
                            <td>
                                @if($ordersIntercity->driver)
                                    <span class="badge badge-relationship">{{ $ordersIntercity->driver->name ?? '' }}</span>
                                @endif
                            </td>
                            <td>
                                {{ $ordersIntercity->final_rate }}
                            </td>
                            <td>
                                {{ $ordersIntercity->intercity_service }}
                            </td>
                            <td>
                                @if($ordersIntercity->intercityservice)
                                    <span class="badge badge-relationship">{{ $ordersIntercity->intercityservice->name ?? '' }}</span>
                                @endif
                            </td>
                            <td>
                                {{ $ordersIntercity->number_of_passenger }}
                            </td>
                            <td>
                                {{ $ordersIntercity->offer_rate }}
                            </td>
                            <td>
                                {{ $ordersIntercity->otp }}
                            </td>
                            <td>
                                {{ $ordersIntercity->parcel_dimension }}
                            </td>
                            <td>
                                {{ $ordersIntercity->parcel_image }}
                            </td>
                            <td>
                                {{ $ordersIntercity->parcel_weight }}
                            </td>
                            <td>
                                {{ $ordersIntercity->payment_status }}
                            </td>
                            <td>
                                {{ $ordersIntercity->payment_type }}
                            </td>
                            <td>
                                {{ $ordersIntercity->position }}
                            </td>
                            <td>
                                {{ $ordersIntercity->rejected_driver }}
                            </td>
                            <td>
                                {{ $ordersIntercity->source_city }}
                            </td>
                            <td>
                                {{ $ordersIntercity->source_location_lat_lng }}
                            </td>
                            <td>
                                {{ $ordersIntercity->source_location_name }}
                            </td>
                            <td>
                                {{ $ordersIntercity->status }}
                            </td>
                            <td>
                                {{ $ordersIntercity->tax_list }}
                            </td>
                            <td>
                                @if($ordersIntercity->user)
                                    <span class="badge badge-relationship">{{ $ordersIntercity->user->name ?? '' }}</span>
                                @endif
                            </td>
                            <td>
                                {{ $ordersIntercity->when_dates }}
                            </td>
                            <td>
                                {{ $ordersIntercity->when_time }}
                            </td>
                            <td>
                                <div class="flex justify-end">
                                    @can('orders_intercity_show')
                                        <a class="btn btn-sm btn-info mr-2" href="{{ route('admin.orders-intercities.show', $ordersIntercity) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan
                                    @can('orders_intercity_edit')
                                        <a class="btn btn-sm btn-success mr-2" href="{{ route('admin.orders-intercities.edit', $ordersIntercity) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan
                                    @can('orders_intercity_delete')
                                        <button class="btn btn-sm btn-rose mr-2" type="button" wire:click="confirm('delete', {{ $ordersIntercity->id }})" wire:loading.attr="disabled">
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
            {{ $ordersIntercities->links() }}
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